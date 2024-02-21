<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class VpayJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;



    /**
     * Create a new job instance.
     */
    public function __construct(public $payload)
    {
        $this->payload = $payload;
    }



    /**
     * Execute the job.
     */
    public function handle()
    {
        if($this->payload["originator_account_name"] != "Failed Card Transaction" ){

            Log::info($this->payload);


            $transactionref = $this->payload['transactionref'];
            $amount = $this->payload['amount'];
            // get the transaction with same transactionref and update status to be successfull

            // Increment the user balance by the amount
        }
    }
}
