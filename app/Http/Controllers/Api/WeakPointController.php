<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WeakPointService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeakPointController extends Controller
{
    public function __construct(private WeakPointService $service) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json($this->service->getWeakPoints($request->user()->id));
    }
}
