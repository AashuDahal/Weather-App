<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="new.css">
    <title>Weather App</title>
</head>
<body>
    <div class="container">
        <h1>WEATHER FORECAST</h1>
        <div class="searchbar">
            <form id="form" action="" method=POST>
                <input type="text" id="forminput" placeholder="CITY NAME" name="forminput" size="65">
                <button type="submit" name="submit">SEARCH</button>
            </form>
        </div>
<?php
include 'connection.php'; //establishing connection to database
// Fetch weather data for the current day
if (isset($_POST['forminput'])) {
    $citysearch = $_POST['forminput'];
} else {
    $citysearch = 'Leeds';
};
$currentDate = date('Y-m-d');
$query = "SELECT * FROM weathertable WHERE cityname='$citysearch' AND citydate = '$currentDate'"; //sql query to fetch data for current day
$sql = mysqli_query($con, $query);
$result = mysqli_num_rows($sql);
if ($result) {
    $row = mysqli_fetch_array($sql); //fetching the different data
    $nameofcity = $row['cityname']; //name of the city
    $description = $row['weatherdescription']; //description of the weather
    $time = $row['citydate']; //date 
    $temperature = $row['temperature']; //temperature
    $humid = $row['humidity']; //humidity
    $windspeed = $row['windspeed']; //windspeed
    $pressure = $row['pressure'];//pressure

                echo '<h1 id="nameofcity" name="nameofcity">' . $nameofcity. '</h1>';
                echo '<h2 id="weatherdescription" name="weatherdescription">' . $description . '</h2>'.'<br>';
                echo '<h3 id="temperature" name="temperature">'.'Temperature: '. $temperature . ' Kelvin</h3>'.'<br>';
                echo '<h3 id="citydate" name="citydate">'.'Date: ' . $time . '</h3>'.'<br>';
                echo '<p><span id="humidity" name="humidity">'.'Humidity: ' . $humid . '%----'.'</span></p>';
                echo '<p><span id="windspeed" name="windspeed">'.'WindSpeed: ' . $windspeed . 'm/s----','</span></p>';
                echo '<p><span id="pressure" name="pressure">'.'Pressure: ' . $pressure . 'atm','</span></p>';
} else {
    echo "No data found for the day"; //if no data for current day error displayed
}
?>      
        <div class="weathertable"><!--creating a table for past 7 days data !-->
            <table>
                <tr>
                    <th>NAME</th>
                    <th>DESCRIPTION</th>
                    <th>DATE</th>
                    <th>TEMPERATURE(K)</th>
                    <th>HUMIDITY(%)</th>
                    <th>WINDSPEED(m/s)</th>
                    <th>PRESSURE(atm)</th>
                </tr>

                <?php
                include 'connection.php'; //establishing database connection
                if (isset($_POST['forminput'])) {
                    $citysearch = $_POST['forminput'];
                } else {
                    $citysearch = 'Leeds';
                }
                ;
                //fetching weather data from the database for past 7 days
                try {
                    $query = "SELECT DISTINCT cityname,weatherdescription,citydate,temperature,humidity,windspeed,pressure FROM weathertable WHERE cityname='$citysearch' ORDER BY citydate"; //query yo select all data from weathertable by citydate
                    $sql = mysqli_query($con, $query);
                    $result = mysqli_num_rows($sql); //getting the number of rows that is returned
                    if ($result) {
                        while ($row = mysqli_fetch_array($sql)) { //looping to display the data through table
                            echo "<tr>";
                            echo "<td>" . $row['cityname'] . "</td>";
                            echo "<td>" . $row['weatherdescription'] . "</td>";
                            echo "<td>" . $row['citydate'] . "</td>";
                            echo "<td>" . $row['temperature'] . "</td>";
                            echo "<td>" . $row['humidity'] . "</td>";
                            echo "<td>" . $row['windspeed'] . "</td>";
                            echo "<td>" . $row['pressure'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "SORRY NO DATA FOUND IN DATABASE!!!";
                    }
                } catch (Error $error) { //catching any error
                    echo "Error: " . $e->getMessage();
                }
                ?>
        </div>
    </div>
    <?php
    $apikey = "5d6ac39916b8ea4ee64f6b9bd1bed300"; //api for openweathermap
    function currentweather($name)
    { //fetching current weather
        include "connection.php"; //establishing connection
        global $apikey;
        $url = 'https://api.openweathermap.org/data/2.5/weather?q=' . $name . '&appid=' . $apikey; //api url for data
        $response = @file_get_contents($url); //fetches the data from api
        $data = json_decode($response, true); //decoding json data into array
        //getting different data required
        if (isset($data)) {
            $descrip = $data['weather'][0]['main']; //description of the weather
            $time = $data['dt']; //date and time
            $date = date('Y-m-d', $time); //unixtimestamp to date
            $temp = $data['main']['temp']; //temperature
            $humid = $data['main']['humidity']; //humidity
            $wind = $data['wind']['speed']; //windspeed
            $press = $data['main']['pressure']; //pressure
            $query = "INSERT INTO weathertable(cityname,weatherdescription,citydate,temperature,humidity,windspeed,pressure)VALUES('$name','$descrip','$date','$temp','$humid','$wind','$press')"; //query to insert data into database
            $sql = mysqli_query($con, $query);
        } else {
            return null;
        }
    }
    //function to get weather of past 7 days
    function weatherbycity($name)
    {
        global $apikey; //accessing apikey 
        include 'connection.php'; //establishing connection
        for ($i = 0; $i <= 7; $i++) { //looping through past 7 days
            $time = time() - $i * 86400; //getting the timestamp
            $count = 1; //count the data required
            $furl = 'https://api.openweathermap.org/data/2.5/weather?q=' . $name . '&appid=' . $apikey; //api url
            $fresponse = @file_get_contents($furl); //fetching data
            $fdata = json_decode($fresponse, true);
            $id = $fdata['id']; //getting id into city
            $url = 'https://history.openweathermap.org/data/2.5/history/city?id=' . $id . '&type=hour&start=' . $time . '&cnt=' . $count . '&appid=' . $apikey;
            $response = @file_get_contents($url);
            $data = json_decode($response, true);
        try{
            if (isset($data['list']) && !empty($data['list'])) { //checking if the data exsists
                $item = $data['list'][0]; //getting 1st item of list
                $timestamp = $item['dt'];
                $date = date('Y-m-d', $timestamp);
                $descrip = $item['weather'][0]['description'];
                $temp = $item['main']['temp'];
                $humid = $item['main']['humidity'];
                $wind = $item['wind']['speed'];
                $press = $item['main']['pressure'];
                $query = "INSERT INTO weathertable(cityname,weatherdescription,citydate,temperature,humidity,windspeed,pressure)VALUES('$name','$descrip','$date','$temp','$humid','$wind','$press')";
                $sql = mysqli_query($con, $query);
            } 
            } catch (Exception $e) {
                echo "Exception caught: " . $e->getMessage();
            } catch (Error $e) {
                echo "Error caught: " . $e->getMessage();
            }
        }
    }
    function call($name)
    {
            currentweather($name); //calling the function
            weatherbycity($name);
    }
    if (isset($_POST['submit'])) { //using post method
        $name = $_POST['forminput']; //uses the entered city for data retrieval
        call($name);
    } else {
        $name = 'Leeds'; //else uses the default city
        call($name);
    }
    ?>
</body>
</html>