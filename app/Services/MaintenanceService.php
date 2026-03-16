<?php

namespace App\Services;

use App\Models\MaintenanceSchedule;
use App\Repositories\Contracts\MaintenanceRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class MaintenanceService
{
    public function __construct(private
        MaintenanceRepositoryInterface $maintenanceRepo,
        )
    {
    }

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->maintenanceRepo->getFiltered($filters, $perPage);
    }

    public function create(array $data): MaintenanceSchedule
    {
        $data['status'] = 'Scheduled';
        return $this->maintenanceRepo->create($data);
    }

    public function update(MaintenanceSchedule $schedule, array $data): MaintenanceSchedule
    {
        return $this->maintenanceRepo->update($schedule, $data);
    }

    public function delete(MaintenanceSchedule $schedule): bool
    {
        return $this->maintenanceRepo->delete($schedule);
    }

    public function complete(MaintenanceSchedule $schedule): MaintenanceSchedule
    {
        return $this->maintenanceRepo->update($schedule, [
            'status' => 'Completed',
            'completed_date' => now(),
        ]);
    }

    public function cancel(MaintenanceSchedule $schedule): MaintenanceSchedule
    {
        return $this->maintenanceRepo->update($schedule, ['status' => 'Cancelled']);
    }
}
