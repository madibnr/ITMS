<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\AssetLog;
use App\Models\AssetMaintenance;
use App\Models\User;
use App\Repositories\Contracts\AssetRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIo\QrCode\Facades\QrCode;

class AssetService
{
    private const CATEGORY_PREFIX_MAP = [
        'PC' => 'PC',
        'Desktop' => 'DT',
        'Laptop' => 'LPT',
        'Printer' => 'PRT',
        'Switch' => 'SW',
        'AP' => 'AP',
        'Router' => 'RTR',
        'Server' => 'SVR',
        'CCTV' => 'CTV',
        'Network Device' => 'NET',
        'Access Control' => 'ACC',
        'Software License' => 'SFT',
    ];

    public function __construct(private AssetRepositoryInterface $assetRepo)
    {
    }

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->assetRepo->getFiltered($filters, $perPage);
    }

    public function create(array $data, User $performer): Asset
    {
        return DB::transaction(function () use ($data, $performer) {
            $categoryName = $data['category_name'] ?? 'GEN';
            $prefix = self::CATEGORY_PREFIX_MAP[$categoryName] ?? strtoupper(substr($categoryName, 0, 3));
            $data['asset_code'] = Asset::generateAssetCode($prefix);
            $data['asset_tag'] = $data['asset_tag'] ?? Asset::generateAssetTag();

            $asset = $this->assetRepo->create($data);
            $this->logHistory($asset, 'Created', 'Asset created', $performer);

            AssetLog::create([
                'asset_id' => $asset->id,
                'action' => 'Created',
                'user_id' => $performer->id,
                'changes' => ['asset_tag' => $asset->asset_tag, 'name' => $asset->name],
            ]);

            return $asset;
        });
    }

    public function update(Asset $asset, array $data, User $performer): Asset
    {
        return DB::transaction(function () use ($asset, $data, $performer) {
            $oldValues = $asset->only(array_keys($data));
            $updated = $this->assetRepo->update($asset, $data);
            $newValues = $updated->only(array_keys($data));

            $this->logHistory($updated, 'Updated', 'Asset updated', $performer, $oldValues, $newValues);

            AssetLog::create([
                'asset_id' => $updated->id,
                'action' => 'Updated',
                'user_id' => $performer->id,
                'changes' => ['old' => $oldValues, 'new' => $newValues],
            ]);

            return $updated;
        });
    }

    public function delete(Asset $asset, User $performer): bool
    {
        $this->logHistory($asset, 'Deleted', 'Asset soft deleted', $performer);

        AssetLog::create([
            'asset_id' => $asset->id,
            'action' => 'Deleted',
            'user_id' => $performer->id,
            'changes' => ['asset_tag' => $asset->asset_tag, 'name' => $asset->name],
        ]);

        return $this->assetRepo->delete($asset);
    }

    public function assignUser(Asset $asset, int $userId, User $performer): Asset
    {
        $oldUser = $asset->assigned_user_id;
        $updated = $this->assetRepo->update($asset, ['assigned_user_id' => $userId]);

        $this->logHistory($updated, 'Assigned', "Assigned to user #{$userId}", $performer, [
            'assigned_user_id' => $oldUser,
        ], [
            'assigned_user_id' => $userId,
        ]);

        return $updated;
    }

    public function unassignUser(Asset $asset, User $performer): Asset
    {
        $oldUser = $asset->assigned_user_id;
        $updated = $this->assetRepo->update($asset, ['assigned_user_id' => null]);

        $this->logHistory($updated, 'Unassigned', "Unassigned from user #{$oldUser}", $performer, [
            'assigned_user_id' => $oldUser,
        ], [
            'assigned_user_id' => null,
        ]);

        return $updated;
    }

    public function retire(Asset $asset, User $performer): Asset
    {
        $updated = $this->assetRepo->update($asset, ['status' => Asset::STATUS_RETIRED]);
        $this->logHistory($updated, 'Retired', 'Asset retired', $performer);
        return $updated;
    }

    /**
     * Generate QR code SVG for an asset.
     */
    public function generateQrCode(Asset $asset, int $size = 200): string
    {
        $url = route('assets.show', $asset);
        return QrCode::size($size)->generate($url)->toHtml();
    }

    /**
     * Get comprehensive dashboard summary.
     */
    public function getDashboardSummary(): array
    {
        return [
            'total' => Asset::count(),
            'active' => Asset::where('status', Asset::STATUS_ACTIVE)->count(),
            'in_repair' => Asset::where('status', Asset::STATUS_IN_REPAIR)->count(),
            'in_stock' => Asset::where('status', Asset::STATUS_IN_STOCK)->count(),
            'retired' => Asset::where('status', Asset::STATUS_RETIRED)->count(),
            'lost' => Asset::where('status', Asset::STATUS_LOST)->count(),
            'broken' => Asset::where('status', Asset::STATUS_BROKEN)->count(),
            'assigned' => Asset::assigned()->count(),
            'unassigned' => Asset::unassigned()->count(),
            'warranty_expiring' => Asset::warrantyExpiringSoon(30)->count(),
            'by_category' => Asset::query()
                ->join('categories', 'assets.category_id', '=', 'categories.id')
                ->selectRaw('categories.name as category, count(*) as total')
                ->groupBy('categories.name')
                ->pluck('total', 'category')
                ->toArray(),
            'by_status' => Asset::query()
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray(),
        ];
    }

    public function getSummaryReport(): array
    {
        return $this->getDashboardSummary();
    }

    private function logHistory(
        Asset $asset,
        string $action,
        string $description,
        User $performer,
        ?array $oldValues = null,
        ?array $newValues = null,
    ): AssetHistory {
        return $asset->histories()->create([
            'action' => $action,
            'description' => $description,
            'performed_by' => $performer->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }
}
