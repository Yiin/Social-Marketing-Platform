<?php

namespace App\Services;

use App\User;

class UserService
{
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