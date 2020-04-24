<?php

require __DIR__ .  '/vendor/autoload.php';

MercadoPago\SDK::setAccessToken("APP_USR-6317427424180639-042414-47e969706991d3a442922b0702a0da44-469485398");

$merchant_order = null;

switch($_GET["topic"]) {

    case "payment":
        $payment = MercadoPago\Payment::find_by_id($_GET["id"]);
        // Get the payment and the corresponding merchant_order reported by the IPN.
        $merchant_order = MercadoPago\MerchantOrder::find_by_id($payment->order->id);

        $myfile = fopen("ordenid.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $merchant_order->id);
        fclose($myfile);

        $myfile = fopen("pagoid.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $payment->id);
        fclose($myfile);

        break;

    case "merchant_order":
        $merchant_order = MercadoPago\MerchantOrder::find_by_id($_GET["id"]);
                
        $myfile = fopen("ordenid.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $merchant_order->id);
        fclose($myfile);
        
        $myfile = fopen("pagoid.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $merchant_order->payments[0]->id);
        fclose($myfile);

        $myfile = fopen("success.php", "w") or die("Unable to open file!");
        $txt = "<?php"; 
        $txt = $txt."\necho '<h1>Pago: ".$merchant_order->payments[0]->id."</h1>'; 
                        \necho '<h2>Orden: ".$merchant_order->id."</h2>'; 
                        \necho '<h2>Orden: ".$merchant_order->id."</h2>'; 
                        \necho '<h2>Orden: ".$merchant_order->id."</h2>'; 
                        \necho '<a href=\"https://ojedavd-mp-commerce-php.herokuapp.com/\">Volver a la tienda</a>';
                \n?>";
        fwrite($myfile, $txt);
        fclose($myfile);

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


?>


