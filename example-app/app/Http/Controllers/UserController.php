<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Imports\UsersImport;

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

    //Export users' database to an excel file
    public function exportUsers() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    //Import
    public function importUsers() 
    {
        Excel::import(new UsersImport, request()->file('file'));
            
        return back();
    }
}
