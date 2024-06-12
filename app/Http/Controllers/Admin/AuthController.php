<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.login');
    }

    /**
     * @param LoginRequest $request
     */
    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json($request->validator->messages(), Response::HTTP_BAD_REQUEST);
        }

        if (
            auth()->attempt([
                'login' => $request->get('login'),
                'password' =>  $request->get('password'),
                'status' => $request->get('status'),
            ])
        ) {
            return response()->json(["message" => "Login success"]);
        }

        return response()->json(['login' => ['Incorrect username or password.']], Response::HTTP_BAD_REQUEST);
    }
}
