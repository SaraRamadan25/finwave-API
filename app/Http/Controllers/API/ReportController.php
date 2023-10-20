<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(): JsonResponse
    {
        $reports = Report::with('transactions')->paginate(5);
        return ReportResource::collection($reports)->response();
    }
    public function show(Report $report): ReportResource|JsonResponse
    {
        $report->load('transactions');
        return new ReportResource($report);
    }
    public function store(StoreReportRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        return response()->json(['message' => 'we will generate the appropriate report for your period'], 201);
    }

}
