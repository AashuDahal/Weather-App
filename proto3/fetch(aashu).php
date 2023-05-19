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