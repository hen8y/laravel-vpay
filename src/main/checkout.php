<!DOCTYPE html>
<head>   
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <script src="https://<?= $this->appStatus() == "live" ? "dropin" : "dropin-sandbox" ?>.vpay.africa/dropin/v1/initialise.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    
    <script>
        (() => {
            const options = {
                amount: <?= $data["amount"] ?>,
                currency: "NGN",
                email: "<?= $data["email"] ?>",
                transactionref: "<?= $data["transactionref"] ?>",

                domain: "<?= $this->appStatus() == "live" ? "dropin" : "sandbox" ?>",
                key: "<?= $this->getPublicId() ?>",
                customer_logo: "<?= $this->customerLogo() ?>",
                customer_service_channel: "<?= $this->customerChannel() ?>",
                txn_charge: "<?= $this->transactionCharge() ?>",
                txn_charge_type: "<?= $this->transactionType() ?>",

                // onSuccess function
                onSuccess: function(response) {
                    const csrfToken = "<?= csrf_token() ?>";
                    const postData = {
                        status: 'success',
                        amount: <?= $data["amount"] ?>,
                        transactionref: "<?= $data["transactionref"] ?>",
                        email: "<?= $data["email"] ?>",
                    };
                    const form = $('<form>', {
                        action: '/payment/callback',
                        method: 'POST'
                    });
                    $('<input>').attr({
                        type: 'hidden',
                        name: '_token',
                        value: csrfToken
                    }).appendTo(form);
                    Object.keys(postData).forEach(function(key) {
                        $('<input>').attr({
                            type: 'hidden',
                            name: key,
                            value: postData[key]
                        }).appendTo(form);
                    });
                    form.appendTo('body').submit();
                },



                onExit: function(response) {
                    if (response.code == '09'){
                        const csrfToken = "<?= csrf_token() ?>";
                        const postData = {
                            status: 'failed',
                            amount: <?= $data["amount"] ?>,
                            transactionref: "<?= $data["transactionref"] ?>",
                            email: "<?= $data["email"] ?>",
                        };
                        const form = $('<form>', {
                            action: '/payment/callback',
                            method: 'POST'
                        });
                        $('<input>').attr({
                            type: 'hidden',
                            name: '_token',
                            value: csrfToken
                        }).appendTo(form);
                        Object.keys(postData).forEach(function(key) {
                            $('<input>').attr({
                                type: 'hidden',
                                name: key,
                                value: postData[key]
                            }).appendTo(form);
                        });
                        form.appendTo('body').submit();
                    }else{
                        console.log('Payment was cancelled');
                        window.history.back(); 
                    }
                }

            };
            if(window.VPayDropin){
                const {open, exit} = VPayDropin.create(options);
                open();                    
            }                
        })();
    </script>
</body>
</html>
