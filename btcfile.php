<?php

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://api.btcfile.com/sell');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// For the sake of making this sample self-contained, this script uses itself as the uploaded file
curl_setopt($ch, CURLOPT_POSTFIELDS, array('file' => '@' . __FILE__, // The only mandatory field is having at least one file, everything below else is optional and has default values
										   'price' => 6000000, // Price of the content, expressed in satoshis
										   'miners_fee' => 600000, // Fee for the miners, expressed in satoshis. Minimum is 500000
										   'btcfile_fee' => 600000, // Fee for btcfile, expressed in satoshis. No minimum. Up to you if you want btcfile to survive or go bankrupt :)
										   'address' => '1vWb36Rzmqf9FfysuHCeAYs982iZwLJqm', // Address that will receive the value set in "price", should be the content owner
										   'api_address' => '184Z3JvhopcXJhjUPua5wAWjMheGDLa9wV', // Address that will receive 10% of the value set in "btcfile_fee"
										  ));

$data = json_decode(curl_exec($ch), true);

print_r($data);

// Update the price. Any field can be updated at any time, except files which will always be the set of files provided during the initial upload

curl_setopt($ch, CURLOPT_POSTFIELDS, array('update_url' => $data['update_url'], // You must pass the update_url for any update
										   'price' => 7000000,
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