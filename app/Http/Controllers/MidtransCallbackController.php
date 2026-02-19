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
        
        // Handle Midtrans Redirect Parameters directly (Helpful for localhost where callback might fail)
        $transactionStatus = $request->query('transaction_status');
        
        // Check current status before update to avoid double stock reduction
        $previousStatus = $order->status;
        
        if ($transactionStatus) {
            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                if ($previousStatus !== 'paid') {
                    $order->status = 'paid';
                    $order->save();
                    $this->reduceStock($order);
                }
                
                // Create dummy payment record if not exists (for comprehensive data)
                if (!$order->payment) {
                    Payment::create([
                        'order_id' => $order->id,
                        'gateway' => 'midtrans',
                        'amount' => $order->total_price,
                        'status' => 'success',
                        'payment_type' => 'manual_check',
                        'paid_at' => now(),
                    ]);
                }
            } elseif ($transactionStatus == 'pending') {
                $order->status = 'pending';
                $order->save();
            }
        }
        
        // Redirect based on order status (re-fetch to be sure)
        $order->refresh();
        if ($order->status === 'paid') {
            return redirect()->route('payment.success', ['orderId' => $orderCode]);
        } elseif ($order->status === 'pending') {
             // For pending, we can show success page but with "Pending" status content
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
