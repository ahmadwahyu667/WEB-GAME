@extends('layouts.app')
@section('title', $news->title . ' - Berita Gaming')

@section('content')
<div class="section container">
    <div style="margin-bottom: var(--space-xl);">
        <a href="{{ route('news.index') }}" style="color: var(--primary-cyan); font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Berita
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 320px; gap: var(--space-3xl); align-items: start;">
        
        {{-- Main Article --}}
        <article style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-xl); overflow: hidden;">
            @if($news->thumbnail)
                <img src="{{ $news->thumbnail }}" alt="{{ $news->title }}" style="width: 100%; height: auto; max-height: 500px; object-fit: cover;">
            @endif
            
            <div style="padding: var(--space-2xl);">
                <div style="display: flex; gap: var(--space-md); align-items: center; margin-bottom: var(--space-md); font-size: 0.9rem;">
                    <span style="color: var(--primary-cyan); font-weight: 600;">{{ $news->created_at->format('d M Y') }}</span>
                    <span style="color: var(--text-muted);">•</span>
                    <span style="color: var(--text-secondary);">Oleh {{ $news->author ?? 'Admin' }}</span>
                </div>
                
                <h1 style="font-family: var(--font-heading); font-size: 2.25rem; font-weight: 800; color: var(--text-primary); margin-bottom: var(--space-xl); line-height: 1.3;">{{ $news->title }}</h1>
                
                <div style="color: var(--text-secondary); font-size: 1.05rem; line-height: 1.8;">
                    {!! $news->content !!}
                </div>
            </div>
        </article>

        {{-- Sidebar --}}
        <aside>
            <h3 style="font-family: var(--font-heading); font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: var(--space-lg); border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">Berita Lainnya</h3>
            
            @php
                $relatedNews = \App\Models\News::where('status', 'published')
                                ->where('id', '!=', $news->id)
                                ->latest()
                                ->take(3)
                                ->get();
            @endphp

            @if($relatedNews->count() > 0)
                <div style="display: flex; flex-direction: column; gap: var(--space-lg);">
                    @foreach($relatedNews as $related)
                        <a href="{{ route('news.show', $related->slug) }}" style="display: flex; gap: var(--space-md); text-decoration: none; group;">
                            @if($related->thumbnail)
                                <img src="{{ $related->thumbnail }}" alt="{{ $related->title }}" style="width: 100px; height: 75px; object-fit: cover; border-radius: var(--radius-sm); flex-shrink: 0;">
                            @else
                                <div style="width: 100px; height: 75px; background: var(--bg-secondary); border-radius: var(--radius-sm); flex-shrink: 0;"></div>
                            @endif
                            <div>
                                <h4 style="font-family: var(--font-heading); font-size: 0.9rem; font-weight: 600; color: var(--text-primary); margin-bottom: 4px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; transition: color 0.3s;" onmouseover="this.style.color='var(--primary-cyan)'" onmouseout="this.style.color='var(--text-primary)'">{{ $related->title }}</h4>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $related->created_at->format('d M Y') }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div style="color: var(--text-muted); font-size: 0.9rem;">Belum ada berita lainnya.</div>
            @endif
        </aside>

    </div>
</div>
@endsection
