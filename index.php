<?php
    $weather = "";
    $error = "";
    if(array_key_exists('city', $_GET)) {
        $city = str_replace(' ', '', $_GET['city']);
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
    if ($temperatureElements->length > 0) {
        $error = "No City found";
    }else{
        foreach ($temperatureElements as $element) {
            // Check if the class name contains 'temperature'
            if (strpos($element->getAttribute('class'), 'rbi') !== false) {
                // Output the temperature text
                $temperature = $element->nodeValue;
                $weather=$temperature;
                break;
            }
        }

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
    <body>

    <div class="container">
            <form method="get" action="">
                <div class="mb-3 form-group">
                  <label for="city" class="form-label">Enter a City</label>
                  <input type="text" class="form-control" name='city' id="city" placeholder="Eg. London, Tokyo" aria-describedby="emailHelp" value="<?php
                if(array_key_exists('city', $_GET)) {
                    echo $_GET['city'];
                }
                ?>">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>        
        <div id="weather">
            <?php
                if($weather) {
                    echo '<div class="alert alert-success" role="alert">'.$weather.'</div>';
                } else  if($error) {
                    echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
                }
            ?>
        </div>
    </div>
</head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>