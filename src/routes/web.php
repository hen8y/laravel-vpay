<?php

use Illuminate\Support\Facades\Route;
use Hen8y\Vpay\App\Http\Controller\VpayController;

Route::post('payment/webhook/vpay', [VpayController::class, 'handleWebhook'])->middleware('VerifyWebhook');
