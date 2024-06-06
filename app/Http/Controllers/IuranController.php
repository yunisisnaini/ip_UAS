<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Iuran;
use App\Models\Warga;

class IuranController extends Controller
{
    public function index()
    {
        // Mengambil semua entri dari tabel iurans
        $iurans = Iuran::select('id', 'id_wargas', 'bulan', 'jumlah_iuran', 'status')->get();

        // Response
        return response()->json([
            'data' => $iurans
        ], 200);
    }

    public function store(Request $request)
    {
        // Validasi request
        $this->validate($request, [
            'id_wargas' => 'required|exists:wargas,id',
            'bulan' => 'required|date_format:Y-m',
            'jumlah_iuran' => 'required|numeric',
            'status' => 'required|string'
        ]);

        // Buat entri baru
        $iuran = Iuran::create([
            'id_wargas' => $request->input('id_wargas'),
            'bulan' => $request->input('bulan'),
            'jumlah_iuran' => $request->input('jumlah_iuran'),
            'status' => $request->input('status')
        ]);

        // Response
        return response()->json([
            'message' => 'Data iuran berhasil ditambahkan',
            'data' => $iuran
        ], 201);
    }

    public function show($id)
    {
        // Mengambil entri dari tabel iurans berdasarkan ID
        $iuran = Iuran::find($id);

        if (!$iuran) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        // Response
        return response()->json([
            'data' => $iuran
        ], 200);
    }

    public function update(Request $request, $id)
    {
        // Validasi request
        $this->validate($request, [
            'status' => 'required|string'
        ]);

        // Mengambil entri dari tabel iurans berdasarkan ID
        $iuran = Iuran::find($id);

        if (!$iuran) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        // Update entri
        $iuran->status = $request->input('status');
        $iuran->save();

        // Response
        return response()->json([
            'message' => 'Data iuran berhasil diperbarui',
            'data' => $iuran
        ], 200);
    }

    public function destroy($id)
    {
        // Mengambil entri dari tabel iurans berdasarkan ID
        $iuran = Iuran::find($id);

        if (!$iuran) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        // Hapus entri
        $iuran->delete();

        // Response
        return response()->json([
            'message' => 'Data iuran berhasil dihapus'
        ], 200);
    }

    public function tunggakan($tahun)
    {
        $tunggakan = Iuran::where('status', 'pending')
            ->whereYear('created_at', $tahun)
            ->with('warga')
            ->get();

        $formattedTunggakan = [];

        foreach ($tunggakan as $iuran) {
            $detailTunggakan = $iuran->toArray();
            $detailTunggakan['detail_tunggakan'] = [
                [
                    'bulan' => $iuran->bulan,
                    'jumlah_iuran' => $iuran->jumlah_iuran
                ]
            ];

            if (!array_key_exists($iuran->id_wargas, $formattedTunggakan)) {
                $formattedTunggakan[$iuran->id_wargas] = [
                    'id' => $iuran->id_wargas,
                    'nama' => $iuran->warga->nama,
                    'alamat' => $iuran->warga->alamat,
                    'total_tunggakan' => $iuran->jumlah_iuran,
                    'detail_tunggakan' => []
                ];
            } else {
                $formattedTunggakan[$iuran->id_wargas]['total_tunggakan'] += $iuran->jumlah_iuran;
            }

            array_push($formattedTunggakan[$iuran->id_wargas]['detail_tunggakan'], [
                'bulan' => $iuran->bulan,
                'jumlah_iuran' => $iuran->jumlah_iuran
            ]);
        }

        return response()->json(['data' => array_values($formattedTunggakan)]);
    }
}
