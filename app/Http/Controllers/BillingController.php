<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\User\BillingService;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    protected $service;

    public function __construct(BillingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
    }

    public function notification(Request $request)
    {
        return $this->service->notification($request);
    }

    public function subscription()
    {
        return $this->service->subscription();
    }

    public function subscribe(Plan $plan)
    {
        return $this->service->subscribe($plan);
    }

    public function cancel()
    {
        auth()->user()->subscription('default')->cancel();

        return back();
    }

    public function resume()
    {
        auth()->user()->subscription('default')->resume();

        return back();
    }

    public function success(Request $request)
    {
        return $this->service->success($request);
    }

    public function cancelCallback()
    {
        return $this->service->cancelCallback();
    }
}
