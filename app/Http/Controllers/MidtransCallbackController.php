<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // Configure Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        try {
            // Get notification from Midtrans
            $notification = new \Midtrans\Notification();
            
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            $orderId = $notification->order_id;
            
            Log::info('Midtrans Callback', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
            ]);
            
            // Find order
            $order = Order::where('order_code', $orderId)->first();
            
            if (!$order) {
                Log::error('Order not found: ' . $orderId);
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }
            
            // Update or create payment record
            $payment = Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'gateway' => 'midtrans',
                    'gateway_transaction_id' => $notification->transaction_id,
                    'payment_type' => $this->mapPaymentType($notification->payment_type),
                    'payment_method' => $notification->payment_type,
                    'amount' => $notification->gross_amount,
                    'currency' => $notification->currency ?? 'IDR',
                    'status' => $this->mapStatus($transactionStatus, $fraudStatus),
                    'fraud_status' => $fraudStatus ?? null,
                    'payload' => (array) $notification,
                    'paid_at' => in_array($transactionStatus, ['capture', 'settlement']) ? now() : null,
                ]
            );
            
            // Update order status based on payment status
            if ($payment->status == 'success') {
                if ($order->status !== 'paid') {
                    $order->status = 'paid';
                    $this->reduceStock($order);
                }
            } elseif ($payment->status == 'failed' || $payment->status == 'expired') {
                $order->status = 'cancelled';
            } elseif ($payment->status == 'pending') {
                $order->status = 'pending';
            }
            
            $order->save();
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    public function finish(Request $request)
    {
        // Configure Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        // Parameter order_id kita kirim di URL callback settings
        // Tapi Midtrans juga mengirim order_id sebagai query param
        $orderCode = $request->query('order_id'); 
        
        if (!$orderCode) {
            return redirect()->route('cart');
        }
        
        $order = Order::where('order_code', $orderCode)->first();
        
        if (!$order) {
            return redirect()->route('cart')->with('error', 'Order not found');
        }

        try {
            // Cek status langsung ke API Midtrans untuk memastikan data akurat
            // Ini membantu jika callback telat atau gagal, terutama di environment Sandbox/Local
            $status = (object) \Midtrans\Transaction::status($orderCode);
            $transactionStatus = $status->transaction_status ?? null;
            $fraudStatus = $status->fraud_status ?? null;
            $paymentType = $status->payment_type ?? null;
            
            Log::info('Midtrans Finish Check', [
                'order_id' => $orderCode,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus
            ]);
            
            // Map status Midtrans ke status aplikasi kita
            $paymentStatus = $this->mapStatus($transactionStatus, $fraudStatus);
            
            // Simpan data payment jika belum ada atau perlu update
            $payment = Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'gateway' => 'midtrans',
                    'gateway_transaction_id' => $status->transaction_id ?? null,
                    'payment_type' => $this->mapPaymentType($paymentType ?? 'unknown'),
                    'payment_method' => $paymentType ?? 'unknown',
                    'amount' => $status->gross_amount ?? $order->total_price,
                    'currency' => $status->currency ?? 'IDR',
                    'status' => $paymentStatus,
                    'fraud_status' => $fraudStatus,
                    'payload' => (array) $status,
                    'paid_at' => $paymentStatus == 'success' ? now() : null,
                ]
            );

            // Update status order berdasarkan hasil cek API
            if ($paymentStatus == 'success') {
                if ($order->status !== 'paid') {
                    $order->status = 'paid';
                    $order->save();
                    $this->reduceStock($order);
                }
            } elseif ($paymentStatus == 'failed' || $paymentStatus == 'expired') {
                $order->status = 'cancelled';
                $order->save();
            } elseif ($paymentStatus == 'pending') {
                $order->status = 'pending';
                $order->save();
            }

        } catch (\Exception $e) {
            Log::error('Midtrans Finish Error: ' . $e->getMessage());
            
            // Fallback: Jika cek API gagal, gunakan parameter query (cara lama)
            // Ini jarang terjadi, tapi sebagai backup
            $transactionStatus = $request->query('transaction_status');
            $previousStatus = $order->status;
            
            if ($transactionStatus) {
                if (in_array($transactionStatus, ['capture', 'settlement'])) {
                    if ($previousStatus !== 'paid') {
                        $order->status = 'paid';
                        $order->save();
                        $this->reduceStock($order);
                    }
                } elseif ($transactionStatus == 'pending') {
                    $order->status = 'pending';
                    $order->save();
                }
            }
        }
        
        // Redirect based on order status (refresh data)
        $order->refresh();
        if ($order->status === 'paid') {
            return redirect()->route('payment.success', ['orderId' => $orderCode]);
        } elseif ($order->status === 'pending') {
             return redirect()->route('payment.success', ['orderId' => $orderCode]);
        } else {
            return redirect()->route('payment.failed', ['orderId' => $orderCode]);
        }
    }
    
    /**
     * Map Midtrans transaction status to our payment status
     */
    private function mapStatus($transactionStatus, $fraudStatus = null)
    {
        if ($transactionStatus == 'capture') {
            return $fraudStatus == 'accept' ? 'success' : 'pending';
        }
        
        if ($transactionStatus == 'settlement') {
            return 'success';
        }
        
        if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            return $transactionStatus == 'expire' ? 'expired' : 'failed';
        }
        
        return 'pending';
    }
    
    /**
     * Map Midtrans payment type to simplified category
     */
    private function mapPaymentType($paymentType)
    {
        if (!$paymentType) {
            return 'unknown';
        }

        if (strpos($paymentType, 'bank_transfer') !== false || strpos($paymentType, '_va') !== false) {
            return 'va';
        }
        
        if (in_array($paymentType, ['gopay', 'shopeepay', 'qris'])) {
            return 'ewallet';
        }
        
        if ($paymentType == 'credit_card') {
            return 'cc';
        }
        
        return $paymentType;
    }

    /**
     * Reduce product stock based on order items
     */
    private function reduceStock(Order $order)
    {
        foreach ($order->items as $item) {
            $variant = \App\Models\ProductVariant::find($item->product_variant_id);
            if ($variant) {
                $variant->stock = max(0, $variant->stock - $item->quantity);
                $variant->save();
            }
        }
    }
}
