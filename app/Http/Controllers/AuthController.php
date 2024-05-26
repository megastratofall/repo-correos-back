<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        //validación
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|confirmed',
            'role' => 'required'
        ]);

        //alta de admin
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->save();

        //respuesta
        return response()->json($user, Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if(Auth::attempt($credenciales)){
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            $cookie = cookie('cookie_token', $token, 60*24);
            return response()->json(["token"=>$token], Response::HTTP_OK)->withCookie($cookie);
        }else {
            return response()->json(["message" => "Credenciales inválidas!"], Response::HTTP_UNAUTHORIZED);
        }
    }
    public function choferProfile(Request $request)
    {
        return response()->json([
            "message"=> "choferProfile ok",
            "choferData"=> auth()->user()
        ], Response::HTTP_OK);
    }
    public function logout()
    {   
        $cookie = Cookie::forget('cookie_token');
        return response()->json(["Message"=>"Cierre de sesión exitoso!"], Response::HTTP_OK)->withCookie($cookie);
    }
}
