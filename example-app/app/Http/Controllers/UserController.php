<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //Get all users
    public function index(Request $request) {

        $users = QueryBuilder::for(User::class)
            ->with(['posts', 'comments'])
            ->allowedFilters(['name', 'email', AllowedFilter::exact('id')])
            ->defaultSort('-updated_at')
            ->allowedSorts(['name', 'email', '-updated_at'])
            ->paginate($request->input('per_page', 100))
            ->appends($request->query());

        return response(['success' => true, 'data' => $users]);
    }

    //Create User
    public function store(Request $request) {

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8',
            'role' => 'nullable|string',
        ]);

        $data['password'] = bcrypt($data['password']);

        $role = empty($data['role']) ? 'visitor' : $data['role'];

        $user = User::create($data);

        $user->assignRole($role);

        return response([
            'success' => true, 
            'data' => $user,
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ]);
    }

    //Login the User
    public function loginUser(Request $request) {

        $data = $request->validate([
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8',
        ]);

        $data['password'] = bcrypt($data['password']);

        if(!Auth::attempt($request->only(['email', 'password']))) {
            return response([
                'success' => false,
                'message' => 'The provided credentials do not match our records.',
            ]);
        }

        $user = User::where('email', $request->email)->first();

        return response([
            'success' => true,
            'message' => 'User logged in successfully',
            'data' => $user,
        ]);
    }

    //Get user
    public function show(user $user) {
        return response(['success' => true, 'data' => $user->load(['posts', 'comments'])]);
    }

    //Update user credentials
    public function update(Request $request, user $user) {

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id . '|max:255',
            'password' => 'required|string|min:8',
            'role' => 'nullable|string',
        ]);

        if (request()->has('password'))
            $data['password'] = bcrypt($data['password']);

            $role = empty($data['role']) ? 'visitor' : $data['role'];

        $user->update($data);

        $user->assignRole($role);

        return response(['success' => true, 'data' => $user]);
    }

    //Delete user
    public function destroy(user $user)
    {
        $user->delete();
        return response(['data' => $user], Response::HTTP_NO_CONTENT);
    }
}
