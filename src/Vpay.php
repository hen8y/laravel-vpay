<?php
namespace Hen8y\Vpay;

use Illuminate\Support\Facades\Config;


class Vpay
{

    protected $public_id;

    protected $secret_key;

    protected $status;

    public $url;

    protected $customer_logo;

    protected $customer_service_channel;

    protected $txn_charge_type;

    protected $txn_charge;


    public function __construct(){
        $this->getPublicId();
        $this->getSecret();
        $this->appStatus();
        $this->customerLogo();
        $this->customerChannel();
        $this->transactionType();
        $this->transactionCharge();
    }


    /**
     * Get Public Id from Vpay Config File
     */
    public function getPublicId(){

        $this->public_id = Config::get("vpay.public_id");
    }



    /**
     * Get Secret from Vpay Config File
     */
    public function getSecret(){

        $this->secret_key = Config::get("vpay.secret_key");
    }

    /**
     * Get the App status from Vpay config file
     */
    public function appStatus(){

        $this->status = Config::get("vpay.status");
    }

    /**
     * Get Customer Logo from Vpay Config File
     */
    public function customerLogo(){

        $this->customer_logo = Config::get("vpay.customer_logo");
    }


    /**
     * Get Customer Service Channel from Vpay Config File
     */
    public function customerChannel(){
        
        $this->customer_service_channel = Config::get("vpay.customer_service_channel");
    }

    /**
     * Get Transaction Type from Vpay Config File
     */
    public function transactionType(){

        $this->txn_charge_type = Config::get("vpay.txn_charge_type");
    }

    /**
     * Get Transaction Charge from Vpay Config File
     */
    public function transactionCharge(){

        $this->txn_charge = Config::get("vpay.txn_charge");
    }

    public function redirectNow()
    {
        return redirect($this->url);
    }

     /**
     * Redirects to the checkout page with details
     */

    public static function handleCheckout($data)
    {
        
        return view("vpay.checkout",["data"=>$data]);
    }

}
