<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Phone Number Validation Rule
    |--------------------------------------------------------------------------
    |
    | This rule will be used in the validation of forms and for the phone number field
    |
    */

    'validate' => 'digits:11',

    /*
    |--------------------------------------------------------------------------
    | Token : min and max value
    |--------------------------------------------------------------------------
    |
    | Minimum and maximum acceptable amount for making tokens
    |
    */

    'token'     =>[
        'min'   => 100000 ,
        'max'   => 999999
    ],


    /*
    |--------------------------------------------------------------------------
    | My custom channel
    |--------------------------------------------------------------------------
    |
    | I use this channel to send SMS . It is better for you to use your own channel.
    | If your SMS service is similar to mine, you can use the same channel
    | by completing the following information.
    |
    */

    'channels' =>   [
        'sms'   => [
            'url'           => 'https://RestfulSms.com/api/',
            'secret'        => env('SMSIR_SECRET'),
            'apikey'        => env('SMSIR_APIKEY'),
            'verifyPattern' => env('SMSIR_PATTERN')
        ]
    ],

];
