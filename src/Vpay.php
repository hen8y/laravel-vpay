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

    public function __construct()
    {
        $this->public_id = config("vpay.public_id");
        $this->secret_key = config("vpay.secret_key");
        $this->status = config("vpay.status");
        $this->customer_logo = config("vpay.customer_logo");
        $this->customer_service_channel = config("vpay.customer_service_channel");
        $this->txn_charge_type = config("vpay.txn_charge_type");
        $this->txn_charge = config("vpay.txn_charge");
    }

    public function handleCheckout(array $data): string
    {
        if ($data) {
            ob_start();
            include __DIR__ . '/main/checkout.php';

            $html = ob_get_clean();

            return $html;
        } else {
            http_response_code(419);
            throw new \Exception("Invalid Data");
        }
    }
}
