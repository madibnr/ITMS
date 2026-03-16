<?php

namespace App\Services;

use App\Models\Incident;
use App\Models\RootCauseAnalysis;
use App\Models\User;
use App\Repositories\Contracts\IncidentRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class IncidentService
{
    public function __construct(private
        IncidentRepositoryInterface $incidentRepo,
        )
    {
    }

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->incidentRepo->getFiltered($filters, $perPage);
    }

    public function create(array $data, User $reporter): Incident
    {
        $data['incident_number'] = Incident::generateIncidentNumber();
        $data['reported_by'] = $reporter->id;
        $data['status'] = 'Open';

        return $this->incidentRepo->create($data);
    }

    public function update(Incident $incident, array $data): Incident
    {
        if (isset($data['status']) && $data['status'] === 'Resolved' && $incident->status !== 'Resolved') {
            $data['resolved_at'] = now();
        }

        return $this->incidentRepo->update($incident, $data);
    }

    public function delete(Incident $incident): bool
    {
        return $this->incidentRepo->delete($incident);
    }

    public function resolve(Incident $incident, string $resolution): Incident
    {
        return $this->incidentRepo->update($incident, [
            'status' => 'Resolved',
            'resolution' => $resolution,
            'resolved_at' => now(),
        ]);
    }

    public function createRca(Incident $incident, array $data, User $analyst): RootCauseAnalysis
    {
        return DB::transaction(function () use ($incident, $data, $analyst) {
            return $incident->rootCauseAnalysis()->create([
                ...$data,
                'analyzed_by' => $analyst->id,
                'status' => 'Draft',
            ]);
        });
    }
}
