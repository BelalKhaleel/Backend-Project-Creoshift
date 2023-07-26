<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function addUser(Request $request) {

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }

        $user = new User;
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->save();

        return response()->json([
            'message' => 'User created successfully!',
            'user' => $user,
        ]);
    }

    public function getUser(Request $request, $id) {

        $validator = Validator::make(['id' => $id], ['id' => 'required|numeric',]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid user ID format',
            ], 400);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'error' => 'User not found!',
            ], 404);
        }

        return response()->json([
            'message' => 'Retrieved user successfully!',
            'user' => $user,
        ]);
    }

    public function editUser(Request $request, $id) {

        $validator = Validator::make(['id' => $id], ['id' => 'required|numeric',]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid user ID format',
            ], 400);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'error' => 'User not found!',
            ], 404);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }

        $inputs = $request->except('_method');
        $user->update($inputs);

        return response()->json([
            'message' => 'Edited user successfully!',
            'user' => $user,
        ]);
    }

    public function deleteUser(Request $request, $id) {

        $validator = Validator::make(['id' => $id], ['id' => 'required|numeric',]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid user ID format',
            ], 400);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'error' => 'User not found!',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'Deleted user successfully!',
            'user' => $user,
        ]);
    }
}
