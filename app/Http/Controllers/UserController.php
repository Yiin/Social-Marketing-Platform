<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UpdateUser;
use App\Http\Requests\ChangeUserPassword;
use App\Services\UserService;

use App\User;

class UserController extends Controller
{
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, User $user)
    {
        $this->userService->update($user, $request->only([
            'name',
            'email'
        ]));

        return redirect()->back();
    }

    public function changePassword(ChangeUserPassword $request, User $user)
    {
        $this->userService->changePassword($user, $request->get('new_password'));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \APp\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
