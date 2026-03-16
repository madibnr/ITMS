<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetFileController extends Controller
{
    public function store(Request $request, Asset $asset)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store('asset-files/' . $asset->id, 'public');

        AssetFile::create([
            'asset_id' => $asset->id,
            'file_name' => $uploadedFile->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $uploadedFile->getClientMimeType(),
            'size' => $uploadedFile->getSize(),
            'uploaded_by' => $request->user()->id,
        ]);

        return redirect()->route('assets.show', $asset)
            ->with('success', 'File uploaded successfully.');
    }

    public function download(AssetFile $assetFile)
    {
        return Storage::disk('public')->download($assetFile->file_path, $assetFile->file_name);
    }

    public function destroy(AssetFile $assetFile)
    {
        $assetId = $assetFile->asset_id;
        Storage::disk('public')->delete($assetFile->file_path);
        $assetFile->delete();

        return redirect()->route('assets.show', $assetId)
            ->with('success', 'File deleted successfully.');
    }
}
