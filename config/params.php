<?php

return [
    'adminEmail' => 'admin@example.com',
    'url' => 'https://payments.airpay.co.in/pay/index.php',
    'adminEmail'=>'mail@partnerpay.co.in',
    'noreplyEmail'=>'noreply@partnerpay.co.in',
    'paymentEmail'=>'payments@partnerpay.co.in',
    'Name'=>'partnerpay16102018',
    'DbUsername'=>'root',
    'DbPassword'=>'123456',
    'sms' =>[
    	'url' => 'http://luna.a2wi.co.in:7501/failsafe/HttpLink?',
    	'data' => 'aid=633609&pin=welcome12&mnumber=91{{{phone_number}}}&signature={{{signature}}}&message={{{message}}}',
        'message' => 'Dear Customer, Please click on the following link to make the payment for your transaction reference id {{{invoice_number}}} ({{{invoice_guest_url}}}). {{{partner_name}}}'
    ]
];
