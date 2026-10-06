<?php

namespace App\Http\Controllers;

use App\Models\SalarySlip;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class StaffPayrollController extends Controller
{
    /**
     * Helper: ambil staff dari user yang login, dengan validasi role & permission.
     */
    private function getAuthenticatedStaff()
    {
        if (Auth::user()->role !== 'staff') {
            abort(redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.'));
        }

        if (!Auth::user()->hasPermission('payroll')) {
            abort(redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat slip gaji.'));
        }

        $staff = Staff::where('user_id', Auth::id())->first();

        if (!$staff) {
            abort(redirect()->route('admin.dashboard')->with('error', 'Staff profile tidak ditemukan.'));
        }

        return $staff;
    }

    public function index(Request $request)
    {
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        if (!Auth::user()->hasPermission('payroll')) {
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat slip gaji.');
        }

        $staff = Staff::where('user_id', Auth::id())->first();

        if (!$staff) {
            return redirect()->route('admin.dashboard')->with('error', 'Staff profile tidak ditemukan.');
        }

        $query = SalarySlip::where('staff_id', $staff->id);

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        $salarySlips = $query->orderBy('month', 'desc')->orderBy('created_at', 'desc')->paginate(20);

        return view('staff.payroll.index', compact('salarySlips', 'staff'));
    }

    public function show($id)
    {
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        if (!Auth::user()->hasPermission('payroll')) {
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat slip gaji.');
        }

        $staff = Staff::where('user_id', Auth::id())->first();

        if (!$staff) {
            return redirect()->route('admin.dashboard')->with('error', 'Staff profile tidak ditemukan.');
        }

        $salarySlip = SalarySlip::with('staff')->findOrFail($id);

        if ($salarySlip->staff_id !== $staff->id) {
            return redirect()->route('staff.payroll.index')->with('error', 'Akses ditolak.');
        }

        return view('staff.payroll.show', compact('salarySlip'));
    }

    public function downloadPdf($id)
    {
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        if (!Auth::user()->hasPermission('payroll')) {
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses untuk mengunduh slip gaji.');
        }

        $staff = Staff::where('user_id', Auth::id())->first();

        if (!$staff) {
            return redirect()->route('admin.dashboard')->with('error', 'Staff profile tidak ditemukan.');
        }

        $salarySlip = SalarySlip::with('staff')->findOrFail($id);

        if ($salarySlip->staff_id !== $staff->id) {
            return redirect()->route('staff.payroll.index')->with('error', 'Akses ditolak.');
        }

        // Pakai template yang sama dengan admin
        $pdf = Pdf::loadView('admin.payroll.pdf', compact('salarySlip'));
        return $pdf->download('salary-slip-' . $salarySlip->staff->nama . '-' . $salarySlip->month . '.pdf');
    }
}