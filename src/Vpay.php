<?php
namespace Hen8y\Vpay;




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


    
    /**
     * Get Public Id from Vpay Config File
     */
    public function getPublicId(){

        return $this->public_id = config("vpay.public_id");
    }



    /**
     * Get Secret from Vpay Config File
     */
    public function getSecret(){

        return $this->secret_key = config("vpay.secret_key");
    }

    /**
     * Get the App status from Vpay config file
     */
    public function appStatus(){

        return $this->status = config("vpay.status");
    }

    /**
     * Get Customer Logo from Vpay Config File
     */
    public function customerLogo(){

        return $this->customer_logo = config("vpay.customer_logo");
    }


    /**
     * Get Customer Service Channel from Vpay Config File
     */
    public function customerChannel(){
        
        $this->customer_service_channel = config("vpay.customer_service_channel");
    }

    /**
     * Get Transaction Type from Vpay Config File
     */
    public function transactionType(){

        return $this->txn_charge_type = config("vpay.txn_charge_type");
    }

    /**
     * Get Transaction Charge from Vpay Config File
     */
    public function transactionCharge(){

        return $this->txn_charge = config("vpay.txn_charge");
    }


    /**
     * Displays the checkout page
     *
     * @param  array $data
     */
    public function handleCheckout($data)
    {
       if($data){
            ob_start();
        
            include(__DIR__ . '/main/checkout.php');
        
            $html = ob_get_clean(); 
        
            return $html;
       }else{
            http_response_code(419);
       }
    }
    



}