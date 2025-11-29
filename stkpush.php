<?php

// ---------------------- ACCESS TOKEN ----------------------
$consumerKey    = "YR8fEvPF0indL8SDCu5rQl9zdV6kMR3KnJTGODlbIZGf6P8VYY";
$consumerSecret = "W7kK20iLykD3pKiuGkN500bNJNZLLlqD6TDMcmYnCVU0ccgqBMQS0mAfmZbpA1fr";

$credentials = base64_encode($consumerKey . ":" . $consumerSecret);

$token_url = "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $token_url);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic " . $credentials));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);
$response = json_decode($response);

$access_token = $response->access_token;

// ---------------------- STK PUSH REQUEST ----------------------
$phone  = $_POST["phone"];
$amount = $_POST["amount"];

$BusinessShortCode = "174379"; // test paybill
$Passkey           = "YOUR_PASSKEY";
$Timestamp         = date("YmdHis");
$Password          = base64_encode($BusinessShortCode . $Passkey . $Timestamp);

$stk_url = "https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest";

$post_data = [
    "BusinessShortCode" => $BusinessShortCode,
    "Password"          => $Password,
    "Timestamp"         => $Timestamp,
    "TransactionType"   => "CustomerPayBillOnline",
    "Amount"            => $amount,
    "PartyA"            => $phone,
    "PartyB"            => $BusinessShortCode,
    "PhoneNumber"       => $phone,
    "CallBackURL"       => "https://yourdomain.com/callback.php",
    "AccountReference"  => "Online Payment",
    "TransactionDesc"   => "Payment"
];

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $stk_url);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "Authorization: Bearer " . $access_token
));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data));

$response = curl_exec($curl);

echo $response;
