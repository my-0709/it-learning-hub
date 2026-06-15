<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Term;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $terms = Term::with(['category', 'tags'])
            ->whereHas('favoritedBy', fn($q) => $q->where('user_id', $request->user()->id))
            ->paginate(20);

        $terms->getCollection()->transform(function ($term) {
            $term->is_favorite = true;
            return $term;
        });

        return response()->json($terms);
    }

    public function toggle(Request $request, Term $term)
    {
        $userId = $request->user()->id;
        $exists = Favorite::where('user_id', $userId)->where('term_id', $term->id)->first();

        if ($exists) {
            $exists->delete();
            return response()->json(['is_favorite' => false]);
        }

        Favorite::create(['user_id' => $userId, 'term_id' => $term->id]);
        return response()->json(['is_favorite' => true]);
    }
}
