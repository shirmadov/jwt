<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     description="Получить токен",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *          description="Данные для входа",
     *          required=true,
     *       @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              @OA\Property(property="email", type="string", example="test@example.com"),
     *              @OA\Property(property="password", type="string", example="password", format="password"),
     *          )
     *        ),
     *     ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="access_token",type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3MjgxNDAzODgsImV4cCI6MTcyODE0Mzk4OCwibmJmIjoxNzI4MTQwMzg4LCJqdGkiOiJJblNwYWZiVUo2dVowQkZ3Iiwic3ViIjoiMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.UA_JEhSlzVo1EE1t9t0fFwA_6ZibyWEY5fEdUllKGJI"),
     *               @OA\Property(property="token_type",type="string", example="bearer"),
     *               @OA\Property(property="expires_in",type="integer", example=3600),
     *           )
     *     )
     * )
     *
     *
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if(!$token = auth()->attempt($credentials)){
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/me",
     *     description="Получить ифно",
     *     tags={"Auth"},
     *     security={ {"bearer_token": {} }},
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="id",type="integer", example="1"),
     *               @OA\Property(property="name",type="string", example="Test User"),
     *               @OA\Property(property="email",type="string", example="test@example.com"),
     *               @OA\Property(property="email_verified_at",type="string", example="2024-10-05T13:51:19.000000Z"),
     *               @OA\Property(property="created_at",type="string", example="2024-10-05T13:51:19.000000Z"),
     *               @OA\Property(property="updated_at",type="string", example="2024-10-05T13:51:19.000000Z"),
     *           )
     *     )
     * )
     *
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     description="Выйти",
     *     tags={"Auth"},
     *     security={ {"bearer_token": {} }},
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="message",type="string", example="Successfully logged out"),
     *           )
     *     )
     * )
     *
     */
    public function logout()
    {
        auth()->logout(true);
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/refresh",
     *     description="Обновить",
     *     tags={"Auth"},
     *     security={ {"bearer_token": {} }},
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *         @OA\JsonContent(
     *                type="object",
     *                @OA\Property(property="access_token",type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3MjgxNDAzODgsImV4cCI6MTcyODE0Mzk4OCwibmJmIjoxNzI4MTQwMzg4LCJqdGkiOiJJblNwYWZiVUo2dVowQkZ3Iiwic3ViIjoiMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.UA_JEhSlzVo1EE1t9t0fFwA_6ZibyWEY5fEdUllKGJI"),
     *                @OA\Property(property="token_type",type="string", example="bearer"),
     *                @OA\Property(property="expires_in",type="integer", example=3600),
     *            )
     *     )
     * )
     *
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh(true, true));
    }

    protected function respondWithToken($token)
    {
        return response()->json([
          'access_token' => "Bearer $token",
          'token_type' => 'bearer',
          'expires_in' => auth()->factory()->getTTL()*60
        ]);
    }
}
