<?php

namespace App\Http\Controllers;

use App\Models\Music;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MusicController extends Controller
{
    private const PER_PAGE = 10;

    public function index(): View
    {
        $tracks = Music::query()->paginate(self::PER_PAGE);

        return view('music.index', [
            'tracks' => $tracks,
            'pageTitle' => 'All Musics',
        ]);
    }

    public function saveFavorite(Music $music): RedirectResponse
    {
        $user = Auth::user();

        if ($user->musics()->where('id', $music->id)->exists()) {
            $user->musics()->detach($music->id);
        } else {
            $user->musics()->attach($music->id);
        }

        return redirect()->back();
    }

    public function trackListenProgress(Request $request): JsonResponse
    {
        $track = Music::query()->findOrFail($request->track_id);
        $track->plays += 1;
        $track->save();

        return response()->json([
            'plays' => $track->plays,
            'status' => 'success',
        ]);
    }
}
