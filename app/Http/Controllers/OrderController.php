<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponse;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $orders = Order::with(['service', 'merchant', 'user', 'merchantUser', 'reviews'])->get();
        return ApiResponse::success($orders);
    }

    /**
     * Store a newly created order in storage using authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setOrderByMy(Request $request)
    {
        $request->validate([
            'price' => 'required|numeric',
            'service_id' => 'required|exists:services,id',
            'merchant_id' => 'required|exists:merchants,id',
            'merchant_user_id' => 'required|exists:users,id',
            'estimated_date' => 'required|date',
            'user_file_url' => 'nullable|url',
            'comment_json' => 'nullable|array',
            'meet_url' => 'nullable|url',
            'order_status' => 'required|string',
        ]);

        $order = Order::create([
            'price' => $request->price,
            'service_id' => $request->service_id,
            'merchant_id' => $request->merchant_id,
            'merchant_user_id' => $request->merchant_user_id,
            'estimated_date' => $request->estimated_date,
            'user_id' => Auth::id(),  // Use authenticated user's ID
            'user_file_url' => $request->user_file_url,
            'comment_json' => $request->comment_json,
            'meet_url' => $request->meet_url,
            'order_status' => $request->order_status,
        ]);

        return ApiResponse::success($order, 'Order created successfully.');
    }

    /**
     * Display the specified order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Order $order)
    {
        return ApiResponse::success($order->load(['service', 'merchant', 'user', 'merchantUser', 'reviews']));
    }

    /**
     * Update the specified order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'price' => 'sometimes|numeric',
            'service_id' => 'sometimes|exists:services,id',
            'merchant_id' => 'sometimes|exists:merchants,id',
            'merchant_user_id' => 'sometimes|exists:users,id',
            'estimated_date' => 'sometimes|date',
            'user_file_url' => 'nullable|url',
            'comment_json' => 'nullable|array',
            'meet_url' => 'nullable|url',
            'order_status' => 'sometimes|string',
        ]);

        $order->update($request->all());

        return ApiResponse::success($order, 'Order updated successfully.');
    }

    /**
     * Remove the specified order from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return ApiResponse::success(null, 'Order deleted successfully.');
    }
}
