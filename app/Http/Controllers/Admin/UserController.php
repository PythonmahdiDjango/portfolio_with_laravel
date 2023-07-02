<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderByDesc('id')->get();

        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $user = $this->userService->store($request->validated());

        session()->flash('success', 'User has created successfully!');

        return redirect(route('admin.user.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.user.show', [
            'user' => $user->load(['profile', 'image', 'comments', 'blogs'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.user.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $this->userService->update($user, $request->validated());

        session()->flash(
            'success', 'User has updated successfully!'
        );

        return redirect(route('admin.user.show', $user->id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->userService->delete($user);

        session()->flash(
            'success', 'User has deleted successfully!'
        );
    }
}
