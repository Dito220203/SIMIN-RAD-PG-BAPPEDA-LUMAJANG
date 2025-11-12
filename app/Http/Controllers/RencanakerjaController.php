<?php

namespace App\Http\Controllers;


use App\Models\Opd;
use App\Models\RencanaAksi_6_tahun;
use App\Models\RencanaKerja;
use App\Models\Subprogram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RencanaExport;
use App\Models\Monev;
use App\Models\ProgresKerja;

class RencanakerjaController extends Controller
{

    // app/Http/Controllers/RencanakerjaController.php

    public function index(Request $request)
    {
        $user   = Auth::guard('pengguna')->user();
        $search = $request->input('search');
        $tahun  = $request->input('tahun'); // <-- AMBIL INPUT TAHUN DARI REQUEST

        // Ambil semua tahun unik dari database untuk dropdown filter
        // distinct() untuk mengambil nilai unik & pluck() untuk mengambil satu kolom saja
        $daftarTahun = RencanaKerja::query()
            ->active() // Hanya dari data yang aktif
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc') // Urutkan dari tahun terbaru
            ->pluck('tahun');

        $query = RencanaKerja::with(['subprogram', 'opd'])
            ->active();

        if ($user->level !== 'Super Admin') {
            $query->where('id_pengguna', $user->id);
        }

        // TERAPKAN FILTER TAHUN JIKA ADA
        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('rencana_aksi', 'like', "%{$search}%")
                    // ... sisa query pencarian Anda tidak berubah ...
                    ->orWhere('keterangan', 'like', "%{$search}%");
            })
                ->orWhereHas('opd', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                })
                ->orWhereHas('subprogram', function ($q) use ($search) {
                    $q->where('subprogram', 'like', "%{$search}%");
                });
        }

        $rencana = $query->paginate(10);
        // Tambahkan 'tahun' agar pagination tetap mengingat filter tahun yang dipilih
        $rencana->appends($request->only('search', 'tahun'));
        $opdIdsWithData = RencanaKerja::select('id_opd')->whereNotNull('id_opd')->distinct()->pluck('id_opd');

        $allOpds = Opd::whereIn('id', $opdIdsWithData)->orderBy('nama', 'asc')->get();

        // KIRIM VARIABEL BARU KE VIEW
        return view('admin.RencanaKerja.index', compact('rencana', 'search', 'daftarTahun', 'tahun', 'allOpds'));
    }




    public function getRencanaAksi($id_subprogram)
    {
        $rencanaAksi = RencanaAksi_6_tahun::where('id_subprogram', $id_subprogram)->where('delete_at', '0')
            ->get();

        return response()->json($rencanaAksi);
    }
    public function getDetail($id)
    {
        $data = RencanaAksi_6_tahun::with(['subprogram'])->findOrFail($id);

        return response()->json([
            'sub_kegiatan' => $data->sub_kegiatan,
            'kegiatan'     => $data->kegiatan,
            'nama_program' => $data->nama_program,
            // 'tahun'        => $data->tahun,
        ]);
    }




    public function create()
    {
        $subprogram = Subprogram::where('delete_at', '0')->get();
        $opd = Opd::where('delete_at', '0')->get();
        $rencanaAksi =  RencanaAksi_6_tahun::where('delete_at', '0')->get();

        return view('admin.RencanaKerja.create', compact('subprogram', 'opd', 'rencanaAksi'));
    }
    // BARU
    public function exportExcel(Request $request)
    {
        $user = Auth::guard('pengguna')->user();
        $tahun = $request->input('tahun'); // Ambil nilai tahun dari request

        // Kirim user dan tahun ke class export
        return Excel::download(new RencanaExport($user, $tahun), 'rencana_kerja.xlsx');
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'sub_program' => 'required|exists:subprograms,id',
            'nama_program' => 'required',
            'rencanaAksi' => 'required',
            'kegiatan' => 'required',
            'sub_kegiatan' => 'required',
            'tahun' => 'required',
            'anggaran' => 'required|array',
            'anggaran.*' => 'required|string',
            'sumberdana' => 'required|array',
            'sumberdana.*' => 'required|string',
            'lokasi' => 'required',
            'volume' => 'required',
            'satuan' => 'required',
            'id_opd' => 'required|exists:opds,id',
            'keterangan' => 'required'
        ]);

        $anggaranString = implode('; ', $validate['anggaran']);
        $sumberdanaString = implode('; ', $validate['sumberdana']);

        // simpan ke tabel rencana kerja
        $rencana = RencanaKerja::create([
            'id_pengguna'   => Auth::guard('pengguna')->id(),
            'id_subprogram' => $validate['sub_program'],
            'rencana_aksi'  => $validate['rencanaAksi'],
            'nama_program'  => $validate['nama_program'],
            'kegiatan'      => $validate['kegiatan'],
            'sub_kegiatan'  => $validate['sub_kegiatan'],
            'tahun'         => $validate['tahun'],
            'anggaran'      => $anggaranString,
            'sumberdana'    => $sumberdanaString,
            'lokasi'        => $validate['lokasi'],
            'volume'        => $validate['volume'],
            'satuan'        => $validate['satuan'],
            'id_opd'        => $validate['id_opd'],
            'keterangan'    => $validate['keterangan'],
            'input'        => 'manual',
        ]);

        // otomatis simpan juga ke tabel monev
        $monev = Monev::create([
            'id_pengguna'   => $rencana->id_pengguna,
            'id_subprogram' => $rencana->id_subprogram,
            'id_renja'      => $rencana->id,
            'id_opd'        => $rencana->id_opd,
            'anggaran'      => $anggaranString,
            'sumberdana'    => $sumberdanaString,
            'is_locked'     => true,


        ]);

        ProgresKerja::create([
            'id_pengguna' => $monev->id_pengguna,
            'id_monev'    => $monev->id,

        ]);

        return redirect()->route('rencanakerja')
            ->with('success', 'Rencana Kerja berhasil ditambahkan!');
    }



    public function validasi(string $id)
    {
        $rencana = RencanaKerja::findOrFail($id);
        $rencana->status = 'Valid';
        $rencana->save();

        return redirect()->route('rencanakerja')->with('success', 'Status berhasil divalidasi');
    }

    public function updateStatus(string $id)
    {
        $rencana = RencanaKerja::findOrFail($id);
        $rencana->status = $rencana->status === 'Valid' ? 'tidak valid' : 'Valid';
        $rencana->save();

        return redirect()->route('rencanakerja')->with('success', 'Status berhasil diperbarui');
    }

    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        $user = Auth::guard('pengguna')->user();
        $rencana = RencanaKerja::findOrFail($id);

        // Jika bukan Super Admin, cek apakah data ini miliknya
        if ($user->level !== 'Super Admin' && $rencana->id_pengguna !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        $subprogram = Subprogram::where('delete_at', '0')->get();
        $opd = Opd::where('delete_at', '0')->get();

        // PECAH STRING MENJADI ARRAY SEBELUM DIKIRIM KE VIEW
        $rencana->anggaran = explode('; ', $rencana->anggaran);
        $rencana->sumberdana = explode('; ', $rencana->sumberdana);

        return view('admin.RencanaKerja.update', compact('rencana', 'subprogram', 'opd'));
    }

    public function update(Request $request, string $id)
    {
        // 1️⃣ Validasi input
        $validate = $request->validate([
            'sub_program'  => 'required|exists:subprograms,id',
            'rencanaAksi'  => 'required',
            'sub_kegiatan' => 'required',
            'kegiatan'     => 'required',
            'nama_program' => 'required',
            'tahun'        => 'required',
            'volume'       => 'required',
            'satuan'       => 'required',
            'anggaran'     => 'required|array',
            'anggaran.*'   => 'required|string',
            'sumberdana'   => 'required|array',
            'sumberdana.*' => 'required|string',
            'lokasi'       => 'required',
            'id_opd'       => 'required|exists:opds,id',
            'keterangan'   => 'required'
        ]);

        // 2️⃣ Gabungkan array menjadi string
        $anggaranString = implode('; ', $validate['anggaran']);
        $sumberdanaString = implode('; ', $validate['sumberdana']);

        // 3️⃣ Ambil data lama
        $rencana = RencanaKerja::findOrFail($id);
        $user = Auth::guard('pengguna')->user();

        if ($user->level !== 'Super Admin' && $rencana->id_pengguna !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengupdate data ini.');
        }

        // 4️⃣ Data update utama
        $updateData = [
            'id_subprogram' => $validate['sub_program'],
            'rencana_aksi'  => $validate['rencanaAksi'],
            'sub_kegiatan'  => $validate['sub_kegiatan'],
            'kegiatan'      => $validate['kegiatan'],
            'nama_program'  => $validate['nama_program'],
            'tahun'         => $validate['tahun'],
            'volume'        => $validate['volume'],
            'satuan'        => $validate['satuan'],
            'anggaran'      => $anggaranString,
            'sumberdana'    => $sumberdanaString,
            'lokasi'        => $validate['lokasi'],
            'id_opd'        => $validate['id_opd'],
            'keterangan'    => $validate['keterangan'],
        ];

        // 5️⃣ Update tabel Rencana Kerja
        $rencana->update($updateData);

        // 6️⃣ Cari Monev berdasarkan id_renja (bukan rencana_aksi)
        $monev = Monev::where('id_renja', $rencana->id)->first();

        if ($monev) {
            $monev->update([
                'id_subprogram' => $rencana->id_subprogram,
                'id_opd'        => $rencana->id_opd,
                'anggaran'      => $anggaranString,
                'sumberdana'    => $sumberdanaString,
            ]);
        }



        // 8️⃣ Redirect
        return redirect()->route('rencanakerja')
            ->with('success', 'Rencana Kerja berhasil diperbarui!');
    }

    public function bulkToggleLock(Request $request)
    {
        // 1. Pastikan hanya Super Admin yang bisa mengakses
        if (auth()->guard('pengguna')->user()->level !== 'Super Admin') {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // 2. Validasi input dari form
        $validated = $request->validate([
            'opd_id' => 'required|exists:opds,id',
            'action' => 'required|in:lock,unlock',
        ], [
            'opd_id.required' => 'Anda harus memilih Perangkat Daerah.',
            'action.required' => 'Anda harus memilih Aksi.',
        ]);

        $opdId = $validated['opd_id'];
        $action = $validated['action'];
        $opd = Opd::findOrFail($opdId);

        // 3. Tentukan status kunci yang baru
        $newState = ($action === 'lock');

        // 4. 👇 BAGIAN YANG DIPERBAIKI ADA DI SINI 👇
        // Update semua data monev yang dimiliki oleh OPD tersebut
        RencanaKerja::where('id_opd', $opdId)->update(['is_locked' => $newState]);

        // 5. Siapkan pesan feedback untuk pengguna
        $actionText = $newState ? 'dikunci' : 'dibuka';
        $message = "Semua data untuk OPD {$opd->nama} berhasil {$actionText}.";


        // 6. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', $message);
    }


    public function destroy(string $id)
    {
        $rencana = RencanaKerja::findOrFail($id);

        if ($rencana->file && Storage::disk('public')->exists($rencana->file)) {
            Storage::disk('public')->delete($rencana->file);
        }

        $rencana->update([
            'delete_at' => '1'
        ]);

        return redirect()->route('rencanakerja')->with('success', 'Data berhasil dihapus');
    }
}
