<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function index(Request $request)
    {
        $query = Term::with(['category', 'tags'])
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->q, fn($q) => $q->where(function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->q}%")
                      ->orWhere('definition', 'like', "%{$request->q}%");
            }))
            ->when($request->difficulty, fn($q) => $q->where('difficulty', $request->difficulty));

        $user = $request->user();
        $favoriteIds = $user
            ? $user->favorites()->pluck('term_id')->toArray()
            : [];

        $terms = $query->orderBy('name')->paginate(20);

        $terms->getCollection()->transform(function ($term) use ($favoriteIds) {
            $term->is_favorite = in_array($term->id, $favoriteIds);
            return $term;
        });

        return response()->json($terms);
    }

    public function show(Request $request, Term $term)
    {
        $term->load(['category', 'tags']);
        $user = $request->user();
        $term->is_favorite = $user
            ? $user->favorites()->where('term_id', $term->id)->exists()
            : false;

        return response()->json($term);
    }
}
