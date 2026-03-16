<?php

namespace App\Services;

use App\Models\ChangeRequest;
use App\Models\User;
use App\Repositories\Contracts\ChangeRequestRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ChangeRequestService
{
    public function __construct(private
        ChangeRequestRepositoryInterface $changeRequestRepo,
        )
    {
    }

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->changeRequestRepo->getFiltered($filters, $perPage);
    }

    public function create(array $data, User $requester): ChangeRequest
    {
        $data['change_number'] = ChangeRequest::generateChangeNumber();
        $data['requested_by'] = $requester->id;
        $data['status'] = 'Draft';

        return $this->changeRequestRepo->create($data);
    }

    public function update(ChangeRequest $changeRequest, array $data): ChangeRequest
    {
        return $this->changeRequestRepo->update($changeRequest, $data);
    }

    public function delete(ChangeRequest $changeRequest): bool
    {
        return $this->changeRequestRepo->delete($changeRequest);
    }

    public function submit(ChangeRequest $changeRequest): ChangeRequest
    {
        return $this->changeRequestRepo->update($changeRequest, ['status' => 'Submitted']);
    }

    public function approve(ChangeRequest $changeRequest, User $approver): ChangeRequest
    {
        return $this->changeRequestRepo->update($changeRequest, [
            'status' => 'Approved',
            'approved_by' => $approver->id,
        ]);
    }

    public function reject(ChangeRequest $changeRequest, User $approver): ChangeRequest
    {
        return $this->changeRequestRepo->update($changeRequest, [
            'status' => 'Rejected',
            'approved_by' => $approver->id,
        ]);
    }

    public function implement(ChangeRequest $changeRequest): ChangeRequest
    {
        return $this->changeRequestRepo->update($changeRequest, [
            'status' => 'Implemented',
            'implemented_at' => now(),
        ]);
    }
}
