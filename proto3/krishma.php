<!DOCTYPE html>
<html>

<head>
    <title>Weather Forecast App</title>
    <link rel="stylesheet" href="2332267_AashutoshDahal.css">
</head>

<body>
    <div class="container">
        <h1>Weather Forecast</h1>
        <!--Allows a user to search for a particular city's weather data-->
        <form method="post" id="form">
            <div class="search-box">
                <input type="text" name="location" id="city" placeholder="Enter city name" method="post">
                <button type="submit" name="submit">Search</button>
            </div>
    </form>

        <div class="weather-body">
        <div class="weather-body">
                    <h3 id="city-name"></h3>
                    <p id="description"></p>
                    <p id="temp"></p>
                    <p id="humidity"></p>
                    <p id="wind"></p>
                    <p id="pressure"></p>
                    <p id="time"></p>
                </div>
        <!--Fetches weather data for the current day from a MySQL database-->

            <table id="weathertable"><!--Creates a table to display weather data-->
                <tr>
                    <th>Name of city</th>
                    <th>Date of retrieval</th>
                    <th>Temperature</th>
                    <th>Description</th>
                    <th>Humidity</th>
                    <th>Wind</th>
                    <th>Pressure</th>
                </tr>
                
        </div>
    </div>
    <?php
    $apikey = "5d6ac39916b8ea4ee64f6b9bd1bed300";

    function currentWeather($name)
    {
        include 'connection.php';
        global $apikey;//making $apikey global to access it inside the function
        $url = 'https://api.openweathermap.org/data/2.5/weather?q=' . $name . '&appid=' . $apikey;//to call OpenWeatherMap API
        $response = file_get_contents($url);//Storing response data
        $data = json_decode($response, true);//decoding response data from JSON to an array
        if (isset($data) && !empty($data)) {
        $temperature = $data['main']['temp'];//retrieving data from $data array and storing them
        $humidity = $data['main']['humidity'];
        $pressure = $data['main']['pressure'];
        $description = $data['weather'][0]['main'];
        $time = $data['dt'];
        $date = date('Y-m-d', $time);
        $wind = $data['wind']['speed'];
        //Inserting values into MySQL database
        $query = "INSERT INTO weatherdata (nameofcity,dateofretrieval,temperature,description,humidity,wind,pressure) VALUES ('$name', '$date', '$temperature','$description', '$humidity', '$wind', '$pressure')";
        $sql = mysqli_query($con, $query);
        if ($name === 'Leeds') {
            echo "<script>
                    localStorage.setItem('CurrentD', '$response')
                </script>";
        } else {
            echo "<script>
                    localStorage.setItem('Current$name','$response')
                </script>";
        }
    }else {
        // handle the case where no data is returned or $data[0] does not exist
        return null;
    }
}

    function getWeatherByCityName($name){
        global $apikey;//declaring the variable global


        include 'connection.php';
        for ($i = 0; $i <= 7; $i++) {//loop that executes eight times
            $time = time() - $i * 86400;//calculating timestamp
            $cnt = 1;
            $curl = 'https://api.openweathermap.org/data/2.5/weather?q=' . $name . '&appid=' . $apikey;
            $cresponse = file_get_contents($curl);//retrieving the contents of url and storing it
            $cdata = json_decode($cresponse, true);//decoding json datainto PHP array
            $id = $cdata['id'];
            $url = 'https://history.openweathermap.org/data/2.5/history/city?id=' . $id . '&type=hour&start=' . $time . '&cnt=' . $cnt . '&appid=' . $apikey;//constructing url to retrieve the current weather data
            $response = file_get_contents($url);

            $data = json_decode($response, true);

             // Store the data in the database
            try {
                if (isset($data['list']) && !empty($data['list'])) {//checking if it is set
                    $item = $data['list'][0];//retrieving the first item
                    $timestamp = $item['dt'];
                    $date = date('Y-m-d', $timestamp);
                    $description = $item['weather'][0]['description'];
                    $temperature = $item['main']['temp'];
                    $humidity = $item['main']['humidity'];
                    $wind = $item['wind']['speed'];
                    $pressure = $item['main']['pressure'];
                    $query = "INSERT INTO weatherdata (nameofcity,dateofretrieval,temperature,`description`,humidity,wind,pressure) VALUES ('$name', '$date', '$temperature','$description', '$humidity', '$wind', '$pressure')";
                    $sql = mysqli_query($con, $query);
                }
                if ($name === 'Leeds') {
                    echo "<script>
                            localStorage.setItem('PastD$i', '$response')
                        </script>";
                } else {
                    echo "<script>
                            localStorage.setItem('Past$name-$i','$response')
                        </script>";
                }
            }
            //catching exception
             catch (Exception $error) {
                echo "Exception caught: " . $error->getMessage();
            }
            //catching errors
            catch (Error $error) {
                echo "Error caught: " . $error->getMessage();
            }
        }
    }
    //call the function to fetch
    function calling($location)
    {include 'connection.php';
        $query = "SELECT nameofcity FROM weatherdata";
        $sql = mysqli_query($con, $query);
        $found = false;
        while ($row = mysqli_fetch_assoc($sql)) {
            if ($location == $row['nameofcity']) {
                $found = true;
                break;
            }
        }
        if ($found) {
            echo "<script> alert('Already in the database') </script>";
        } else {
            if (currentWeather($location) == null) {
                echo "<script> alert('API not working')</script>";
            }
            currentWeather($location);
            getWeatherByCityName($location);
        }
        
    }
    
    if (isset($_POST['submit'])) {//check if the city is submitted
        $location = $_POST['location'];//keeping the variable into blank 
        calling($location);//getting data from the form
    } else {
        $location = 'Leeds';
        calling($location);
    }
    ?>
<script>
            function currentlocal() {
                const cd = JSON.parse(localStorage.getItem("CurrentD"));
                const cityName = cd.name;
                const description = cd.weather.decription;
                const temp = (cd.main.temp - 273.15).toFixed(2);
                const humidity = cd.main.temp;
                const windSpeed = cd.wind.speed;
                const pressure = cd.main.pressure;
                const timestamp = cd.dt;
                const date = new Date(timestamp * 1000);
                const options = {
                    weekday: "long",
                    month: "long",
                    day: "numeric",
                    hour: "numeric",
                    minute: "numeric",
                };
                const formattedTime = date.toLocaleString("en-US", options);

                document.getElementById("city-name").textContent = cityName;
                document.getElementById("description").textContent = description;
                document.getElementById("temp").textContent = `Temperature: ${temp} °C`;
                document.getElementById(
                    "humidity"
                ).textContent = `Humidity: ${humidity}%`;
                document.getElementById("wind").textContent = `Wind: ${windSpeed} m/s`;
                document.getElementById(
                    "pressure"
                ).textContent = `Pressure: ${pressure} Pa`;
                document.getElementById(
                    "time"
                ).textContent = `Updated at ${formattedTime}`;
            }
            

            function pastlocal() {
  const table = document.getElementById("weathertable");

  for (let i = 1; i <= 7; i++) {
    const data = JSON.parse(localStorage.getItem(`PastD${i}`));
    const pd = data.list[0];
    const cityName = "Yokohama";
    const description = pd.weather[0].description;
    const temp = (pd.main.temp - 273.15).toFixed(2);
    const humidity = pd.main.humidity;
    const windSpeed = pd.wind.speed;
    const pressure = pd.main.pressure;

    const timestamp = pd.dt * 1000;
    const date = new Date(timestamp);
    const options = {
      weekday: "long",
      month: "long",
      day: "numeric",
      hour: "numeric",
      minute: "numeric",
    };
    const formattedTime = date.toLocaleString("en-US", options);

    const row = table.insertRow();
    const nameCell = row.insertCell();
    const dateCell = row.insertCell();
    const tempCell = row.insertCell();
    const descCell = row.insertCell();
    const humCell = row.insertCell();
    const windCell = row.insertCell();
    const pressCell = row.insertCell();

    
    nameCell.textContent = cityName;
    dateCell.textContent = formattedTime;
    
    tempCell.textContent = `${temp} °C`;
    descCell.textContent = description;
    humCell.textContent = `${humidity}%`;
    windCell.textContent = `${windSpeed} m/s`;
    pressCell.textContent = `${pressure} hPa`;
  }
}

      window.onload = function () {
        currentlocal();
        pastlocal();
      };
        </script>
</body>
</html>