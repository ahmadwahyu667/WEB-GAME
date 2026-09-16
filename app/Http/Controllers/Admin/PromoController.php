<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePromoRequest;
use App\Http\Requests\Admin\UpdatePromoRequest;
use App\Models\AdminAuditLog;
use App\Models\Promo;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.promos.index', compact('promos'));
    }

    public function create()
    {
        return view('admin.promos.create');
    }

    public function store(StorePromoRequest $request)
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->has('is_active');

            $promo = Promo::create($data);

            AdminAuditLog::log('create_promo', "Menambahkan promo baru: {$promo->code}");

            return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage())->withInput();
        }
    }

    public function edit(Promo $promo)
    {
        return view('admin.promos.edit', compact('promo'));
    }

    public function update(UpdatePromoRequest $request, Promo $promo)
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->has('is_active');

            $promo->update($data);

            AdminAuditLog::log('update_promo', "Memperbarui promo: {$promo->code}");

            return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(Promo $promo)
    {
        try {
            $promoCode = $promo->code;
            $promo->delete();

            AdminAuditLog::log('delete_promo', "Menghapus promo: {$promoCode}");

            return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}
