<!DOCTYPE html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <script src="https://<?= $this->status == "live" ? "dropin" : "dropin-sandbox" ?>.vpay.africa/dropin/v1/initialise.js"></script>
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

                domain: "<?= $this->status == "live" ? "dropin" : "sandbox" ?>",
                key: "<?= $this->public_id ?>",
                customer_logo: "<?= $this->customer_logo ?>",
                customer_service_channel: "<?= $this->customer_service_channel ?>",
                txn_charge: "<?= $this->txn_charge ?>",
                txn_charge_type: "<?= $this->txn_charge_type ?>",

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
                    if (response.code == '09') {
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
                    } else {
                        console.log('Payment was cancelled');
                        window.history.back();
                    }
                }

            };
            if (window.VPayDropin) {
                const {
                    open,
                    exit
                } = VPayDropin.create(options);
                open();
            }
        })();
    </script>
</body>

</html>