<?php
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://open-weather13.p.rapidapi.com/city/zomba/EN",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "x-rapidapi-host: open-weather13.p.rapidapi.com",
        "x-rapidapi-key: ebc49c9d63mshce19a6df52c6843p12f37bjsnc6b2da25ad19"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo json_encode(["error" => "cURL Error #:" . $err]);
} else {
    $weather = json_decode($response, true);
    $temp = $weather['main']['temp'];
    $humidity = $weather['main']['humidity'];
    $suggestion = "";

    if ($temp > 30) {
        $suggestion = "It's hot outside. Make sure to water your crops frequently.";
    } elseif ($temp < 0) {
        $suggestion = "It's freezing outside. Protect your crops from the cold.";
    } else {
        $suggestion = "The weather is moderate. Your crops should be doing well.";
    }

    echo json_encode([
        'temp' => $temp,
        'humidity' => $humidity,
        'suggestion' => $suggestion
    ]);
}
?>
