<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SessionController extends Controller
{
     /**
     * Handle an authentication attempt.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            $token = $user->createToken("API TOKEN")->plainTextToken;
            $request->session()->put('api_token', $token);

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

     //Login the User
    //  public function loginUser(Request $request) {

    //     $data = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|string|min:8',
    //     ]);

    //     $user = User::where('email', $data['email'])->first();

    //     if(!$user || !Auth::attempt($request->only(['email', 'password']))) {
    //         return response([
    //             'success' => false,
    //             'message' => 'The provided credentials do not match our records.',
    //         ]);
    //     }

    //     $token = $user->createToken("API TOKEN")->plainTextToken;

    //     return response([
    //         'success' => true,
    //         'message' => 'User logged in successfully',
    //         'data' => $user,
    //         'token' => $token
    //     ]);
    // }

    /**
    * Log the user out of the application.
    */
    public function logout(Request $request): RedirectResponse
{
    Auth::logout();
 
    $request->session()->invalidate();
 
    $request->session()->regenerateToken();
 
    return redirect('/');
}
}
