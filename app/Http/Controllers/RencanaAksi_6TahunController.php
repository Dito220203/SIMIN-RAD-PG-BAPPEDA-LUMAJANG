<?php

namespace App\Http\Controllers;

use App\Exports\RencanaAksiExport;
use App\Exports\RencanaExport;
use App\Helpers\LogHelper;
use App\Models\Monev;
use App\Models\Opd;
use App\Models\Pengguna;
use App\Models\ProgresKerja;
use App\Models\RencanaAksi_6_tahun;
use App\Models\RencanaKerja;
use App\Models\Subprogram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RencanaAksi_6TahunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $selectedTahun = $request->input('tahun'); // Ambil input tahun
        $tahuns = RencanaAksi_6_tahun::select('tahun')
            ->where('delete_at', '0')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $query = RencanaAksi_6_tahun::with(['subprogram', 'opd'])
            ->where('delete_at', '0');

        if ($selectedTahun) {
            $query->where('tahun', $selectedTahun);
        }
        $rencanaAksi = $query->get();
        return view('admin.RencanAksi6Tahun.index', compact('rencanaAksi', 'tahuns'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subprogram = Subprogram::where('delete_at', '0')->get();
        $opds = Opd::where('delete_at', '0')->get();
        return view('admin.RencanAksi6Tahun.create', compact('subprogram', 'opds'));
    }
    // BARU
    public function exportExcelAksi(Request $request)
    {
        // Ambil nilai 'tahun' dari URL
        $tahun = $request->input('tahun');

        // Kirim nilai 'tahun' ke class RencanaAksiExport
        return Excel::download(new RencanaAksiExport($tahun), 'rencana_aksi.xlsx');
    }
    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // 1. Validasi input dari form
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
           'keterangan' => 'nullable|string'

        ]);

        // 2. Cari pengguna yang sesuai dengan OPD yang dipilih
        $pengguna = Pengguna::where('id_opd', $validate['id_opd'])->first();

        // 3. Hentikan proses jika tidak ada pengguna yang ditemukan untuk OPD tersebut
        if (!$pengguna) {
            return back()->withInput()->withErrors(['id_opd' => 'Pengguna untuk OPD yang dipilih tidak ditemukan. Pastikan ada akun pengguna yang terhubung dengan OPD ini.']);
        }

        // 4. Ubah array anggaran dan sumberdana menjadi string
        $anggaranString = implode('; ', $validate['anggaran']);
        $sumberdanaString = implode('; ', $validate['sumberdana']);

        // 5. Buat data Rencana Aksi (diinput oleh admin yang sedang login)
        RencanaAksi_6_tahun::create([
            'id_pengguna'   => Auth::guard('pengguna')->id(), // Admin yang menginput
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
        ]);

        // 6. Buat data Rencana Kerja untuk PENGGUNA DARI OPD TERPILIH
        $rencana = RencanaKerja::create([
            'id_pengguna'   => $pengguna->id, // Menggunakan ID pengguna dari OPD
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
            'is_locked'     => true,
        ]);

        // 7. Buat data Monev untuk PENGGUNA DARI OPD TERPILIH
        $monev = Monev::create([
            'id_pengguna'   => $pengguna->id, // Menggunakan ID pengguna dari OPD
            'id_renja'      => $rencana->id,
            'id_subprogram' => $rencana->id_subprogram,
            'id_opd'        => $rencana->id_opd,

            'anggaran'      => $anggaranString,
            'sumberdana'    => $sumberdanaString,
            'is_locked'     => true,

        ]);

        // 8. Buat data Progres Kerja untuk PENGGUNA DARI OPD TERPILIH
        ProgresKerja::create([
            'id_pengguna' => $pengguna->id, // Menggunakan ID pengguna dari OPD
            'id_monev'    => $monev->id,
        ]);


        return redirect()->route('rencana6tahun')
            ->with('success', 'Rencana Aksi, Rencana Kerja, dan Monev berhasil ditambahkan!');
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
        $rencanaAksi = RencanaAksi_6_tahun::findOrFail($id);
        $subprogram = Subprogram::where('delete_at', '0')->get();
        $opds = Opd::where('delete_at', '0')->get();

        // PECAH STRING MENJADI ARRAY SEBELUM DIKIRIM KE VIEW
        $rencanaAksi->anggaran = explode('; ', $rencanaAksi->anggaran);
        $rencanaAksi->sumberdana = explode('; ', $rencanaAksi->sumberdana);

        return view('admin.RencanAksi6Tahun.update', compact('rencanaAksi', 'subprogram', 'opds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 1️⃣ Validasi input dari form
        $validate = $request->validate([
            'sub_program'  => 'required|exists:subprograms,id',
            'rencanaAksi'  => 'required',
            'nama_program' => 'required',
            'kegiatan'     => 'required',
            'sub_kegiatan' => 'required',
            'tahun'        => 'required',
            'anggaran'     => 'required|array',
            'anggaran.*'   => 'required|string',
            'sumberdana'   => 'required|array',
            'sumberdana.*' => 'required|string',
            'lokasi'       => 'required',
            'volume'       => 'required',
            'satuan'       => 'required',
            'id_opd'       => 'required|exists:opds,id',
           'keterangan' => 'nullable|string',
        ]);

        // 2️⃣ Ambil data lama SEBELUM diupdate untuk referensi
        $rencanaAksi = RencanaAksi_6_tahun::findOrFail($id);
        $oldTahun = $rencanaAksi->tahun;
        $oldRencanaAksi = $rencanaAksi->rencana_aksi; // Kunci untuk mencari RencanaKerja
        $oldIdOpd = $rencanaAksi->id_opd;

        $newPenggunaId = null;

        // 3️⃣ Cek apakah OPD diubah. Jika ya, cari pengguna baru.
        if ($oldIdOpd != $validate['id_opd']) {
            $newPengguna = Pengguna::where('id_opd', $validate['id_opd'])->first();

            // Jika tidak ada pengguna di OPD baru, batalkan update dan beri pesan error
            if (!$newPengguna) {
                return back()->withInput()->withErrors(['id_opd' => 'Gagal! Pengguna untuk OPD tujuan tidak ditemukan. Data tidak dapat dipindahkan.']);
            }
            $newPenggunaId = $newPengguna->id; // Simpan ID pengguna baru untuk pemindahan data
        }

        // 4️⃣ Gabungkan kembali array anggaran dan sumberdana menjadi string
        $anggaranString = implode('; ', $validate['anggaran']);
        $sumberdanaString = implode('; ', $validate['sumberdana']);

        // 5️⃣ Siapkan data yang akan diupdate
        $updateData = [
            'id_subprogram' => $validate['sub_program'],
            'rencana_aksi'  => $validate['rencanaAksi'],
            'nama_program'  => $validate['nama_program'],
            'kegiatan'      => $validate['kegiatan'],
            'sub_kegiatan'  => $validate['sub_kegiatan'],
            'tahun'         => $validate['tahun'],
            'anggaran'      => $anggaranString,
            'sumberdana'    => $sumberdanaString,
            'lokasi'        => $validate['lokasi'],
            'id_opd'        => $validate['id_opd'],
            'volume'        => $validate['volume'],
            'satuan'        => $validate['satuan'],
            'keterangan'    => $validate['keterangan'],
        ];

        // 6️⃣ Cari RencanaKerja yang terhubung berdasarkan data LAMA
        $rencanaKerja = RencanaKerja::where('rencana_aksi', $oldRencanaAksi)
            ->where('tahun', $oldTahun)
            ->where('id_opd', $oldIdOpd) // Spesifikasikan pencarian dengan OPD lama
            ->first();

        // Jika RencanaKerja ditemukan, lakukan sinkronisasi update
        if ($rencanaKerja) {
            $updateDataRenja = $updateData;
            // Jika OPD diubah, tambahkan ID pengguna baru ke data update
            if ($newPenggunaId) {
                $updateDataRenja['id_pengguna'] = $newPenggunaId;
            }
            $rencanaKerja->update($updateDataRenja);

            // 7️⃣ Sinkronkan update ke tabel Monev
            $monev = Monev::where('id_renja', $rencanaKerja->id)->first();
            if ($monev) {
                $updateDataMonev = [
                    'id_subprogram' => $rencanaKerja->id_subprogram,
                    'id_opd'        => $rencanaKerja->id_opd,
                    'anggaran'      => $anggaranString,
                    'sumberdana'    => $sumberdanaString,
                ];
                // Jika OPD diubah, perbarui juga pemilik data di Monev
                if ($newPenggunaId) {
                    $updateDataMonev['id_pengguna'] = $newPenggunaId;
                }
                $monev->update($updateDataMonev);

                // 8️⃣ Sinkronkan update ke tabel ProgresKerja (pemindahan kepemilikan)
                $progresKerja = ProgresKerja::where('id_monev', $monev->id)->first();
                if ($progresKerja && $newPenggunaId) {
                    $progresKerja->update(['id_pengguna' => $newPenggunaId]);
                }
            }
        }

        // 9️⃣ Terakhir, update data utama di RencanaAksi_6_tahun
        $rencanaAksi->update($updateData);


        return redirect()->route('rencana6tahun')
            ->with('success', 'Data berhasil diperbarui!');
    }






    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rencanaAksi = RencanaAksi_6_tahun::findOrFail($id);
        $rencanaAksi->update([
            'delete_at' => '1'
        ]);

        return redirect()->route('rencana6tahun')
            ->with('success', 'Rencana Aksi berhasil dihapus!');
    }
}
