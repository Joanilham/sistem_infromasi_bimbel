<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search  = $request->input('search', '');
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        $sort    = in_array($request->input('sort'), ['id', 'nama_bank']) ? $request->input('sort') : 'id';
        $order   = in_array($request->input('order'), ['asc', 'desc']) ? $request->input('order') : 'desc';

        $banks = Bank::where(function ($q) use ($search) {
                $q->where('nama_bank', 'like', "%{$search}%")
                  ->orWhere('nomor_rekening', 'like', "%{$search}%")
                  ->orWhere('atas_nama', 'like', "%{$search}%");
            })
            ->orderBy($sort, $order)
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.bank.index', compact('banks', 'search', 'perPage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->has('nomor_rekening')) {
            $request->merge([
                'nomor_rekening' => preg_replace('/[^0-9]/', '', $request->nomor_rekening)
            ]);
        }

        $validated = $request->validate([
            'nama_bank' => 'required|string|max:255',
            'nomor_rekening' => 'required|numeric|digits_between:10,16',
            'atas_nama' => 'required|string|max:255',
        ], [
            'nomor_rekening.required' => 'Nomor rekening wajib diisi.',
            'nomor_rekening.numeric' => 'Nomor rekening harus berupa angka saja.',
            'nomor_rekening.digits_between' => 'Nomor rekening harus terdiri dari 10 sampai 16 digit.',
        ]);

        Bank::create($validated);

        return redirect()->route('bank.index')->with('success', 'Bank berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $bank = Bank::findOrFail($id);

        if ($request->has('nomor_rekening')) {
            $request->merge([
                'nomor_rekening' => preg_replace('/[^0-9]/', '', $request->nomor_rekening)
            ]);
        }

        $validated = $request->validate([
            'nama_bank' => 'required|string|max:255',
            'nomor_rekening' => 'required|numeric|digits_between:10,16',
            'atas_nama' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ], [
            'nomor_rekening.required' => 'Nomor rekening wajib diisi.',
            'nomor_rekening.numeric' => 'Nomor rekening harus berupa angka saja.',
            'nomor_rekening.digits_between' => 'Nomor rekening harus terdiri dari 10 sampai 16 digit.',
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool) $request->is_active : true;

        $bank->update($validated);

        return redirect()->route('bank.index')->with('success', 'Bank berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();

        return redirect()->route('bank.index')->with('success', 'Bank berhasil dihapus.');
    }
}
