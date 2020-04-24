<?php
$myfile = fopen("log.txt", "w") or die("Unable to open file!");

MercadoPago\SDK::setAccessToken("APP_USR-8196777983571350-031822-2c462f0d08deb2f0b12e1b343176a42c-469485398");

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

$txt = $_POST["type"]."\nCreado.\n".$_POST["id"];
fwrite($myfile, $txt);
fclose($myfile);
?>