<?php
require_once("midtrans/Midtrans.php");

\Midtrans\Config::$serverKey = "SERVER_KEY_MU";

$params = [
  "transaction_details" => [
    "order_id" => rand(),
    "gross_amount" => 250000
  ]
];

$snapToken = \Midtrans\Snap::getSnapToken($params);

echo json_encode(["token" => $snapToken]);
?>
