<?php
    $city="patna";
    $url = "https://www.timeanddate.com/weather/?query=" . $city;

    // Get headers for the specified URL
    $file_headers = @get_headers($url);

    // Check if headers were retrieved successfully
    if ($file_headers === false) {
        $error = "Could not connect to the weather service.";
    } elseif (isset($file_headers[0]) && $file_headers[0] == 'HTTP/1.1 404 Not Found') {
        $error = "That city could not be found.";
    } else {
        // Fetch the weather forecast page
        $forecastPage = @file_get_contents($url);
    }
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($forecastPage);
    libxml_clear_errors();

    $temperatureElements = $dom->getElementsByTagName('td');

    $xpath = new DOMXPath($dom);

    foreach ($temperatureElements as $element) {
        // Check if the class name contains 'temperature'
        if (strpos($element->getAttribute('class'), 'rbi') !== false) {
            // Output the temperature text
            $temperature = $element->nodeValue;
            echo "Temperature: " . trim($element->nodeValue) . "\n";
            $weather=$temperature;
            break;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Scrapper</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <div class="container">
            <form method="GET" action="">
                <div class="mb-3 form-group">
                  <label for="city" class="form-label">Enter a City</label>
                  <input type="text" class="form-control" id="city" placeholder="Eg. London, Tokyo" aria-describedby="emailHelp">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>        
        
    </div>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>