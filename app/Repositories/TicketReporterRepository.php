<?php

namespace App\Repositories;

use App\Models\TicketReporter;
use App\Repositories\Contracts\TicketReporterRepositoryInterface;

class TicketReporterRepository extends BaseRepository implements TicketReporterRepositoryInterface
{
    public function __construct(TicketReporter $model)
    {
        parent::__construct($model);
    }

    public function findByNikAndEmail(string $nik, string $email): ?TicketReporter
    {
        return $this->model->where('nik', $nik)->where('email', $email)->first();
    }

    public function findOrCreateByData(array $data): TicketReporter
    {
        return TicketReporter::findOrCreateByNik($data);
    }
}
