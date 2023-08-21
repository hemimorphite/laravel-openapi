<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Resources\UserResource;
use App\Http\Services\UserService;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class UserController extends Controller
{   
    /**
     * @OA\Post (
     *      path="/api/v1/user/login",
     *      tags={"Auth"},
     *      summary="Login",
     *      description="User login",
     *      operationId="login",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "email":"john@gmail.com",
     *                     "password":"helloworld"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Valid credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="number", example=1),
     *                  @OA\Property(property="name", type="string", example="John"),
     *                  @OA\Property(property="email", type="string", example="john@test.com"),
     *                  @OA\Property(property="email_verified_at", type="string", example=null),
     *                  @OA\Property(property="api_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNTVmNjA1NDNmMGI1MDg3MGFlNWY5ZWQ1ZTMzYWIwNzhiZTQ1NjRmYzliMmMyMjQwZjNjNDFlZWQ4ODhiZDcxMTg3NWJiM2E0NWNhY2IxMmMiLCJpYXQiOjE2OTI1NDg3MzAuODU3OTE4LCJuYmYiOjE2OTI1NDg3MzAuODU3OTIzLCJleHAiOjE3MjQxNzExMzAuNzczNTQ5LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.JBAMuBjIqP78z_6BzubosjNfk0O5TCR1Ro73y6wSJPODLbMyxsQWFJmqroSHVYzIHpfC9ikLIUz4ywKCrsuqyOgWzhnaQH8W-A5eqnfbp-Vrevq9QzS2cGVTGySYa_-YxP3sy8fgEfMApDKzmSDJZCy-Q61aYRYjtp3v52Nj6iAd_mvrFQ8TeVq-Vbor2yTXGZnnd7SHTJxzR4eDCmg3RvhI-4vKDGF4LGRt8Q5bQOK0tIY8bvsEpsXDVlkNlSYEu0txOFYkYjNqaxnBDV9BTF3Wki6Ui6qc5BP9EwvtGugerygdT0LDQoblWHXx9gxKBB4-Y-CN4xYa4rvTIeGWEioqPHpywINtWmNWVJ71gheva8aBIc0sxNU3i2xa6dT5JF_rEorHXsUPkyXjGQzxMUfTP9pmtKlbq1eW-AJuS23S_7p1g-CNYMvx2hMtNeDSKYCQE-RZoayCEiLJS10ldWZcF-iMgEm-ZcTG8lzjjfYdqRAMS7FyAM6f1xmAvhgnSDPW-nZnbGo5AUueT3Zz3WrvU6w4BdtiwVLdz15bEmJ3ZkT2x6GvJIqx2DQNhBO9x6o6kVrujcdtc_JDF9tkPNsu171tiV6Uk8kV_r15jyO7taSugT4uqS1ITdd4II4oAKL0JXRd4pSVsY8Fbr0p-HOdYmebLHsnUGRmMiDiZBA"),
     *                  @OA\Property(property="updated_at", type="string", example="2023-08-28 06:06:17"),
     *                  @OA\Property(property="created_at", type="string", example="2023-08-28 06:06:17"),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Invalid credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Incorrect username or password!"),
     *          )
     *      )
     * )
     */
    public function login(Request $request)
    {
        $login = Auth::Attempt($request->all());
        if ($login) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->accessToken;
            $user->api_token = $token;
            $user->save();

            return new UserResource($user);
        }else {
            
            return response()->json([
                'message' => 'Username and Password did not match'
            ], 401);
        }
    }

    /**
     * Register
     * @OA\Post (
     *     path="/api/v1/user/register",
     *     tags={"Auth"},
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
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"John",
     *                     "email":"john@gmail.com",
     *                     "password":"helloworld"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="number", example=1),
     *                  @OA\Property(property="name", type="string", example="John"),
     *                  @OA\Property(property="email", type="string", example="john@test.com"),
     *                  @OA\Property(property="email_verified_at", type="string", example=null),
     *                  @OA\Property(property="api_token", type="string", example=null),
     *                  @OA\Property(property="updated_at", type="string", example="2023-08-28 06:06:17"),
     *                  @OA\Property(property="created_at", type="string", example="2023-08-28 06:06:17"),
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
    public function register(RegisterRequest $request, UserService $service)
    {
        $user = $service->store($request->validated());

        return new UserResource($user);
    }


    /**
     * Logout
     * @OA\Post (
     *      path="/api/v1/user/logout",
     *      tags={"Auth"},
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\Response(
     *          response=201,
     *          description="Success",
     *      ),
     * )
     */
    public function logout()
    {
        //$user = Auth::user();
        //$user->api_token = null;
        //$user->save();
        //Session::flush();
        //Auth::logout();
        
        return response()->json([
                
        ], 201);
    }
}
