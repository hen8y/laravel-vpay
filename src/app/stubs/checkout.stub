<!DOCTYPE html>
    <head>   
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checkout Page</title>
        <script src="https://{{ config("vpay.status") == "live"?"dropin":"dropin-sandbox" }}.vpay.africa/dropin/v1/initialise.js"></script>
    </head>
    
    <body>
        
        <script>
            (() => {
                const options = {
                    amount: {{ $data["amount"] }},
                    currency: "{{ $data['currency'] }}",
                    email: "{{ $data['email'] }}",
                    transactionref: "{{ $data['transactionref'] }}",

                    domain: "{{ config('vpay.status') == 'live'?'dropin':'sandbox' }}",
                    key: "{{ config('vpay.public_id') }}",
                    customer_logo:"{{ config('vpay.customer_logo') }}",
                    customer_service_channel:"{{ config('vpay.customer_service_channel') }}",
                    txn_charge: "{{ config('vpay.txn_charge') }}",
                    txn_charge_type: "{{ config('vpay.txn_charge_type') }}",

                    onSuccess: function(response) {
                        window.location.href = "{{ route('vpay.payment.success') }}"
                    },
                    onExit: function(response) {
                        window.location.href = "{{ route('vpay.payment.fail') }}"
                    }
                }
                if(window.VPayDropin){
                    const {open, exit} = VPayDropin.create(options);
                    open();                    
                }                
            })();
        </script>
    </body>
</html>