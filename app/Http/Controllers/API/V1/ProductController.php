<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Http\Requests\ProductRequest;
use App\Http\Services\ProductService;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * @OA\Get (
     *      path="/api/v1/products",
     *      tags={"Product"},
     *      summary="Paginated Product list",
     *      description="Paginated list of products",
     *      operationId="productList",
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
     *                          property="name", 
     *                          type="string", 
     *                          example="product 1"
     *                      ),
     *                      @OA\Property(
     *                          property="price", 
     *                          type="string", 
     *                          example="20000"
     *                      ),
     *                      @OA\Property(
     *                          property="updated_at", 
     *                          type="string", 
     *                          example="2023-08-28 06:06:17"
     *                      ),
     *                      @OA\Property(
     *                          property="created_at", 
     *                          type="string", 
     *                          example="2023-08-28 06:06:17"
     *                      ),
     *                      @OA\Property(
     *                          property="categories", 
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
     *                                  example="category 1"
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
        $products = Product::with('categories')->orderBy('name', 'asc')->paginate(5);

        return ProductResource::collection($products);
    }

    /**
     * @OA\Post (
     *     path="/api/v1/product",
     *     tags={"Product"},
     *     summary="Create Product",
     *     description="Create a product record",
     *     operationId="productCreate",
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
     *                      @OA\Property(
     *                          property="categories",
     *                          type="array",
     *                          @OA\Items(
     *                              type="object",
     *                              @OA\Property(
     *                                  property="id",
     *                                  type="number"
     *                              ),
     *                          ),
     *                      )
     *                 ),
     *                 example={
     *                     "name":"Product 1",
     *                     "price":"10000",
     *                     "categories":{
     *                          {
     *                              "id": 1
     *                          },
     *                          {
     *                              "id": 2
     *                          },
     *                      },
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
     *                      property="name", 
     *                      type="string", 
     *                      example="Product 1"
     *                  ),
     *                  @OA\Property(
     *                      property="price", 
     *                      type="string", 
     *                      example="10000"
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
     *                      property="categories", 
     *                      type="array",
     *                      example={
     *                          {
     *                              "id":1
     *                          },
     *                          {
     *                              "id":2
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
    public function store(ProductRequest $request, ProductService $service)
    {
        $product = $service->store($request->validated());

        return new ProductResource($product);
    }

    /**
     * @OA\Get (
     *     path="/api/v1/product/{product}",
     *     tags={"Product"},
     *     summary="View product",
     *     description="Show information of a product",
     *     operationId="productShow",
     *     security={
     *          {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *          description="ID of product",
     *          in="path",
     *          name="product",
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
     *                      property="name", 
     *                      type="string", 
     *                      example="Product 1"
     *                  ),
     *                  @OA\Property(
     *                      property="price", 
     *                      type="string", 
     *                      example="10000"
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
     *                      property="categories", 
     *                      type="array",
     *                      example={
     *                          {
     *                              "id":1,
     *                              "name":"category 1",
     *                              "updated_at":"2023-08-28 06:06:17",
     *                              "created_at":"2023-08-28 06:06:17"
     *                          },
     *                          {
     *                              "id":2,
     *                              "name":"category 2",
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
     *          description="Product not found",
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
    public function show(Product $product)
    {
        $product = Product::with('categories')->find($product->id);

        if($product) {
            return new ProductResource($product);
        }

        return response()->json([
            "message" => "Data not found",
        ], 404);
        
    }
    
    /**
     * @OA\Patch (
     *     path="/api/v1/product/{product}",
     *     tags={"Product"},
     *     summary="Update Product",
     *     description="Update a product record",
     *     operationId="productUpdate",
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
     *                          property="id",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="price",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="categories",
     *                          type="array",
     *                          @OA\Items(
     *                              type="object",
     *                              @OA\Property(
     *                                  property="id",
     *                                  type="number"
     *                              ),
     *                          ),
     *                      )
     *                 ),
     *                 example={
     *                     "id": 1,
     *                     "name":"Product 1",
     *                     "price":"10000",
     *                     "categories":{
     *                          {
     *                              "id": 1
     *                          },
     *                          {
     *                              "id": 2
     *                          },
     *                      },
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
     *                      property="name", 
     *                      type="string", 
     *                      example="Product 1"
     *                  ),
     *                  @OA\Property(
     *                      property="price", 
     *                      type="string", 
     *                      example="10000"
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
     *                      property="categories", 
     *                      type="array",
     *                      example={
     *                          {
     *                              "id":1,
     *                              "name":"category 1",
     *                              "updated_at":"2023-08-28 06:06:17",
     *                              "created_at":"2023-08-28 06:06:17"
     *                          },
     *                          {
     *                              "id":2,
     *                              "name":"category 2",
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
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Product not found",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Data not found",
     *              ),
     *          )
     *      )
     * )
     */
    public function update(ProductRequest $request, ProductService $service, Product $product)
    {
        if($product) {
            $product = $service->update($request->validated(), $product);

            return new ProductResource($product);
        }
        
        return response()->json([
            "message" => "Data not found",
        ], 404);
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/product/{product}",
     *     tags={"Product"},
     *     summary="Delete Product",
     *     description="Delete a product record",
     *     operationId="productDelete",
     *     security={
     *          {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *          description="ID of product",
     *          in="path",
     *          name="product",
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
     *                      property="name", 
     *                      type="string", 
     *                      example="Product 1"
     *                  ),
     *                  @OA\Property(
     *                      property="price", 
     *                      type="string", 
     *                      example="10000"
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
     *                      property="categories", 
     *                      type="array",
     *                      example={
     *                          {
     *                              "id":1,
     *                              "name":"category 1",
     *                              "updated_at":"2023-08-28 06:06:17",
     *                              "created_at":"2023-08-28 06:06:17"
     *                          },
     *                          {
     *                              "id":2,
     *                              "name":"category 2",
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
     *          description="Product not found",
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
    public function destroy(Product $product)
    {

        if($product) {
            $product->categories()->detach();
            $product->delete();

            return new ProductResource($product);
        }
        
        return response()->json([
            "message" => "Data not found",
        ], 404);
    }
}
