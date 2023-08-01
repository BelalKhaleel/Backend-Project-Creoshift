<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class UserController extends Controller
{
    //Get all users
    public function index() {

        $users = QueryBuilder::for(User::class)->with(['posts', 'comments'])
            ->allowedFilters(['name', 'email', AllowedFilter::exact('id')])
            ->allowedSorts(['name', 'email'])
            ->paginate(100);

        return response(['success' => true, 'data' => $users]);
    }

    //Create User
    public function store(Request $request) {

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8',
        ]);

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        return response(['success' => true, 'data' => $user]);
    }

    //Get user
    public function show(user $user) {
        return response(['success' => true, 'data' => $user->load(['posts', 'comments'])]);
    }

    //Update user credentials
    public function update(Request $request, user $user) {

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id . '|max:255',
            'password' => 'sometimes|string|min:8',
        ]);

        if (request()->has('password'))
            $data['password'] = bcrypt($data['password']);

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
