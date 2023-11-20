<?php

namespace Hen8y\Vpay\App\Http\Controller;

use Hen8y\Vpay\App\Jobs\VpayJob;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class VpayController extends Controller
{
    public function handleWebhook(Request $request){

        $payload = $request->all();
        VpayJob::dispatch($payload);


        return response()->json([
            "message"=>"Webhook received successfully and processed",
        ]);

    }
}
