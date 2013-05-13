<?php

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://api.btcfile.com/sell');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// For the sake of making this sample self-contained, this script uses itself as the uploaded file
curl_setopt($ch, CURLOPT_POSTFIELDS, array('file' => '@' . __FILE__, // The only mandatory field is having at least one file, everything below else is optional and has default values
										   'price' => 0.1, // Price of the content, expressed in BTC by default. Adding a currency parameter allows to set the price in a different currency
										   'address' => '1vWb36Rzmqf9FfysuHCeAYs982iZwLJqm', // Address that will receive the value set in "price", should be the content owner
										  ));

$data = json_decode(curl_exec($ch), true);

print_r($data);

// Update the price. Any field can be updated at any time, except files which will always be the set of files provided during the initial upload

curl_setopt($ch, CURLOPT_POSTFIELDS, array('update_url' => $data['update_url'], // You must pass the update_url for any update
										   'price' => 5,
										   'currency' => 'USD', // Changing price + currency. If only the currency field is updated, the existing price is converted.
										   ));

$data2 = json_decode(curl_exec($ch), true);

print_r($data2);

// If you want to read the information without updating anything, just pass the update_url

curl_setopt($ch, CURLOPT_POSTFIELDS, array('update_url' => $data['update_url'], // You must pass the update_url when you just want to read data
										  ));

$data3 = json_decode(curl_exec($ch), true);

print_r($data3);

// To get an address to pay to and price information

curl_setopt($ch, CURLOPT_URL, 'http://api.btcfile.com/buy');
curl_setopt($ch, CURLOPT_POSTFIELDS, array('download_url' => $data['download_url'], // You must pass the download_url when you want to obtain a new address
										  ));

$data4 = json_decode(curl_exec($ch), true);

print_r($data4);

/* To check if full payment has been sent to that address
   If fully paid, the disposable download link will be returned in the JSON in the "paid_download_url" field, until it's used by the buyer
   Please note that the paid download url can only be used once and can't reissued. */

curl_setopt($ch, CURLOPT_POSTFIELDS, array('address' => $data4['address'], // You must pass the address to check the paid state
										  ));

$data5 = json_decode(curl_exec($ch), true);

print_r($data5);

?>