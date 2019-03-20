<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use Tymon\JWTAuth\JWTAuth;
use Validator;

class UserController extends Controller
{
    public $successStatus = 200;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(
            [
            'success' => true,
            'access_token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 60]
        );
    }

    /**
     * Register api
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        $this->validate($request, User::$rules);
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        unset($user->password);

        return response()->json(['success' => true, 'data' => $user], 201);
    }

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function logout()
    {
        $this->jwt->parseToken()->invalidate();
        return response()->json(['success' => true, 'message' => "Successfully logged out"], 200);
    }

    /**
     * details api
     *
     * @return UserResource
     */
    public function details()
    {
        return new UserResource(auth()->user());
    }
}
