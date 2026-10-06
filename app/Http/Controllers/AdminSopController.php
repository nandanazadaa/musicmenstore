<?php

namespace App\Http\Controllers;

use App\Models\Sop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSopController extends Controller
{
    public function index()
    {
        $sops = Sop::orderBy('created_at', 'desc')->get();
        return view('admin.sop.index', compact('sops'));
    }

    public function create()
    {
        return view('admin.sop.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_sop' => 'required|string|max:255', // Ganti = menjadi :
            'icon_visual' => 'required|string|max:255', // Ganti = menjadi :
            'isi_konten_sop' => 'required|string',
        ]);

        Sop::create([
            'judul_sop' => $request->judul_sop,
            'icon_visual' => $request->icon_visual,
            'deskripsi_singkat' => $request->deskripsi_singkat,
            'isi_konten_sop' => $request->isi_konten_sop,
            'dibuat_oleh' => Auth::user()->name,
        ]);

        return redirect()->route('admin.sop.index')->with('success', 'SOP Baru berhasil diterbitkan!');
    }

    public function show($id)
    {
        $sop = Sop::findOrFail($id);
        return view('admin.sop.show', compact('sop'));
    }

    public function edit($id)
    {
        $sop = Sop::findOrFail($id);
        return view('admin.sop.edit', compact('sop'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_sop' => 'required|string|max:255', // Ganti = menjadi :
            'icon_visual' => 'required|string|max:255', // Ganti = menjadi :
            'isi_konten_sop' => 'required|string',
        ]);

        $sop = Sop::findOrFail($id);
        $sop->update($request->all());

        return redirect()->route('admin.sop.index')->with('success', 'SOP berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $sop = Sop::findOrFail($id);
        $sop->delete();

        return redirect()->route('admin.sop.index')->with('success', 'SOP berhasil dihapus!');
    }
}