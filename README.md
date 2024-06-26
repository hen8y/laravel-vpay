# laravel-vpay

![downloads](https://img.shields.io/packagist/dt/hen8y/laravel-flash.svg)

## Installation

[PHP](https://php.net) 7.2, [LARAVEL](https://laravel.com), and [Composer](https://getcomposer.org) are required.

To get the latest version of Laravel Vpay, simply require it

```bash
composer require hen8y/laravel-vpay
```

Or add the following line to the require block of your `composer.json` file.

```bash
"hen8y/laravel-vpay": "1.*"
```

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once Laravel Vpay is installed, you need to register the service provider. Open up config/app.php and add the following to the providers key.

```php
'providers' => [
    ...
    Hen8y\Vpay\VpayServiceProvider::class,
    ...
]

```

You can publish the configuration file and assets by running:

```php
    php artisan vendor:publish --tag=vpay-config

```

## Important

 The url below is for your vpay webhook, don't include it as part of your routes. Callbacks are not as efficient as webhooks, so we've crafted this package to use webhooks mainly but you can opt for the callback option

 ```php
    /payment/webhook/vpay
 ```

Visit Vpay dashboard and add `https://yoursite.com/payment/webhook/pay` as your webhook url in the settings

## Configuration

If you want to use the webhook make sure to publish the job file:

```bash
php artisan vpay:publish
```

### A file would be created

- Job-file named `VpayJob.php` in the `Jobs` directory

### Details of the Config file

The configuration-file named `vpay.php` with some defaults that was placed in your `config` directory:

```php
<?php



return [

    /*
    |--------------------------------------------------------------------------
    | Status of Your Applicatiom 
    |--------------------------------------------------------------------------
    |
    | Here you may specify what is the status pf your app
    | Options("live", "sandbox")
    | By leaving this empty field empty would by default use sandbox
    |
    */
    "status"=> env("VPAY_STATUS","sandbox"),

    /*
    |--------------------------------------------------------------------------
    | Public Key 
    |--------------------------------------------------------------------------
    |
    | Enter the public key gotten from vpay website 
    |
    */
    "public_id"=> env("VPAY_PUBLICID"),

    /*
    |--------------------------------------------------------------------------
    | Public Key 
    |--------------------------------------------------------------------------
    |
    | Enter the secret key gotten from vpay website
    |
    */
    "secret_key"=> env("VPAY_SECRET"),

    /*
    |--------------------------------------------------------------------------
    | Customer Care Email Address 
    |--------------------------------------------------------------------------
    |
    | Here specify the customer service & support channels of your business 
    | e.g. Tel: +2348030070000, Email: support@yourorg.com
    |
    */
    "customer_service_channel"=> env("MERCHANT_EMAIL"),

    /*
    |--------------------------------------------------------------------------
    | Customer Care Support Logo 
    |--------------------------------------------------------------------------
    |
    | Here specify a link to your customer support logo
    |
    */
    "customer_logo"=>"",


    /*
    |--------------------------------------------------------------------------
    | Transaction Type 
    |--------------------------------------------------------------------------
    |
    | Here specify your transaction type,
    | Options("percentage","flat")
    |
    | if chose percentage you can edit the percent required (txn_charge), default is 1.3%
    |
    */
    "txn_charge_type"=> "flat",

    "txn_charge"=>1.3,


];
```

## Usage

Exclude the webhook url from CSRF verification

Open your `VerifyCsrfToken Middleware` file located in `app/Http/Middleware` and add the url like this:

```php
    protected $except = [
        //

        "/payment/webhook/vpay",
    ];

```

Open your .env file and add your public key, secret key, merchant email and payment url like so:

```php
VPAY_PUBLICID=xxxxxxxx
VPAY_SECRET=xxxxxxxx
VPAY_STATUS=sandbox
MERCHANT_EMAIL=hen8y@outlook.com
```

Set up your redirect & callback route :

- Redirect to the checkout

```php
Route::post('/payment/redirect', [\App\Http\Controllers\PaymentController::class,'redirectToGateway']);
```

- Callback

```php
Route::post('/payment/callback', [\App\Http\Controllers\PaymentController::class,'callback']);

```

Set up your payment controller to send the payment details

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hen8y\Vpay\Vpay;




class PaymentController extends Controller
{

    /**
     * Redirect the User to Checkout Page
     */
    public function redirectToGateway()
    {

        // Sends the transaction data to the checkout handler
        $data = array(
            'amount'=> request("amount"),
            "email"=>request("email"),
            "transactionref"=> \Str::random(25),
        );
        return (new Vpay)->handleCheckout($data);
    }


    /**
     * Callback
     *
     * @param Request $request
     */
     public function callback(Request $request){
        // This will give you all the data sent in the POST request
        // Now you can access individual data elements like $data['status'], $data['amount'], etc.

        // if successfull $request->input('status') will return success

        // if failed $request->input('status') will return failed

        $status = $request->input('status');
        $amount = $request->input('amount');
        $transactionref = $request->input('transactionref');
        $email = $request->input('email');

        // Use the retrieved data as needed
    }


}
```

### If you opt to use the webhook, if not you can skip this step

Set up your Job to handle the Payment Webhook, The Job was already Published after you ran the `php artisan vpay:publish` command:

In app/Jobs you would see a file VpayJob, edit it to handle the webhook data after every successful transaction. Visit [Vpay Docs](https://docs.vpay.africa/vpay-js-inline-dropin-integration-guide/6.-webhook-payload-authentication) to view the payload data

```php


<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class VpayJob implements ShouldQueue
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
            
             
             // Get the transaction with same transactionref and update status to be successfull
            // Increment the user balance by the amount
            
        }
    }
}


```

Sample Html/Bootstrap Form

```html
<form method="POST" action="/payment/redirect/" role="form" class="mt-5 col-md-8 mx-auto">
    @csrf
    <h3>Payment Form</h3>
    <div class="row mb-5">
        <div class="col-md-8">
            <input type="email" class="form-control mt-3" name="email" placeholder="Email Address"> {{-- required --}}
            <input type="text" class="form-control mt-3" name="amount" placeholder="Amount"> {{-- required --}}

            <button class="btn btn-primary mt-3">Submit</button>
        </div>
    </div>
</form>
```

After clicking the submit button the customer gets redirected a checkout page.

After the customer does some actions there and now gets redirected back to either the success or failure route with callback data.

#### For webhook

Vpay would send some data to the webhook (remember to exclude the webhook url from CSRF verification). This webhook will contain some information as its payload. Additionally, a JWT token will accompany this payload, with {secret: your_secret_key} as its content.

We must validate if the redirect to our site is a valid request (we don't want imposters to wrongfully place non-paid order).

The webhook url `payment/webhook/vpay` is secured using a middleware (you don't have to set up any middleware as it has been set already in this package). This decodes the JWT token and compare its secret payload with the secret-key added in the env file, on success it dispatches a job to queue, check the Job in your App/Job and handle the details

Make sure to run 👇 for the job on queue

```php

    php artisan queue:work
```

## Contributing

Please feel free to fork this package and contribute by submitting a pull request to enhance the functionalities.

Don't forget to [follow me on twitter](https://twitter.com/hen8y)!

Thanks!
Ogbonna Henry (hen8y).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
