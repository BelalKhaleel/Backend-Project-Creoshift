<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function store(Request $request) {

        $request->validate([
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

    public function show(Request $request, $id) {

        $request->validate([
            'id' => $id], ['id' => 'required|numeric',
        ]);
     
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'error' => 'User not found!',
            ], 404);
        }

        return response(['success' => true, 'data' => $user]);
    }

    public function edit(Request $request, $id) {

        $request->validate([
            'id' => $id], ['id' => 'required|numeric',
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'error' => 'User not found!',
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8',
        ]);

        $inputs = $request->except('_method');
        $user->update($inputs);

        return response(['success' => true, 'data' => $user]);
    }

    public function destroy(Request $request, $id) {

        $request->validate([
            'id' => $id], ['id' => 'required|numeric',
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'error' => 'User not found!',
            ], 404);
        }

        $user->delete();

        return response(['success' => true, 'data' => $user]);
    } 
}
