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
    echo "cURL Error #:" . $err;
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Information</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .weather-card {
            background: url('https://example.com/clouds-background.jpg') no-repeat center center;
            background-size: cover;
            color: white;
        }
        .weather-card .card-body {
            background: rgba(0, 0, 0, 0.5); /* Add semi-transparent background to enhance text readability */
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card weather-card">
                <div class="card-body">
                    <h5 class="card-title">Weather Information for Zomba</h5>
                    <p class="card-text">Temperature: <?= $temp ?>°C</p>
                    <p class="card-text">Humidity: <?= $humidity ?>%</p>
                    <p class="card-text font-weight-bold">Suggestion: <?= $suggestion ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
