<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Lecturer;

class LecturerPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Lecturer $lecturer): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Lecturer $lecturer): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Lecturer $lecturer): bool
    {
        return $user->isAdmin();
    }
}