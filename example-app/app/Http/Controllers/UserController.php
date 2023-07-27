<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //Create User
    public function store(Request $request) {

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        return response(['success' => true, 'data' => $user]);
    }

    //Get all users
    public function index(user $user) {
       $users = User::all();
       return response(['success' => true, 'data' => $users]);
    }

    //Get user
    public function show(user $user) {
        return response(['success' => true, 'data' => $user]);
    }

    //Update user credentials
    public function update(Request $request, user $user) {

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if (request()->has('password'))
            $data['password'] = bcrypt($data['password']);

        $inputs = $request->except('_method');
        $user->update($data);

        return response(['success' => true, 'data' => $user]);
    }

    //Delete user
    public function destroy(user $user)
    {
        $user->delete();
        return response(['data' => $user], Response::HTTP_NO_CONTENT);
    }
}
