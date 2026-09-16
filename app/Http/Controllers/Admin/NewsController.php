<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNewsRequest;
use App\Http\Requests\Admin\UpdateNewsRequest;
use App\Models\AdminAuditLog;
use App\Models\News;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderBy('created_at', 'desc')->paginate(15);
        $newsList = $news;

        return view('admin.news.index', compact('news', 'newsList'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(StoreNewsRequest $request)
    {
        try {
            $data = $request->validated();
            $data['slug'] = Str::slug($data['title']).'-'.time();

            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->file('thumbnail')->store('news', 'public');
            }

            $news = News::create($data);

            AdminAuditLog::log('create_news', "Menambahkan berita baru: {$news->title}");

            return redirect()->route('admin.news.index')->with('success', 'Berita berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage())->withInput();
        }
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(UpdateNewsRequest $request, News $news)
    {
        try {
            $data = $request->validated();

            if ($data['title'] !== $news->title) {
                $data['slug'] = Str::slug($data['title']).'-'.time();
            }

            if ($request->hasFile('thumbnail')) {
                if ($news->thumbnail) {
                    Storage::disk('public')->delete($news->thumbnail);
                }
                $data['thumbnail'] = $request->file('thumbnail')->store('news', 'public');
            }

            $news->update($data);

            AdminAuditLog::log('update_news', "Memperbarui berita: {$news->title}");

            return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(News $news)
    {
        try {
            $newsTitle = $news->title;
            if ($news->thumbnail) {
                Storage::disk('public')->delete($news->thumbnail);
            }
            $news->delete();

            AdminAuditLog::log('delete_news', "Menghapus berita: {$newsTitle}");

            return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}
