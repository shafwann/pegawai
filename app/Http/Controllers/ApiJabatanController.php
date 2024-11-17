<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class ApiJabatanController extends Controller
{
    public function index()
    {
        return Jabatan::all();
    }

    public function store(Request $request)
    {
        $jabatan = Jabatan::create($request->all());
        return response()->json($jabatan, 201);
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update($request->all());
        return response()->json($jabatan, 200);
    }

    public function destroy($id)
    {
        Jabatan::destroy($id);
        return response()->json(null, 204);
    }
}
