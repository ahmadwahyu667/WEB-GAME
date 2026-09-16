<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        $news = News::where('status', 'published')->latest()->paginate(9);

        return view('news.index', compact('news'));
    }

    public function show(string $slug): View
    {
        $news = News::where('slug', $slug)->where('status', 'published')->firstOrFail();

        return view('news.show', compact('news'));
    }
}
