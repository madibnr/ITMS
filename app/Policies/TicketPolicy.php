<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Anyone authenticated can view tickets list.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Creator, assignee, or admin/it-staff can view.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->hasAnyRole(['admin', 'it-staff', 'manager'])) {
            return true;
        }

        return $user->id === $ticket->user_id || $user->id === $ticket->assigned_to;
    }

    /**
     * Anyone authenticated can create tickets.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Creator (if Open), assignee, or admin can update.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->hasAnyRole(['admin', 'it-staff'])) {
            return true;
        }

        if ($user->id === $ticket->user_id && $ticket->status === 'Open') {
            return true;
        }

        return $user->id === $ticket->assigned_to;
    }

    /**
     * Only admin can delete.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Admin or IT Staff can assign.
     */
    public function assign(User $user, Ticket $ticket): bool
    {
        return $user->hasAnyRole(['admin', 'it-staff']);
    }

    /**
     * Assignee or admin can resolve.
     */
    public function resolve(User $user, Ticket $ticket): bool
    {
        return $user->hasAnyRole(['admin', 'it-staff']) || $user->id === $ticket->assigned_to;
    }

    /**
     * Admin, IT Staff, or creator can close.
     */
    public function close(User $user, Ticket $ticket): bool
    {
        return $user->hasAnyRole(['admin', 'it-staff']) || $user->id === $ticket->user_id;
    }
}
