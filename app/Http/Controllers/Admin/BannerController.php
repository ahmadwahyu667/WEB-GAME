<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBannerRequest;
use App\Http\Requests\Admin\UpdateBannerRequest;
use App\Models\AdminAuditLog;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order', 'asc')->paginate(15);

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(StoreBannerRequest $request)
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->has('is_active');

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('banners', 'public');
            }

            $banner = Banner::create($data);

            AdminAuditLog::log('create_banner', "Menambahkan banner baru: {$banner->title}");

            return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage())->withInput();
        }
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->has('is_active');

            if ($request->hasFile('image')) {
                if ($banner->image) {
                    Storage::disk('public')->delete($banner->image);
                }
                $data['image'] = $request->file('image')->store('banners', 'public');
            }

            $banner->update($data);

            AdminAuditLog::log('update_banner', "Memperbarui banner: {$banner->title}");

            return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(Banner $banner)
    {
        try {
            $bannerTitle = $banner->title;
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $banner->delete();

            AdminAuditLog::log('delete_banner', "Menghapus banner: {$bannerTitle}");

            return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}
