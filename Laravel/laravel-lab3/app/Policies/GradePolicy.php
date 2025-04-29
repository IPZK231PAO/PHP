<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Grade;

class GradePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Grade $grade): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isManager() || $user->isAdmin();
    }

    public function update(User $user, Grade $grade): bool
    {
        return $user->isManager() || $user->isAdmin();
    }

    public function delete(User $user, Grade $grade): bool
    {
        return $user->isAdmin();
    }
}