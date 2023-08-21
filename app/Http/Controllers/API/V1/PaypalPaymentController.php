<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalPaymentController extends Controller
{
    /**
     * @OA\Post (
     *     path="/api/v1/order/unpaid",
     *     tags={"Order"},
     *     summary="Checkout payment",
     *     description="Checkout payment of a unpaid invoice",
     *     operationId="orderCheckout",
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
     *                      example="30000"
     *                  ),
     *                  @OA\Property(
     *                      property="status", 
     *                      type="string",
     *                      enum={"unpaid", "paid"},
     *                      example="paid"
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
     *                          },
     *                          {
     *                              "id":2,
     *                              "name":"product 2",
     *                              "price":"20000",
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
    public function create(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);
        $order = $provider->createOrder([
            "intent"=> "CAPTURE",
            "purchase_units"=> [
                 [
                    "amount"=> [
                        "currency_code"=> "USD",
                        "value"=> "1000"
                    ],
                     'description' => 'test'
                ]
            ],
        ]);
        
        $order = Order::with('items')->where('user_id', Auth::user()->id)->where('status', 'unpaid')->first();

        $order->update('status', 'paid');

        return new OrderResource($order);
    }

    public function capture(Request $request)
    {
        /*
        $this->paypalClient->setApiCredentials(config('paypal'));
        $token = $this->paypalClient->getAccessToken();
        $this->paypalClient->setAccessToken($token);
        $result = $this->paypalClient->capturePaymentOrder($orderId);
        */
        return;
    }
}
