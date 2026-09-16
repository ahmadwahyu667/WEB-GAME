@extends('layouts.app')
@section('title', 'Berita Gaming')

@section('content')
<div class="section container">
    <div style="text-align: center; margin-bottom: var(--space-3xl);">
        <h1 style="font-family: var(--font-heading); font-size: 2.5rem; font-weight: 800; margin-bottom: var(--space-md); background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Berita & Update Gaming</h1>
        <p style="color: var(--text-secondary); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Informasi terbaru seputar dunia game, update fitur, dan promo menarik dari GameMarket.</p>
    </div>

    @if($news->count() > 0)
        @php
            $featured = $news->first();
            $remaining = $news->skip(1);
        @endphp

        {{-- Featured Article --}}
        @if($news->currentPage() == 1)
            <a href="{{ route('news.show', $featured->slug) }}" style="display: block; text-decoration: none; margin-bottom: var(--space-3xl);">
                <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-xl); overflow: hidden; display: flex; flex-direction: column; transition: transform 0.3s; box-shadow: var(--shadow-glow-cyan);">
                    @if($featured->thumbnail)
                        <img src="{{ $featured->thumbnail }}" alt="{{ $featured->title }}" style="width: 100%; height: 400px; object-fit: cover;">
                    @else
                        <div style="width: 100%; height: 400px; background: var(--bg-secondary); display: flex; align-items: center; justify-content: center; color: var(--text-muted);">No Image</div>
                    @endif
                    <div style="padding: var(--space-2xl);">
                        <div style="display: flex; gap: var(--space-md); align-items: center; margin-bottom: var(--space-md); font-size: 0.85rem; color: var(--primary-cyan);">
                            <span>{{ $featured->created_at->format('d M Y') }}</span>
                            <span style="color: var(--text-muted);">•</span>
                            <span style="color: var(--text-secondary);">Oleh {{ $featured->author ?? 'Admin' }}</span>
                        </div>
                        <h2 style="font-family: var(--font-heading); font-size: 2rem; font-weight: 700; color: var(--text-primary); margin-bottom: var(--space-md);">{{ $featured->title }}</h2>
                        <p style="color: var(--text-secondary); font-size: 1rem; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $featured->excerpt ?? strip_tags(substr($featured->content, 0, 150)) }}
                        </p>
                    </div>
                </div>
            </a>
        @endif

        {{-- News Grid --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: var(--space-xl);">
            @php $articles = ($news->currentPage() == 1) ? $remaining : $news; @endphp
            @foreach($articles as $article)
                <a href="{{ route('news.show', $article->slug) }}" class="news-card">
                    <div class="news-card__image">
                        @if($article->thumbnail)
                            <img src="{{ $article->thumbnail }}" alt="{{ $article->title }}">
                        @else
                            <div style="width: 100%; height: 100%; background: var(--bg-secondary); display: flex; align-items: center; justify-content: center; color: var(--text-muted);">No Image</div>
                        @endif
                    </div>
                    <div class="news-card__body">
                        <div class="news-card__date" style="display: flex; justify-content: space-between;">
                            <span>{{ $article->created_at->format('d M Y') }}</span>
                            <span>{{ $article->author ?? 'Admin' }}</span>
                        </div>
                        <h3 class="news-card__title">{{ $article->title }}</h3>
                        <div class="news-card__excerpt">
                            {{ $article->excerpt ?? strip_tags(substr($article->content, 0, 100)) }}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div style="margin-top: var(--space-3xl);">
            {{ $news->links('pagination::tailwind') }}
        </div>
    @else
        <div style="text-align: center; padding: var(--space-3xl) 0; background: var(--bg-card); border-radius: var(--radius-xl); border: 1px solid var(--border-color);">
            <div style="font-size: 3rem; margin-bottom: var(--space-md);">📰</div>
            <h3 style="font-family: var(--font-heading); font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">Belum ada berita</h3>
            <p style="color: var(--text-secondary);">Pantau terus untuk update terbaru dari GameMarket.</p>
        </div>
    @endif
</div>
@endsection
