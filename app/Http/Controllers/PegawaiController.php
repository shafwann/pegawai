<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::all();

        $jabatan = Jabatan::all();

        return view('pegawai', ['pegawai' => $pegawai, 'jabatan' => $jabatan]);
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'nama' => 'required',
            'foto_profile' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'jabatan' => 'required',
            'daterange' => 'required',
            'gaji' => 'required|numeric',

        ]);

        $file = $request->file('foto_profile');
        $name = $file->getClientOriginalName();
        $file->move(public_path('/images'), $name);

        $pegawai = new Pegawai();
        $pegawai->nama = $request->nama;
        $pegawai->foto_profile = $name;
        $pegawai->jabatan = $request->jabatan;
        list($tanggal_dipekerjakan, $tanggal_berhenti) = explode(' - ', $request->daterange);
        $pegawai->tanggal_dipekerjakan = $tanggal_dipekerjakan;
        $pegawai->tanggal_berhenti = $tanggal_berhenti;
        $pegawai->gaji = $request->gaji;
        $pegawai->save();

        return redirect('/')->with('success', 'Data Pegawai berhasil disimpan.');
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::find($id);
        if ($pegawai) {
            $image_path = public_path('/images/') . $pegawai->foto_profile;
            if (!empty($pegawai->foto_profile) && file_exists($image_path)) {
                unlink($image_path);
            }
            $pegawai->delete();
            return response()->json(['message' => 'Pegawai berhasil dihapus'], 200);
        }

        return response()->json(['message' => 'Pegawai tidak ditemukan'], 404);
    }

    public function detail($id)
    {
        $pegawai = Pegawai::find($id);
        $jabatan = Jabatan::all();

        return view('detail', ['pegawai' => $pegawai, 'jabatan' => $jabatan]);
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::find($id);

        if ($pegawai) {
            $this->validate($request, [
                'nama' => 'required',
                'jabatan' => 'required',
                'daterange' => 'required',
                'gaji' => 'required|numeric',
            ]);

            $pegawai->nama = $request->nama;
            $pegawai->jabatan = $request->jabatan;
            list($tanggal_dipekerjakan, $tanggal_berhenti) = explode(' - ', $request->daterange);
            $pegawai->tanggal_dipekerjakan = $tanggal_dipekerjakan;
            $pegawai->tanggal_berhenti = $tanggal_berhenti;
            $pegawai->gaji = $request->gaji;

            if ($request->hasFile('foto_profile')) {

                $this->validate($request, ['foto_profile' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',]);

                $file = $request->file('foto_profile');
                $name = $file->getClientOriginalName();
                $file->move(public_path('/images'), $name);
                $image_path = public_path('/images/') . $pegawai->foto_profile;
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
                $pegawai->foto_profile = $name;
            } else {
                $pegawai->foto_profile = $pegawai->foto_profile;
            }

            $pegawai->save();

            return redirect('/')->with('success', 'Data Pegawai berhasil diupdate.');
        }

        return redirect('/')->with('error', 'Data Pegawai tidak ditemukan.');
    }
}
