<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGameRequest;
use App\Http\Requests\Admin\UpdateGameRequest;
use App\Models\AdminAuditLog;
use App\Models\Game;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::withCount('products')->paginate(15);

        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        return view('admin.games.create');
    }

    public function store(StoreGameRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('logo')) {
                $data['logo'] = $request->file('logo')->store('games', 'public');
            }

            if ($request->hasFile('banner')) {
                $data['banner'] = $request->file('banner')->store('games', 'public');
            }

            $game = Game::create($data);

            AdminAuditLog::log('create_game', "Menambahkan game baru: {$game->name}");

            return redirect()->route('admin.games.index')->with('success', 'Game berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage())->withInput();
        }
    }

    public function edit(Game $game)
    {
        return view('admin.games.edit', compact('game'));
    }

    public function update(UpdateGameRequest $request, Game $game)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('logo')) {
                if ($game->logo) {
                    Storage::disk('public')->delete($game->logo);
                }
                $data['logo'] = $request->file('logo')->store('games', 'public');
            }

            if ($request->hasFile('banner')) {
                if ($game->banner) {
                    Storage::disk('public')->delete($game->banner);
                }
                $data['banner'] = $request->file('banner')->store('games', 'public');
            }

            $game->update($data);

            AdminAuditLog::log('update_game', "Memperbarui game: {$game->name}");

            return redirect()->route('admin.games.index')->with('success', 'Game berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(Game $game)
    {
        try {
            $gameName = $game->name;
            $game->delete();

            AdminAuditLog::log('delete_game', "Menghapus game: {$gameName}");

            return redirect()->route('admin.games.index')->with('success', 'Game berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}
