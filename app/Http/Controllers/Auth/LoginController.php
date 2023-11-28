<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{



    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
/**
 * @OA\Post(
 *     path="/login",
 *     operationId="authLogin",
 *     tags={"Auth"},
 *     summary="User Login",
 *     description="Login",
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             @OA\MediaType(
 *                 mediaType="multipart/form-data",
 *                 @OA\Schema(
 *                     type="object",
 *                     required={"email", "password"},
 *                     @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *                     @OA\Property(property="password", type="string", format="password", example="password123")
 *                 ),
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login Successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="token", type="string", example="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImI5MzZkYj..."),
 *         )
 *     ),
 *     @OA\Response(response=400, description="Bad request"),
 *     @OA\Response(response=404, description="Resource Not Found"),
 * )
 */

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
