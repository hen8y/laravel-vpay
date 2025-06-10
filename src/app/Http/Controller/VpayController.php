<?php

namespace Hen8y\Vpay\App\Http\Controller;

use App\Jobs\VpayJob;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class VpayController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();
        $vpayJobFilePath = app_path('Jobs/VpayJob.php');
        if (file_exists($vpayJobFilePath)) {
            VpayJob::dispatch($payload);

            return response()->json([
                "message" => "Webhook received successfully and processed",
            ]);
        }
    }
}
