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
    "status"=>env("VPAY_STATUS","sandbox"),

    /*
    |--------------------------------------------------------------------------
    | Public Key 
    |--------------------------------------------------------------------------
    |
    | Enter the public key gotten from vpay website 
    |
    */
    "public_id"=> env("VPAY_PUBLICID",""),

    /*
    |--------------------------------------------------------------------------
    | Public Key 
    |--------------------------------------------------------------------------
    |
    | Enter the secret key gotten from vpay website
    |
    */
    "secret_key"=> env("VPAY_SECRET",""),


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
    | Customer Care Email Address 
    |--------------------------------------------------------------------------
    |
    | Here specify the customer service & support channels of your business 
    | e.g. Tel: +2348030070000, Email: support@yourorg.com
    |
    */
    "customer_service_channel"=> env("MERCHANT_EMAIL",""),

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