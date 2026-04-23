<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // For production, you should verify the x-callback-token
        $callbackToken = $request->header('x-callback-token');
        $expectedToken = config('xendit.webhook_token') ?? env('XENDIT_CALLBACK_TOKEN');

        if ($expectedToken && $callbackToken !== $expectedToken) {
            Log::warning('Xendit Webhook Invalid Token');
            return response()->json(['message' => 'Invalid token'], 403);
        }

        $orderId = $request->input('external_id');
        $status = $request->input('status'); // e.g. 'PAID', 'EXPIRED'
        $paymentMethod = $request->input('payment_method');
        $paymentChannel = $request->input('payment_channel');
        
        Log::info('Xendit Webhook Received', [
            'order_id' => $orderId,
            'status' => $status,
        ]);

        $order = Order::where('order_code', $orderId)->first();

        if (!$order) {
            Log::error('Order not found: ' . $orderId);
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        $paymentStatus = $this->mapStatus($status);

        $payment = Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'gateway' => 'xendit',
                'gateway_transaction_id' => $request->input('id'),
                'payment_type' => $paymentMethod ?? 'unknown',
                'payment_method' => $paymentChannel ?? 'unknown',
                'amount' => $request->input('paid_amount') ?? $request->input('amount'),
                'currency' => $request->input('currency') ?? 'IDR',
                'status' => $paymentStatus,
                'payload' => $request->all(),
                'paid_at' => $paymentStatus == 'success' ? now() : null,
            ]
        );

        if ($paymentStatus == 'success') {
            if ($order->status !== 'paid') {
                $order->status = 'paid';
                $this->reduceStock($order);
            }
        } elseif ($paymentStatus == 'failed' || $paymentStatus == 'expired') {
            $order->status = 'cancelled';
        }

        $order->save();

        return response()->json(['status' => 'success', 'message' => 'Webhook received successfully']);
    }

    private function mapStatus($status)
    {
        if ($status == 'PAID' || $status == 'COMPLETED' || $status == 'SETTLED') {
            return 'success';
        }
        
        if (in_array($status, ['EXPIRED', 'FAILED'])) {
            return 'failed';
        }
        
        return 'pending';
    }

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
