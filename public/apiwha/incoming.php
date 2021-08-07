<?php
$url = "https://erp.theluxuryunlimited.com/whatsapp/incoming";
$content = $_POST['data'];
//$content = '{"request":"{"event":"INBOX","from":"918879948245","to":"918291920455","text":"Let me know if u get this"}","response":"","status":200}';

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER,
        array("Content-type: application/json"));
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

$response = curl_exec($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$params = [
  'request' => $content,
  'response' => $response,
  'status' => $status
];
file_put_contents(__DIR__."/log.txt", json_encode($params));
file_put_contents(__DIR__."/status.txt", json_encode($status));
file_put_contents(__DIR__."/response.txt", json_encode($response));

curl_close($curl);
