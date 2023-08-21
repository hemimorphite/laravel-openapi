<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\CategoryRequest;
use App\Http\Services\CategoryService;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * @OA\Get (
     *      path="/api/v1/categories/all",
     *      tags={"Category"},
     *      summary="all categories",
     *      description="list of all categories",
     *      operationId="allCategories",
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
     *                          example="category 1"
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
     *                  ),
     *              ),
     *          )
     *      ),
     * )
     */
    public function all()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        
        return CategoryResource::collection($categories);
    }

    /**
     * @OA\Get (
     *      path="/api/v1/categories",
     *      tags={"Category"},
     *      summary="Paginated category list",
     *      description="Paginated list of categories",
     *      operationId="categoryList",
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
     *                          example="category 1"
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
     *                  ),
     *              ),
     *          )
     *      ),
     * )
     */
    public function index()
    {
        $categories = Category::orderBy('name', 'asc')->paginate(5);
        
        return CategoryResource::collection($categories);
    }

    /**
     * @OA\Post (
     *     path="/api/v1/category",
     *     tags={"Category"},
     *     summary="Create Category",
     *     description="Create a category record",
     *     operationId="categoryCreate",
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
     *                 ),
     *                 example={
     *                     "name":"category 1"
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
     *                      property="updated_at", 
     *                      type="string", 
     *                      example="2023-08-28 06:06:17"
     *                  ),
     *                  @OA\Property(
     *                      property="created_at", 
     *                      type="string", 
     *                      example="2023-08-28 06:06:17"
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
    public function store(CategoryRequest $request, CategoryService $service)
    {
        $category = $service->store($request->validated());

        return new CategoryResource($category);
    }

    /**
     * @OA\Get (
     *     path="/api/v1/category/{category}",
     *     tags={"Category"},
     *     summary="View a category",
     *     description="View a category",
     *     operationId="categoryShow",
     *     security={
     *          {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *          description="ID of category",
     *          in="path",
     *          name="category",
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
     *                      property="updated_at", 
     *                      type="string", 
     *                      example="2023-08-28 06:06:17"
     *                  ),
     *                  @OA\Property(
     *                      property="created_at", 
     *                      type="string", 
     *                      example="2023-08-28 06:06:17"
     *                  ),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Category not found",
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
    public function show(Category $category)
    {
        if($category) {
            return new CategoryResource($category);
        }

        return response()->json([
            "message" => "Data not found",
        ], 404);
        
    }

    /**
     * @OA\Patch (
     *     path="/api/v1/category/{category}",
     *     tags={"Category"},
     *     summary="Update Category",
     *     description="Update a category record",
     *     operationId="categoryUpdate",
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
     *                 ),
     *                 example={
     *                     "id": 1,
     *                     "name":"category 1"
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
     *                      property="updated_at", 
     *                      type="string", 
     *                      example="2023-08-28 06:06:17"
     *                  ),
     *                  @OA\Property(
     *                      property="created_at", 
     *                      type="string", 
     *                      example="2023-08-28 06:06:17"
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
     *          description="Category not found",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Data not found"
     *              ),
     *          )
     *      )
     * )
     */
    public function update(CategoryRequest $request, CategoryService $service, Category $category)
    {
        if($category) {
            $category = $service->update($request->validated(), $category);

            return new CategoryResource($category);
        }
        
        return response()->json([
            "message" => "Data not found",
        ], 404);
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/category/{category}",
     *     tags={"Category"},
     *     summary="Delete Category",
     *     description="Delete a category record",
     *     operationId="categoryDelete",
     *     security={
     *          {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *          description="ID of category",
     *          in="path",
     *          name="category",
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
     *                      example="category 1"
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
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Category not found",
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
    public function destroy(Category $category)
    {
        if($category) {
            if(!$category->products()->exists()) {
                $category->delete();

                return new CategoryResource($category);
            }
            
            return response()->json([
                "message" => "Forbidden",
            ], 403);
        }
        
        return response()->json([
            "message" => "Data not found",
        ], 404);
    }
}
