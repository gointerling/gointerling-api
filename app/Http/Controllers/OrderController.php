<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use App\Models\Service;
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
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->query('search', '');
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $orderStatus = $request->query('order_status', '');

        $query = Order::with(['service', 'merchant', 'user', 'merchantUser', 'reviews'])
                    ->orderBy('created_at', 'desc'); // Sort by latest orders

        if (!$user->is_admin) {
            // If the user is not an admin, they can only see their own orders
            $query->where('user_id', $user->id);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                ->orWhereHas('user', function($q) use ($search) {
                    $q->where('fullname', 'like', "%$search%");
                })
                ->orWhereHas('merchantUser', function($q) use ($search) {
                    $q->where('fullname', 'like', "%$search%");
                });
            });
        }

        if ($orderStatus) {
            $query->where('order_status', $orderStatus);
        }

        $orders = $query->paginate($perPage, ['*'], 'page', $page);

        return ApiResponse::send(200, compact('orders'), 'Orders retrieved successfully.');
    }


    /**
     * Store a newly created order in storage using authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setMyOrder(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'merchant_id' => 'required|exists:merchants,id',
            'merchant_user_id' => 'required|exists:users,id',
            'language_source' => 'required',
            'language_destination' => 'required',
            'estimated_date' => 'nullable|date',
            'user_file_url' => 'nullable|url',
            'comment_json' => 'nullable|array',
            'meet_url' => 'nullable|url',
            // Validation rules for review
        ]);

        // check if the authenticated user is the same as the merchant_user_id
        if (Auth::id() === $request->merchant_user_id) {
            // Return error response
            return ApiResponse::send(403, null, 'You cannot create an order with yourself as the merchant.');
        }

        // get price from service
        $price = Service::find($request->service_id)->price;

        $order = Order::create([
            'price' => $price,
            'service_id' => $request->service_id,
            'merchant_id' => $request->merchant_id,
            'merchant_user_id' => $request->merchant_user_id,
            'estimated_date' => $request->estimated_date,
            'user_id' => Auth::id(),  // Use authenticated user's ID
            'user_file_url' => $request->user_file_url,
            'comment_json' => $request->comment_json,
            'meet_url' => $request->meet_url,
            'order_status' =>'waitingpaid',
            'language_source' => $request->language_source,
            'language_destination' => $request->language_destination,
        ]);

        if ($request->has('review')) {
            $review = Review::create([
                'rating' => $request->input('review.rating'),
                'review_message' => $request->input('review.review_message'),
                'order_id' => $order->id,
                'merchant_user_id' => $request->input('review.merchant_user_id'),
                'reviewer_id' => Auth::id(),  // Use authenticated user's ID
            ]);
        }

        return ApiResponse::send(
            201,
            compact('order'),
            'Order created successfully.'
        );
    }



    /**
     * Display the specified order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Order $order)
    {
        $order->load(['service', 'merchant', 'user', 'merchantUser', 'reviews']);
        return ApiResponse::send(200, compact('order'), 'Order retrieved successfully.');
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
            'service_id' => 'sometimes|exists:services,id',
            'merchant_id' => 'sometimes|exists:merchants,id',
            'merchant_user_id' => 'sometimes|exists:users,id',
            'estimated_date' => 'sometimes|date',
            'user_file_url' => 'nullable|url',
            'comment_json' => 'nullable|array',
            'meet_url' => 'nullable|url',
            'order_status' => 'sometimes|string',
            'language_source' => 'sometimes|array',
            'language_destination' => 'sometimes|array',
            'result_file_url' => 'nullable|url',
            'payment_file_url' => 'nullable|url',
        ]);

        // Check if the authenticated user is the same as the merchant_user_id or admin
        if (Auth::id() !== $order->merchant_user_id && !Auth::user()->is_admin) {
            // Return error response
            return ApiResponse::send(403, null, 'You cannot update this order.');
        }

        // Check if result_file_url is provided in the request
        if ($request->has('result_file_url') && !empty($request->input('result_file_url'))) {
            $request->merge(['order_status' => 'completed']);
        }

        $order->update($request->all());

        return ApiResponse::send(
            200,
            compact('order'),
            'Order updated successfully.'
        );
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
        return ApiResponse::send(200, null, 'Order deleted successfully.');
    }

    public function getMyOrder(Request $request)
    {
        $search = $request->query('search', '');
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $orderStatus = $request->query('order_status', '');

        $query = Order::where('user_id', Auth::id())
                    ->with(['service', 'merchant', 'user', 'merchantUser', 'reviews'])
                    ->orderBy('created_at', 'desc'); // Sort by latest orders

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                ->orWhereHas('merchantUser', function($q) use ($search) {
                    $q->where('fullname', 'like', "%$search%");
                });
            });
        }

        if ($orderStatus) {
            $query->where('order_status', $orderStatus);
        }

        $orders = $query->paginate($perPage, ['*'], 'page', $page);

        return ApiResponse::send(200, compact('orders'), 'Orders retrieved successfully.');
    }

    public function getMyMerchantOrder(Request $request)
    {
        $search = $request->query('search', '');
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $orderStatus = $request->query('order_status', '');

        $query = Order::where('merchant_user_id', Auth::id())
                    ->with(['service', 'merchant', 'user', 'merchantUser', 'reviews'])
                    ->orderBy('created_at', 'desc'); // Sort by latest orders

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                ->orWhereHas('user', function($q) use ($search) {
                    $q->where('fullname', 'like', "%$search%");
                });
            });
        }

        if ($orderStatus) {
            $query->where('order_status', $orderStatus);
        }

        $orders = $query->paginate($perPage, ['*'], 'page', $page);

        return ApiResponse::send(200, compact('orders'), 'Orders retrieved successfully.');
    }


    public function updateMyOrder(Request $request, Order $order)
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
            'payment_file_url' => 'nullable|url',
        ]);

        $order->update($request->all());

        return ApiResponse::send(
            200,
            compact('order'),
            'Order updated successfully.'
        );
    }

    public function updateClientStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|string|in:pending,waitingpaid,paid,completed,failed,refund'
        ]);

        $order->update($request->all());

        return ApiResponse::send(
            200,
            compact('order'),
            'Order status updated successfully.'
        );
    }

}
