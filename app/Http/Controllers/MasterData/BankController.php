<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterData\Bank;
use Illuminate\Http\Request;
use App\Http\Requests\MasterData\BankRequest;

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
    public function store(BankRequest $request)
    {
        Bank::create($request->validated());

        return redirect()->route('bank.index')->with('success', 'Bank berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BankRequest $request, string $id)
    {
        $bank = Bank::findOrFail($id);

        $validated = $request->validated();
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
