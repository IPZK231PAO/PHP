<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Result;

class ResultPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Result $result): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isManager() || $user->isAdmin();
    }

    public function update(User $user, Result $result): bool
    {
        return $user->isManager() || $user->isAdmin();
    }

    public function delete(User $user, Result $result): bool
    {
        return $user->isAdmin();
    }
}