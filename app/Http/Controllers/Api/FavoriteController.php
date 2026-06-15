<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Term;
use App\Services\FavoriteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function __construct(private FavoriteService $service) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json($this->service->getList($request->user()->id));
    }

    public function toggle(Request $request, Term $term): JsonResponse
    {
        $isFavorite = $this->service->toggle($request->user()->id, $term->id);

        return response()->json(['is_favorite' => $isFavorite]);
    }
}
