<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LearningRecordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LearningRecordController extends Controller
{
    public function __construct(private LearningRecordService $service) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json($this->service->getHistory($request->user()->id));
    }

    public function stats(Request $request): JsonResponse
    {
        return response()->json($this->service->getStats($request->user()->id));
    }
}
