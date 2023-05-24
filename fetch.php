<?php
    $apikey = "5d6ac39916b8ea4ee64f6b9bd1bed300";

    function currentWeather($name)
    {
        include 'connection.php';
        global $apikey;//making $apikey global to access it inside the function
        $url = 'https://api.openweathermap.org/data/2.5/weather?q=' . $name . '&appid=' . $apikey;//to call OpenWeatherMap API
        $response = @file_get_contents($url);//Storing response data
        $data = json_decode($response, true);//decoding response data from JSON to an array
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
            $id = $fdata['id']; //getting value of id from array
            if($id==null){
                echo "<script>alert('Invalid city/ API not working')</script>";
                break;
            }
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
                if ($name === 'Leeds') {
                    echo "<script>
                            localStorage.setItem('PastD$i', '$response')
                        </script>";
                } else {
                    echo "<script>
                            localStorage.setItem('Past$name$i','$response')
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
    function call($name)
    {include 'connection.php';
        $query = "SELECT cityname FROM weathertable";
        $sql = mysqli_query($con, $query);
        $found = false;
        while ($row = mysqli_fetch_assoc($sql)) {   
            if ($name == $row['cityname']) {
                $found = true;
                break;
            }
        }
        if ($found) {
            echo "<script> alert('Already in the database') </script>";
        } else {
            currentWeather($name);
            weatherbycity($name);
        }
    }

    if (isset($_POST['submit'])) {//check if the city is submitted
        $name = $_POST['nameInput'];//keeping the variable into blank 
        call($name);//getting data from the form
    } else {
        $name = 'Leeds';
        call($name);
    }
    ?>