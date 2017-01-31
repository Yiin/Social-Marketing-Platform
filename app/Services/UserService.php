<?php

namespace App\Services;

use App\User;

class UserService
{
    public function create(array $data)
    {
        $data['password'] = bcrypt($data['password']);
        $data['api_token'] = str_random(32);

        $user = User::create($data);

        return $user;
    }

    public function update(User $user, $data)
    {
        $user->update($data);

        flash('Profile updated!');
    }

    public function changePassword(User $user, $password)
    {
        $user->password = bcrypt($password);
        $user->save();

        flash('Password changed!');
    }
}