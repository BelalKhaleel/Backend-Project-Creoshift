<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function addUser(Request $request) {
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
        $user = User::find($id);

        return response()->json([
            'message' => 'Retrieved user successfully!',
            'user' => $user,
        ]);
    }

    public function editUser(Request $request, $id) {
        $user = User::find($id);
        $inputs = $request->except('_method');
        $user->update($inputs);

        return response()->json([
            'message' => 'Edited user successfully!',
            'user' => $user,
        ]);
    }

    public function deleteUser(Request $request, $id) {
        $user = User::find($id);
        $user->delete();

        return response()->json([
            'message' => 'Deleted user successfully!',
            'user' => $user,
        ]);
    }
}
