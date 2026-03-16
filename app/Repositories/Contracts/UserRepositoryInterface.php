<?php

namespace App\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function getFiltered(array $filters, int $perPage = 15): LengthAwarePaginator;
}
