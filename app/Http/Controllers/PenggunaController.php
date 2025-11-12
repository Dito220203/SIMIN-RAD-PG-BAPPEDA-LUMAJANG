<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\Opd;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule; // Import Rule untuk validasi yang lebih kompleks

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Pengguna::with('opd'); // ikut load OPD biar lebih efisien

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('level', 'like', "%{$search}%");
            })
                ->orWhereHas('opd', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                });
        }

        $pengguna = $query->paginate(10);
        $pengguna->appends($request->only('search'));

        return view('admin.Pengguna.index', compact('pengguna', 'search'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $opd = Opd::where('delete_at', '0')->get();
        // Ambil ID OPD yang sudah digunakan
        $assigned_opd_ids = Pengguna::pluck('id_opd')->toArray();
        return view('admin.Pengguna.create', compact('opd', 'assigned_opd_ids'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:penggunas,username',
            'nama' => 'required',
            'password' => 'required',
            'level' => 'required',
            // Tambahkan validasi unique untuk id_opd
            'id_opd' => 'required|exists:opds,id|unique:penggunas,id_opd',
        ], [
            // Pesan error kustom jika OPD sudah dipilih
            'id_opd.unique' => 'Perangkat Daerah ini sudah dipilih oleh pengguna lain.',
        ]);


        //Simpan ke DB dengan password di-hash
        Pengguna::create([
            'id_opd'    => $request->id_opd,
            'username'  => $request->username,
            'nama'      => $request->nama,
            'password'  => Hash::make($request->password),
            'level'     => $request->level,

        ]);

        return redirect()->route('pengguna')->with('success', 'Data Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Ambil data pengguna berdasarkan ID
        $pengguna = Pengguna::findOrFail($id);

        // Ambil semua OPD untuk pilihan dropdown
        $opd = Opd::all();

        // Ambil ID OPD yang sudah digunakan oleh pengguna LAIN
        $assigned_opd_ids = Pengguna::where('id', '!=', $id)->pluck('id_opd')->toArray();

        return view('admin.Pengguna.update', compact('pengguna', 'opd', 'assigned_opd_ids'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pengguna = Pengguna::findOrFail($id);

        $request->validate([
            // Username harus unik, kecuali untuk user yang sedang diedit
            'e_username' => ['required', Rule::unique('penggunas', 'username')->ignore($id)],
            'e_nama'     => 'required',
            // ID OPD harus unik, kecuali untuk user yang sedang diedit
            'e_id_opd'   => ['required', 'exists:opds,id', Rule::unique('penggunas', 'id_opd')->ignore($id)],
            'e_level'    => 'required',
            'e_password' => 'nullable|string|min:8',
        ], [
             // Pesan error kustom jika OPD sudah dipilih
            'e_id_opd.unique' => 'Perangkat Daerah ini sudah dipilih oleh pengguna lain.',
        ]);

        $data = [
            'username' => $request->e_username,
            'nama'     => $request->e_nama,
            'id_opd'   => $request->e_id_opd,
            'level'    => $request->e_level,
        ];

        // Jika password diisi, hash dan update
        if ($request->filled('e_password')) {
            $data['password'] = Hash::make($request->e_password);
        }

        $pengguna->update($data);



        return redirect()->route('pengguna')->with('success', 'Data Berhasil Di Update');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Pengguna::where('id', $id)->delete();

        return redirect()->route('pengguna')->with('success', 'Data Berhasil Dihapus');
    }
}
