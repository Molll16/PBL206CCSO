<?php

namespace App\Http\Controllers;

use App\Services\AlertService;

class AlertController extends Controller
{
    protected $alertService;

    public function __construct(AlertService $alertService)
    {
        $this->alertService = $alertService;
    }

    public function getAlerts()
    {
        return $this->alertService->getLatestAlerts();
    }

    public function index()
    {
        // Controller super tipis, delegasi penuh ke AlertService
        $analytics = $this->alertService->getTodayLogsAnalytics();

        return view('Customer.logs.daftarlog', $analytics);
    }
}