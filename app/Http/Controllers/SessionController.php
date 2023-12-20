<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use App\Models\User;

class SessionController extends Controller
{
     /**
     * Handle an authentication attempt.
     */

    // Login the User
     public function store(Request $request) {

        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('email', $data['email'])->first();
        
        if(!$user || !Auth::attempt($data)) {
            throw ValidationException::withMessages([
               'email' => 'Your provided credentials could not be verified.'
           ]);
        }

        $token = $user->createToken("API TOKEN");

        return response([
            'success' => true,
            'message' => 'User logged in successfully',
            'data' => $user,
            'token' => $token->plainTextToken
        ]);
    }

    /**
    * Log the user out of the application.
    */
    public function destroy(Request $request)
{
    $request->user()->tokens()->delete();

    return response('Logged out!', ResponseAlias::HTTP_FORBIDDEN);
}
}
