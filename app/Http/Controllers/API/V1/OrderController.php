<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Http\Resources\OrderResource;
use App\Http\Requests\OrderRequest;
use App\Http\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * @OA\Get (
     *      path="/api/v1/orders",
     *      tags={"Order"},
     *      summary="Paginated transaction list",
     *      description="Paginated list of transactions",
     *      operationId="originList",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data", 
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(
     *                          property="id", 
     *                          type="number", 
     *                          example=1
     *                      ),
     *                      @OA\Property(
     *                          property="total", 
     *                          type="string", 
     *                          example="70000"
     *                      ),
     *                      @OA\Property(
     *                          property="status", 
     *                          type="string",
     *                          enum={"unpaid", "paid"},
     *                          example="paid",
     *                      ),
     *                      @OA\Property(
     *                          property="updated_at", 
     *                          type="string", 
     *                          example="2023-08-28 06:06:17",
     *                      ),
     *                      @OA\Property(
     *                          property="created_at", 
     *                          type="string", 
     *                          example="2023-08-28 06:06:17"
     *                      ),
     *                      @OA\Property(
     *                          property="items", 
     *                          type="array",
     *                          @OA\Items(
     *                              type="object",
     *                              @OA\Property(
     *                                  property="id", 
     *                                  type="number", 
     *                                  example=1
     *                              ),
     *                              @OA\Property(
     *                                  property="name", 
     *                                  type="string", 
     *                                  example="product 1"
     *                              ),
     *                              @OA\Property(
     *                                  property="price", 
     *                                  type="string", 
     *                                  example="10000"
     *                              ),
     *                              @OA\Property(
     *                                  property="updated_at", 
     *                                  type="string", 
     *                                  example="2023-08-28 06:06:17"
     *                              ),
     *                              @OA\Property(
     *                                  property="created_at", 
     *                                  type="string", 
     *                                  example="2023-08-28 06:06:17"
     *                              ),
     *                          ),
     *                      ),
     *                  ),
     *              ),
     *          )
     *      ),
     * )
     */
    public function index()
    {
        $orders = Order::with('items')->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(5);

        return OrderResource::collection($orders);
    }

    /**
     * @OA\Get (
     *     path="/api/v1/order/unpaid",
     *     tags={"Order"},
     *     summary="Unpaid transaction",
     *     description="view a unpaid invoice",
     *     operationId="orderShow",
     *     security={
     *          {"bearerAuth": {}}
     *     },
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data", 
     *                  type="object",
     *                  @OA\Property(
     *                      property="id", 
     *                      type="number", 
     *                      example=1
     *                  ),
     *                  @OA\Property(
     *                      property="total", 
     *                      type="string", 
     *                      example="70000"
     *                  ),
     *                  @OA\Property(
     *                      property="status", 
     *                      type="string",
     *                      enum={"unpaid", "paid"},
     *                      example="unpaid"
     *                  ),
     *                  @OA\Property(
     *                      property="updated_at", 
     *                      type="string", 
     *                      example="2023-08-28 06:06:17"
     *                  ),
     *                  @OA\Property(
     *                      property="created_at", 
     *                      type="string", 
     *                      example="2023-08-28 06:06:17"
     *                  ),
     *                  @OA\Property(
     *                      property="items", 
     *                      type="array",
     *                      example={
     *                          {
     *                              "id":1,
     *                              "name":"product 1",
     *                              "price":"10000",
     *                              "updated_at":"2023-08-28 06:06:17",
     *                              "created_at":"2023-08-28 06:06:17"
     *                          } 
     *                      },
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(
     *                              property="id", 
     *                              type="number",
     *                          ),
     *                          @OA\Property(
     *                              property="name", 
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="price", 
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="updated_at", 
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="created_at", 
     *                              type="string",
     *                          ),
     *                      ),
     *                  ),
     *              ),
     *          )
     *      ),
     * )
     */
    public function show()
    {
        $order = Order::with('items')->where('user_id', Auth::user()->id)->where('status', 'unpaid')->first();

        return new OrderResource($order);
    }

    /**
     * @OA\Post (
     *     path="/api/v1/product/{product}/order",
     *     tags={"Order"},
     *     summary="Add a shopping item",
     *     description="Add a shopping item to buy",
     *     operationId="orderCreate",
     *     security={
     *          {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="price",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={
     *                     "name":"Product 1",
     *                     "price":"10000"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data", 
     *                  type="object",
     *                  @OA\Property(
     *                      property="id", 
     *                      type="number", 
     *                      example=1
     *                  ),
     *                  @OA\Property(
     *                      property="total", 
     *                      type="string", 
     *                      example="10000"
     *                  ),
     *                  @OA\Property(
     *                      property="status", 
     *                      type="string", 
     *                      enum={"unpaid", "paid"},
     *                      example="unpaid"
     *                  ),
     *                  @OA\Property(
     *                      property="updated_at", 
     *                      type="string", 
     *                      example="2023-08-28 06:06:17"
     *                  ),
     *                  @OA\Property(
     *                      property="created_at", 
     *                      type="string", 
     *                      example="2023-08-28 06:06:17"
     *                  ),
     *                  @OA\Property(
     *                      property="items", 
     *                      type="array",
     *                      example={
     *                          {
     *                              "id":1,
     *                              "name":"product 1",
     *                              "price":"10000",
     *                              "updated_at":"2023-08-28 06:06:17",
     *                              "created_at":"2023-08-28 06:06:17"
     *                          } 
     *                      },
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(
     *                              property="id", 
     *                              type="number",
     *                          ),
     *                          @OA\Property(
     *                              property="name", 
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="price", 
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="updated_at", 
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="created_at", 
     *                              type="string",
     *                          ),
     *                      ),
     *                  ),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Invalid data provided"
     *              ),
     *              @OA\Property(
     *                  property="errors", 
     *                  type="string", 
     *                  example="Invalid data provided"
     *              ),
     *          )
     *      )
     * )
     */
    public function store(OrderService $service, Product $product)
    {
        //$product = Product::find($id);
        
        if(!$product) {
            return response()->json([
                "message" => "Data not found",
            ], 404);
        }

        $order = $service->store($product);

        return new OrderResource($order);
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/order/item/{order_item}",
     *     tags={"Order"},
     *     summary="Remove a shopping item",
     *     description="Remove a shopping item from cart",
     *     operationId="orderRemove",
     *     security={
     *          {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *          description="ID of order item",
     *          in="path",
     *          name="order_item",
     *          required=true,
     *          example="1",
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data", 
     *                  type="object",
     *                  @OA\Property(
     *                      property="id", 
     *                      type="number", 
     *                      example=1
     *                  ),
     *                  @OA\Property(
     *                      property="total", 
     *                      type="string", 
     *                      example="10000"
     *                  ),
     *                  @OA\Property(
     *                      property="status", 
     *                      type="string", 
     *                      enum={"unpaid", "paid"},
     *                      example="unpaid"
     *                  ),
     *                  @OA\Property(
     *                      property="updated_at", 
     *                      type="string", 
     *                      example="2023-08-28 06:06:17"
     *                  ),
     *                  @OA\Property(
     *                      property="created_at", 
     *                      type="string", 
     *                      example="2023-08-28 06:06:17"
     *                  ),
     *                  @OA\Property(
     *                      property="items", 
     *                      type="array",
     *                      example={
     *                          {
     *                              "id":1,
     *                              "name":"product 1",
     *                              "price":"10000",
     *                              "updated_at":"2023-08-28 06:06:17",
     *                              "created_at":"2023-08-28 06:06:17"
     *                          } 
     *                      },
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(
     *                              property="id", 
     *                              type="number",
     *                          ),
     *                          @OA\Property(
     *                              property="name", 
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="price", 
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="updated_at", 
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="created_at", 
     *                              type="string",
     *                          ),
     *                      ),
     *                  ),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Item not found",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Data Not Found"
     *              ),
     *          )
     *      )
     * )
     */
    public function remove(OrderService $service, OrderItem $order_item)
    {
        $order = $service->remove($order_item);
        
        if($order) {
            return new OrderResource($order);
        }
        
        return response()->json([
            "message" => "Data not found",
        ], 404);
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/order/{order}",
     *     tags={"Order"},
     *     summary="Delete invoice",
     *     description="Delete a invoice",
     *     operationId="orderDelete",
     *     security={
     *          {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *          description="ID of invoice",
     *          in="path",
     *          name="order",
     *          required=true,
     *          example="1",
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data", 
     *                  type="object",
     *                  @OA\Property(
     *                      property="id", 
     *                      type="number", 
     *                      example=1
     *                  ),
     *                  @OA\Property(
     *                      property="total", 
     *                      type="string", 
     *                      example="10000"
     *                  ),
     *                  @OA\Property(
     *                      property="status", 
     *                      type="string", 
     *                      enum={"unpaid", "paid"},
     *                      example="unpaid"
     *                  ),
     *                  @OA\Property(
     *                      property="updated_at", 
     *                      type="string", 
     *                      example="2023-08-28 06:06:17"
     *                  ),
     *                  @OA\Property(
     *                      property="created_at", 
     *                      type="string", 
     *                      example="2023-08-28 06:06:17"
     *                  ),
     *                  @OA\Property(
     *                      property="items", 
     *                      type="array",
     *                      example={
     *                          {
     *                              "id":1,
     *                              "name":"product 1",
     *                              "price":"10000",
     *                              "updated_at":"2023-08-28 06:06:17",
     *                              "created_at":"2023-08-28 06:06:17"
     *                          } 
     *                      },
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(
     *                              property="id", 
     *                              type="number",
     *                          ),
     *                          @OA\Property(
     *                              property="name", 
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="price", 
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="updated_at", 
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="created_at", 
     *                              type="string",
     *                          ),
     *                      ),
     *                  ),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Invoice not found",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Data Not Found"
     *              ),
     *          )
     *      )
     * )
     */
    public function destroy(Order $order)
    {
        if($order) {
            $order->items()->delete();
            $order->delete();

            return new OrderResource($order);
        }
        
        return response()->json([
            "message" => "Data not found",
        ], 404);
    }
}
