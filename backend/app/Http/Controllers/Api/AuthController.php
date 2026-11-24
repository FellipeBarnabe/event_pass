<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:120'],
            'email'    => ['required', 'email', 'max:160', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'cpf'      => ['nullable', 'digits:11'],
            'phone'    => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create($data);
        $user->refresh(); // carrega o 'role' padrão que o banco definiu

        return response()->json([
            'user'  => $user,
            'token' => $user->createToken('web')->plainTextToken,
        ], 201);
    }


    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $data['email'])->first();

        // Mesma mensagem para "e-mail não existe" e "senha errada":
        // não revela quais e-mails estão cadastrados.
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'E-mail ou senha incorretos.'], 401);
        }

        return response()->json([
            'user'  => $user,
            'token' => $user->createToken('web')->plainTextToken,
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sessão encerrada.']);
    }

}