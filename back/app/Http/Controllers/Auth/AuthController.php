<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\AuthRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AuthRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        $user = User::where('email', request(['email']))->first();

        if (! $user) {
            return response()->json(
                ['errors' => ['login' => [trans('validation.login')]]],
                JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        $token = auth()->claims(['rol' => $user->role()->name])
            ->setTTL(120) // <-- token valid for 2 hours
            ->attempt($credentials);

        if (! $token) {
            return response()->json(['errors' => ['login-form' => [trans('validation.login')]]], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function currentUser()
    {
        $user = auth()->user()->load(['address', 'preference']);

        if ($user->role()->isSuperiorOrEquivalentTo('staff')) {
            $user->load([
                'roles' => function($r) {
                    return $r->orderBy('created_at', 'desc');
                }
            ]);
        }
        return response()->json(UserResource::make($user));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Déconnecté avec succès']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token
        ]);
    }
}
