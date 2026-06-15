<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Term;
use App\Services\TermService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function __construct(private TermService $service) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['category_id', 'q', 'difficulty']);
        $terms   = $this->service->getList($filters, $request->user()?->id);

        return response()->json($terms);
    }

    public function show(Request $request, Term $term): JsonResponse
    {
        $term = $this->service->getDetail($term, $request->user()?->id);

        return response()->json($term);
    }
}
