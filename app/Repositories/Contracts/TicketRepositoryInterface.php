<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

interface TicketRepositoryInterface extends BaseRepositoryInterface
{
    public function getFiltered(array $filters, int $perPage = 15, ?Builder $baseQuery = null): LengthAwarePaginator;
    public function getActive(array $filters, int $perPage = 15): LengthAwarePaginator;
    public function getHistory(array $filters, int $perPage = 15): LengthAwarePaginator;
}
