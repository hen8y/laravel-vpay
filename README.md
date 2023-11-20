# laravel-vpay


## Installation

[PHP](https://php.net) 5.5, [LARAVEL](https://laravel.com), and [Composer](https://getcomposer.org) are required.

To get the latest version of Laravel Vpay, simply require it

```bash
composer require hen8y/laravel-vpay
```

Or add the following line to the require block of your `composer.json` file.

```
"hen8y/laravel-vpay": "1.*"
```

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.



Once Laravel Vpay is installed, you need to register the Facade like so:

```php
'aliases' => [
    ...
    'Vpay' => hen8y\Vpay\Facades\Vpay::class,
    ...
]
```

## Important

 The url below is for your vpay webhook. Callbacks are not as efficient as webhooks, so we've crafted this package to use webhooks mainly but you can opt for the callback option

 ```
    /payment/webhook/vpay
 ```

Visit Vpay dashboard and add `https://yoursite.com/payment/webhook/pay` as your webhook url in the settings

## Configuration

You can publish the files needed using this command:

```bash
php artisan make:vpay
```

#### 3 Files would be created
- Configuration-file named `vpay.php` in the `config` directory
- Job-file named `VpayJob.php` in the `Jobs` directory
- Blade-file named `checkout.php` in the `views/vpay` directory

#### Details of the Config file

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
MERCHANT_EMAIL=hen8y@gmail.com
```


Set up your success & failure callback routes :

- Redirect to the checkout


```php
Route::post('/payment/redirect', 'PaymentController@redirectToGateway')->name('vpay.redirect');
```


- The deposit success

```php
Route::post('/payment/success/{transactionref}', 'PaymentController@success')->name('vpay.payment.success');
```

- The deposit failure

```php
Route::post('/payment/failure/{transactionref}', 'PaymentController@failure')->name('vpay.payment.fail');
```





Set up your payment controller to send the payment details


```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hen8y\Vpay\Vpay;
use Str;




class PaymentController extends Controller
{

    /**
     * Redirect the User to Vpay Checkout Page
     */
    public function redirectToGateway()
    {

        // Create a trasaction 

        // \App\Models\Transaction::create([
        //     'user_id'=>auth()->user()->id,
        //     'amount'=>request("amount"),
        //     'type' =>'Deposit',
        //     'transactionref'=> request("transactionref"),
        //     'status'=>'Pending',
        // ]);


        /**
         * Sends the transaction data to the checkout handler
         * 
        */
        $data = array(
        'amount'=> request("amount"),
        "email"=>request("email"),
        "currency"=>"NGN",
        "transactionref"=> request("transactionref"),
        );
        return Vpay::handleCheckout($data);  
    }


    public function success($transactionref)
    {
        // Now you can send a notification of success to user
    }


    public function failure($transactionref)
    {
        /**
         * Since Vpay doesn't send any webhook data when transfer fails, if you created a pending transaction when 
         * redirecting to the gateway in the redirectToGateway(), make sure to cancel the transaction
         * 
         * Transaction::where("transactionref",$transactionref)->delete();
        */
    }


}
```



Set up your Job to handle the Payment Webhook, The Job was already Published after you ran the `php artisan make:vpay` command:

In app/Jobs you would see a file VpayJob, edit it to handle the webhook data after every successful transaction. Visit [Vpay Docs](https://docs.vpay.africa/vpay-js-inline-dropin-integration-guide/6.-webhook-payload-authentication) to view the payload data


```php


<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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

        Log::info($this->payload)


        $transactionref = $this->payload['transactionref'];
        $amount = $this->payload['amount'];
        // get the transaction with same transactionref and update status to be successfull

        // Increment the user balance by the amount
    }
}


```


```html
<form method="POST" action="{{ route('vpay.redirect') }}" role="form">
    <div class="row" style="margin-bottom:40px;">
        <div class="col-md-8 col-md-offset-2">
            <input type="hidden" name="email" value="hen8y@outlook.com"> {{-- required --}}
            <input type="hidden" name="amount" value="800"> {{-- required --}}
            <input type="hidden" name="currency" value="NGN">
            
            <input type="hidden" name="_token" value="{{ csrf_token() }}"> {{-- employ this in place of csrf_field only in laravel 5.0 --}}

            <p>
                <button class="btn btn-success btn-lg btn-block" type="submit" value="Pay Now!">
                    <i class="fa fa-plus-circle fa-lg"></i> Pay Now!
                </button>
            </p>
        </div>
    </div>
</form>
```

When clicking the submit button the customer gets redirected to the checkout page that was published in your views directory.

So now we've redirected the customer. The customer did some actions there (hopefully he or she paid the order) and now gets redirected back either the success or failure route.

Vpay would send some data to the webhook which set up earlier. This webhook would contain some some data as payload. This webhook data payload will be sent along with a JWT token containing {secret: your_secret_key} as its payload. 

We must validate if the redirect to our site is a valid request (we don't want imposters to wrongfully place non-paid order).

The webhook url `payment/webhook/vpay` is secured using a middleware which decodes the JWT token and compare its secret payload with the secret-key added in the env file, on success it dispatches a job to queue, check the Job in your App/Job and handle the details


## Contributing

Please feel free to fork this package and contribute by submitting a pull request to enhance the functionalities.


Why not star the github repo? I'd love the attention! Why not share the link for this repository on Twitter? Spread the word!

Don't forget to [follow me on twitter](https://twitter.com/hen8y)!

Thanks!
Ogbonna Henry (hen8y).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
