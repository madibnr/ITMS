<?php

namespace App\Repositories;

use App\Models\Ticket;
use App\Repositories\Contracts\TicketRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class TicketRepository extends BaseRepository implements TicketRepositoryInterface
{
    public function __construct(Ticket $model)
    {
        parent::__construct($model);
    }

    public function getFiltered(array $filters, int $perPage = 15, ?Builder $baseQuery = null): LengthAwarePaginator
    {
        $query = $baseQuery ?? $this->model->newQuery();

        return $query
            ->with(['creator', 'assignee', 'category'])
            ->when($filters['status'] ?? null, fn(Builder $q, $v) => $q->where('status', $v))
            ->when($filters['priority'] ?? null, fn(Builder $q, $v) => $q->where('priority', $v))
            ->when($filters['category_id'] ?? null, fn(Builder $q, $v) => $q->where('category_id', $v))
            ->when($filters['assigned_to'] ?? null, fn(Builder $q, $v) => $q->where('assigned_to', $v))
            ->when($filters['user_id'] ?? null, fn(Builder $q, $v) => $q->where('user_id', $v))
            ->when($filters['date_from'] ?? null, fn(Builder $q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($filters['date_to'] ?? null, fn(Builder $q, $v) => $q->whereDate('created_at', '<=', $v))
            ->when($filters['search'] ?? null, fn(Builder $q, $v) => $q->where(function ($q) use ($v) {
            $q->where('title', 'like', "%{$v}%")
                ->orWhere('ticket_number', 'like', "%{$v}%");
        }
        ))
            ->latest()
            ->paginate($perPage);
    }

    public function getActive(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->getFiltered($filters, $perPage, Ticket::query()->active());
    }

    public function getHistory(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->getFiltered($filters, $perPage, Ticket::query()->history());
    }
}
