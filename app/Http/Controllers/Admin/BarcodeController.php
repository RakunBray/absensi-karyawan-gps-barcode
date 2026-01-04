<?php

namespace App\Http\Controllers\Admin;

use App\BarcodeGenerator;
use App\Http\Controllers\Controller;
use App\Models\Barcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class BarcodeController extends Controller
{
    protected $rules = [
        'name' => ['required', 'string', 'max:255'],
        'value' => ['required', 'string', 'max:255', 'unique:barcodes'],
        'lat' => ['required', 'numeric', 'between:-90,90'],
        'lng' => ['required', 'numeric', 'between:-180,180'],
        'radius' => ['required', 'numeric', 'min:1'], // Minimal 1 meter
    ];

    public function index()
    {
        $barcodes = Barcode::latest()->get();
        return view('admin.barcodes.index', compact('barcodes'));
    }

    public function show(string $id)
    {
        $barcode = Barcode::findOrFail($id);
        
        // Return data sesuai dengan model yang sudah diperbaiki
        return response()->json([
            'id' => $barcode->id,
            'name' => $barcode->name,
            'value' => $barcode->value,
            'latitude' => $barcode->latitude,
            'longitude' => $barcode->longitude,
            'radius' => $barcode->radius,
            'created_at' => $barcode->created_at,
            'updated_at' => $barcode->updated_at,
        ]);
    }

    public function create()
    {
        return view('admin.barcodes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);
        
        try {
            Log::info('Creating barcode:', $validated);
            
            // Konversi tipe data sesuai dengan migration
            $barcode = Barcode::create([
                'name' => $validated['name'],
                'value' => $validated['value'],
                'latitude' => (double) $validated['lat'],    // Cast ke double sesuai migration
                'longitude' => (double) $validated['lng'],   // Cast ke double sesuai migration
                'radius' => (float) $validated['radius'],    // Cast ke float sesuai migration
            ]);
            
            Log::info('Barcode created successfully', [
                'id' => $barcode->id,
                'name' => $barcode->name,
                'value' => $barcode->value,
            ]);
            
            return redirect()->route('admin.barcodes')
                ->with('flash.banner', 'Barcode "' . $barcode->name . '" berhasil dibuat.')
                ->with('flash.bannerStyle', 'success');
                
        } catch (\Throwable $th) {
            Log::error('Failed to create barcode:', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'input' => $validated,
                'pdo_error' => method_exists($th, 'errorInfo') ? $th->errorInfo() : null,
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('flash.banner', 'Gagal membuat barcode: ' . $th->getMessage())
                ->with('flash.bannerStyle', 'danger');
        }
    }

    public function edit(Barcode $barcode)
    {
        return view('admin.barcodes.edit', compact('barcode'));
    }

    public function update(Request $request, Barcode $barcode)
    {
        $rules = $this->rules;
        $rules['value'] = ['required', 'string', 'max:255', Rule::unique('barcodes')->ignore($barcode->id)];
        
        $validated = $request->validate($rules);
        
        try {
            Log::info('Updating barcode:', [
                'id' => $barcode->id,
                'old_data' => $barcode->toArray(),
                'new_data' => $validated,
            ]);
            
            $barcode->update([
                'name' => $validated['name'],
                'value' => $validated['value'],
                'latitude' => (double) $validated['lat'],
                'longitude' => (double) $validated['lng'],
                'radius' => (float) $validated['radius'],
            ]);
            
            Log::info('Barcode updated successfully', ['id' => $barcode->id]);
            
            return redirect()->route('admin.barcodes')
                ->with('flash.banner', 'Barcode "' . $barcode->name . '" berhasil diperbarui.')
                ->with('flash.bannerStyle', 'success');
                
        } catch (\Throwable $th) {
            Log::error('Failed to update barcode:', [
                'id' => $barcode->id,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'input' => $validated,
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('flash.banner', 'Gagal memperbarui barcode: ' . $th->getMessage())
                ->with('flash.bannerStyle', 'danger');
        }
    }

    public function destroy(Barcode $barcode)
    {
        try {
            $barcodeName = $barcode->name;
            $barcode->delete();
            
            Log::info('Barcode deleted', [
                'id' => $barcode->id,
                'name' => $barcodeName,
            ]);
            
            return redirect()->route('admin.barcodes')
                ->with('flash.banner', 'Barcode "' . $barcodeName . '" berhasil dihapus.')
                ->with('flash.bannerStyle', 'success');
                
        } catch (\Throwable $th) {
            Log::error('Failed to delete barcode:', [
                'id' => $barcode->id,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            
            return redirect()->back()
                ->with('flash.banner', 'Gagal menghapus barcode: ' . $th->getMessage())
                ->with('flash.bannerStyle', 'danger');
        }
    }

    public function download($barcodeId)
    {
        try {
            $barcode = Barcode::findOrFail($barcodeId);
            $generator = new BarcodeGenerator(width: 1280, height: 1280);
            $barcodeFile = $generator->generateQrCode($barcode->value);
            
            // Generate safe filename
            $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $barcode->name ?? $barcode->value);
            $filename = $safeName . '.png';
            
            Log::info('Barcode downloaded', [
                'id' => $barcode->id,
                'filename' => $filename,
            ]);
            
            return response($barcodeFile)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
                
        } catch (\Throwable $th) {
            Log::error('Failed to download barcode:', [
                'id' => $barcodeId,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            
            return redirect()->back()
                ->with('flash.banner', 'Gagal mengunduh barcode: ' . $th->getMessage())
                ->with('flash.bannerStyle', 'danger');
        }
    }

    public function downloadAll()
    {
        try {
            $barcodes = Barcode::all();
            
            if ($barcodes->isEmpty()) {
                return redirect()->back()
                    ->with('flash.banner', 'Tidak ada barcode yang ditemukan.')
                    ->with('flash.bannerStyle', 'warning');
            }
            
            $generator = new BarcodeGenerator(width: 1280, height: 1280);
            
            $barcodeData = $barcodes->mapWithKeys(function ($barcode) {
                $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $barcode->name ?? $barcode->value);
                return [$safeName => $barcode->value];
            })->toArray();
            
            $zipFile = $generator->generateQrCodesZip($barcodeData);
            
            Log::info('All barcodes downloaded', [
                'count' => $barcodes->count(),
                'filename' => 'barcodes_' . date('Y-m-d_H-i-s') . '.zip',
            ]);
            
            return response(file_get_contents($zipFile))
                ->header('Content-Type', 'application/zip')
                ->header('Content-Disposition', 'attachment; filename="barcodes_' . date('Y-m-d_H-i-s') . '.zip"')
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
                
        } catch (\Throwable $th) {
            Log::error('Failed to download all barcodes:', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            
            return redirect()->back()
                ->with('flash.banner', 'Gagal mengunduh semua barcode: ' . $th->getMessage())
                ->with('flash.bannerStyle', 'danger');
        }
    }
}