<?php

namespace App\Http\Controllers;


use App\Models\FotoProgres;
use App\Models\Map;
use App\Models\Notifikasi;
use App\Models\Pengguna;
use App\Models\ProgresKerja;
use App\Models\Subprogram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProgreskerjaController extends Controller
{

    public function index(Request $request)
    {
        $user   = Auth::guard('pengguna')->user();
        $search = $request->input('search');

        $query = ProgresKerja::with(['penggunas', 'subprogram', 'monev.rencanakerja', 'monev.map']);

        if ($user->level !== 'Super Admin') {
            $query->where('id_pengguna', $user->id);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('penggunas', function ($pengguna) use ($search) {
                        $pengguna->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('subprogram', function ($sub) use ($search) {
                        $sub->where('subprogram', 'like', "%{$search}%");
                    })
                    ->orWhereHas('monev.subprogram', function ($sp) use ($search) {
                        $sp->where('subprogram', 'like', "%{$search}%");
                    })
                    ->orWhereHas('monev.rencanakerja', function ($rk) use ($search) {
                        $rk->where('rencana_aksi', 'like', "%{$search}%")
                            ->orWhere('tahun', 'like', "%{$search}%");
                    });
            });
        }

        $progres = $query->paginate(10);
        $progres->appends($request->only('search'));

        return view('admin.ProgresKerja.index', compact('progres', 'search'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subprogram = Subprogram::where('delete_at', '0')->get();
        return view('admin.ProgresKerja.create', compact('subprogram'));
    }




    public function validasi(string $id)
    {
        $progres = ProgresKerja::findOrFail($id);
        $progres->status = 'Valid';
        $progres->save();

        return redirect()->route('progres')->with('success', 'Status berhasil divalidasi');
    }
    public function updateStatus(Request $request, string $id)
    {
        $progres = ProgresKerja::findOrFail($id);

        // ganti status progres
        if ($progres->status === 'Valid') {
            $progres->status = 'tidak valid';
        } else {
            $progres->status = 'Valid';
        }
        $progres->save();


        return redirect()->route('progres')->with('success', 'Status berhasil diperbarui');
    }
}
