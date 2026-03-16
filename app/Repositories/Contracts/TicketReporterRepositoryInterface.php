<?php

namespace App\Repositories\Contracts;

interface TicketReporterRepositoryInterface extends BaseRepositoryInterface
{
    public function findByNikAndEmail(string $nik, string $email);
    public function findOrCreateByData(array $data);
}
