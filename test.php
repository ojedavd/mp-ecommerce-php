<?php

require __DIR__ .  '/vendor/autoload.php';

MercadoPago\SDK::setAccessToken("APP_USR-6317427424180639-042414-47e969706991d3a442922b0702a0da44-469485398");

$merchant_order = null;


$payment = MercadoPago\Payment::find_by_id(6289645642);

$merchant_order = MercadoPago\MerchantOrder::find_by_id($payment->order->id);

$merchant_order = MercadoPago\MerchantOrder::find_by_id(1293090468);
   


echo "<pre>";
var_dump($merchant_order->payments[0]->id); die();

?>