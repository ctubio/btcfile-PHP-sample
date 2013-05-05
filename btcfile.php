<?php

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://api.btcfile.dev/content');
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

curl_setopt($ch, CURLOPT_POSTFIELDS, array('url' => $data['download_url'], // Passing the update url also works here
										   'price' => 7000000,
										   ));

$data2 = json_decode(curl_exec($ch), true);

print_r($data2);

// If you want to read the information without updating anything, just pass either of the urls

curl_setopt($ch, CURLOPT_POSTFIELDS, array('url' => $data['update_url'], // Passing the download url also works here
										  ));

$data3 = json_decode(curl_exec($ch), true);

print_r($data3);

?>