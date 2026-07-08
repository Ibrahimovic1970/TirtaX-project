<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    /**
     * Tampilkan daftar cabang
     */
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $query = Branch::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', '%' . $search . '%')
                    ->orWhere('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('city', 'LIKE', '%' . $search . '%');
            });
        }

        // Filter status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $branches = $query->orderBy('is_main', 'desc')->orderBy('name')->paginate(20);

        return view('admin.branches.index', compact('branches'));
    }

    /**
     * Tampilkan form tambah cabang
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        return view('admin.branches.create');
    }

    /**
     * Simpan cabang baru
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'code' => 'required|string|max:50|unique:branches,code',
            'name' => 'required|string|max:150',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'pic_name' => 'nullable|string|max:100',
            'pic_phone' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'nullable|boolean',
            'is_main' => 'nullable|boolean',
        ]);

        // Jika is_main = true, set semua cabang lain jadi false
        if ($request->is_main) {
            Branch::where('id', '!=', null)->update(['is_main' => false]);
        }

        Branch::create([
            'code' => $request->code,
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
            'phone' => $request->phone,
            'email' => $request->email,
            'pic_name' => $request->pic_name,
            'pic_phone' => $request->pic_phone,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_active' => $request->has('is_active'),
            'is_main' => $request->has('is_main'),
        ]);

        return redirect()->route('admin.branches.index')
            ->with('success', 'Cabang berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail cabang
     */
    public function show($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $branch = Branch::findOrFail($id);
        return view('admin.branches.show', compact('branch'));
    }

    /**
     * Tampilkan form edit cabang
     */
    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $branch = Branch::findOrFail($id);
        return view('admin.branches.edit', compact('branch'));
    }

    /**
     * Update cabang
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $branch = Branch::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:50|unique:branches,code,' . $id,
            'name' => 'required|string|max:150',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'pic_name' => 'nullable|string|max:100',
            'pic_phone' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'nullable|boolean',
            'is_main' => 'nullable|boolean',
        ]);

        // Jika is_main = true, set semua cabang lain jadi false
        if ($request->is_main) {
            Branch::where('id', '!=', $id)->update(['is_main' => false]);
        }

        $branch->update([
            'code' => $request->code,
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
            'phone' => $request->phone,
            'email' => $request->email,
            'pic_name' => $request->pic_name,
            'pic_phone' => $request->pic_phone,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_active' => $request->has('is_active'),
            'is_main' => $request->has('is_main'),
        ]);

        return redirect()->route('admin.branches.index')
            ->with('success', 'Cabang berhasil diupdate!');
    }

    /**
     * Hapus cabang
     */
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $branch = Branch::findOrFail($id);

        // Cek apakah cabang utama
        if ($branch->is_main) {
            return back()->with('error', 'Cabang utama tidak dapat dihapus!');
        }

        $branch->delete();

        return back()->with('success', 'Cabang berhasil dihapus!');
    }
}