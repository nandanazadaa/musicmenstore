<?php

namespace App\Http\Controllers;

use App\Models\Sop;

class StaffSopController extends Controller
{
    public function index()
    {
        $sops = Sop::orderBy('created_at', 'desc')->get();
        return view('staff.sop.index', compact('sops'));
    }

    public function show($id)
    {
        $sop = Sop::findOrFail($id);
        return view('staff.sop.show', compact('sop'));
    }
}