<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Services\StatusService;
use Illuminate\Http\JsonResponse;

class StatusApiController extends Controller
{
    public function __construct(
        protected readonly StatusService $statusService
    ) {}

    public function status(): JsonResponse
    {
        return $this->statusService->status();
    }
}
