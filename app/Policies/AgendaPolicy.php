<?php

namespace App\Policies;

use App\Models\Agenda;
use App\Models\User;

class AgendaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Agenda $agenda): bool
    {
        return $agenda->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Agenda $agenda): bool
    {
        return $agenda->user_id === $user->id;
    }

    public function delete(User $user, Agenda $agenda): bool
    {
        return $agenda->user_id === $user->id;
    }

    public function restore(User $user, Agenda $agenda): bool
    {
        return false;
    }

    public function forceDelete(User $user, Agenda $agenda): bool
    {
        return false;
    }
}
