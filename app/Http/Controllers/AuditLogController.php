<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    /**
     * Tampilkan daftar audit log
     */
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $query = AuditLog::with('user');

        // Filter berdasarkan module
        if ($request->has('module') && $request->module != '') {
            $query->module($request->module);
        }

        // Filter berdasarkan action
        if ($request->has('action') && $request->action != '') {
            $query->action($request->action);
        }

        // Filter berdasarkan user
        if ($request->has('user_id') && $request->user_id != '') {
            $query->user($request->user_id);
        }

        // Filter berdasarkan tanggal
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'LIKE', '%' . $search . '%')
                    ->orWhere('module', 'LIKE', '%' . $search . '%')
                    ->orWhere('action', 'LIKE', '%' . $search . '%');
            });
        }

        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(50);

        // Statistik
        $stats = [
            'total' => AuditLog::count(),
            'today' => AuditLog::whereDate('created_at', today())->count(),
            'this_week' => AuditLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => AuditLog::whereMonth('created_at', now()->month)->count(),
        ];

        // Daftar user untuk filter
        $users = User::select('id', 'name', 'role')->orderBy('name')->get();

        return view('admin.audit-logs.index', compact('auditLogs', 'stats', 'users'));
    }

    /**
     * Tampilkan detail audit log
     */
    public function show($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $auditLog = AuditLog::with('user')->findOrFail($id);
        return view('admin.audit-logs.show', compact('auditLog'));
    }

    /**
     * Hapus audit log lama (cleanup)
     */
    public function cleanup(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'days' => 'required|integer|min:30|max:365',
        ]);

        $days = $request->days;
        $deletedCount = AuditLog::where('created_at', '<', now()->subDays($days))->delete();

        return back()->with('success', "Berhasil menghapus {$deletedCount} log yang lebih dari {$days} hari.");
    }
}