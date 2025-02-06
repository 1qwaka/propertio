<?php

namespace App\Http\Controllers;

use App\Services\PrometheusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Prometheus\Exception\MetricsRegistrationException;
use Prometheus\RenderTextFormat;

class PrometheusController extends Controller
{
    private PrometheusService $service;

    public function __construct ( PrometheusService $service )
    {
        $this->service = $service;
    }

    public function metrics ()
    {
//        $response = Response::make($this->service->metrics(), 200);
//        $response->header('Content-Type', RenderTextFormat::MIME_TYPE);
//        return $response;
//        return response($this->service->metrics(), 200, [
//            'Content-Type' => RenderTextFormat::MIME_TYPE
//        ]);
//        return response($this->service->metrics())
//            ->header('Content-Type', RenderTextFormat::MIME_TYPE);
        return response()->make($this->service->metrics(), 200)
            ->header('Content-Type', 'text/plain; version=0.0.4');
    }

    /**
     * @throws MetricsRegistrationException
     */
    public function createTestOrder(): JsonResponse
    {
        $this->service->createTestOrder();

        return response()->json(['message' => 'The order has been successfully created']);
    }
}
