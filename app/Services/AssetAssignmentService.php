<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\AssetAssignment;
use App\Models\AssetLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AssetAssignmentService
{
    /**
     * Assign an asset to a user.
     */
    public function assign(Asset $asset, array $data, User $performer): AssetAssignment
    {
        return DB::transaction(function () use ($asset, $data, $performer) {
            // Create the assignment record
            $assignment = AssetAssignment::create([
                'asset_id' => $asset->id,
                'assigned_to_user_id' => $data['assigned_to_user_id'],
                'assigned_by' => $performer->id,
                'assigned_date' => $data['assigned_date'] ?? now()->toDateString(),
                'expected_return_date' => $data['expected_return_date'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            // Update asset's assigned_user_id
            $asset->update([
                'assigned_user_id' => $data['assigned_to_user_id'],
                'status' => Asset::STATUS_ACTIVE,
            ]);

            // Log the action
            AssetLog::create([
                'asset_id' => $asset->id,
                'action' => 'Assigned',
                'user_id' => $performer->id,
                'changes' => [
                    'assigned_to' => $data['assigned_to_user_id'],
                    'assigned_date' => $assignment->assigned_date->toDateString(),
                ],
            ]);

            return $assignment;
        });
    }

    /**
     * Return an asset (mark assignment as returned).
     */
    public function returnAsset(Asset $asset, User $performer, ?string $notes = null): AssetAssignment
    {
        return DB::transaction(function () use ($asset, $performer, $notes) {
            // Find the active assignment
            $assignment = AssetAssignment::where('asset_id', $asset->id)
                ->active()
                ->latest('assigned_date')
                ->firstOrFail();

            $assignment->update([
                'returned_date' => now()->toDateString(),
                'notes' => $notes ?? $assignment->notes,
            ]);

            // Clear asset assignment
            $asset->update([
                'assigned_user_id' => null,
                'status' => Asset::STATUS_IN_STOCK,
            ]);

            // Log the action
            AssetLog::create([
                'asset_id' => $asset->id,
                'action' => 'Returned',
                'user_id' => $performer->id,
                'changes' => [
                    'returned_from' => $assignment->assigned_to_user_id,
                    'returned_date' => now()->toDateString(),
                ],
            ]);

            return $assignment;
        });
    }
}
