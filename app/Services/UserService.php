<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(private
        UserRepositoryInterface $userRepo,
        )
    {
    }

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepo->getFiltered($filters, $perPage);
    }

    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $roles = $data['roles'] ?? [];
            unset($data['roles']);

            $data['password'] = Hash::make($data['password']);
            $user = $this->userRepo->create($data);

            if (!empty($roles)) {
                $user->roles()->sync($roles);
            }

            return $user->load('roles');
        });
    }

    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $roles = $data['roles'] ?? null;
            unset($data['roles']);

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }
            else {
                unset($data['password']);
            }

            $updated = $this->userRepo->update($user, $data);

            if ($roles !== null) {
                $updated->roles()->sync($roles);
            }

            return $updated->load('roles');
        });
    }

    public function delete(User $user): bool
    {
        return $this->userRepo->delete($user);
    }

    public function toggleActive(User $user): User
    {
        return $this->userRepo->update($user, ['is_active' => !$user->is_active]);
    }
}
