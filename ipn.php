<?php
$myfile = fopen("log.txt", "w") or die("Unable to open file!");
$txt = "\nCreado.\n".$_GET["topic"];
fwrite($myfile, $txt);
fclose($myfile);

require __DIR__ .  '/vendor/autoload.php';

MercadoPago\SDK::setAccessToken("APP_USR-8196777983571350-031822-2c462f0d08deb2f0b12e1b343176a42c-469485398");

$merchant_order = null;

switch($_GET["topic"]) {
    case "payment":
        $payment = MercadoPago\Payment::find_by_id($_GET["id"]);
        // Get the payment and the corresponding merchant_order reported by the IPN.
        $merchant_order = MercadoPago\MerchantOrder::find_by_id($payment->order->id);
        break;
    case "merchant_order":
        $merchant_order = MercadoPago\MerchantOrder::find_by_id($_GET["id"]);
        break;
}

$paid_amount = 0;
foreach ($merchant_order->payments as $payment) {
    if ($payment['status'] == 'approved'){
        $paid_amount += $payment['transaction_amount'];
    }
}

// If the payment's transaction amount is equal (or bigger) than the merchant_order's amount you can release your items
if($paid_amount >= $merchant_order->total_amount){
    if (count($merchant_order->shipments)>0) { // The merchant_order has shipments
        if($merchant_order->shipments[0]->status == "ready_to_ship") {
            print_r("Totally paid. Print the label and release your item.");
        }
    } else { // The merchant_order don't has any shipments
        print_r("Totally paid. Release your item.");
    }
} else {
    print_r("Not paid yet. Do not release your item.");
}


switch($_POST["type"]) {
    case "payment":
        $payment = MercadoPago\Payment.find_by_id($_POST["id"]);
        break;
    case "plan":
        $plan = MercadoPago\Plan.find_by_id($_POST["id"]);
        break;
    case "subscription":
        $plan = MercadoPago\Subscription.find_by_id($_POST["id"]);
        break;
    case "invoice":
        $plan = MercadoPago\Invoice.find_by_id($_POST["id"]);
        break;
}


?>


