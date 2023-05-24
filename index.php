<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <link rel="stylesheet" href="2332267_G10_AashutoshDahal.css">
</head>
<body>
        <div class="container">
        <div class="subcontainer">
        <div class="header">
            <h2>Weather App</h2><br>
        </div>
            <form method="post" id="form">
        <div class="search-bar">
            <input type="text" name="nameInput" id="city" placeholder="ENTER CITYNAME" method="post"><br><br>
            <button type="submit" name="submit">Search city</button><!--for searching and storing the data-->
            <button type="submit" id="submit-Button">Display Weather</button><!--for displaying the data-->
        </div>
            </form>
        <div class="weather-body">
        <div class="weather-body">
                    <h2 id="city-name"></h2><br>
                    <h3 id="description"></h3><br>
                    <h3 id="time"></h3>
                    <p id="temperature"></p>
                    <p id="humidity"></p>
                    <p id="windspeed"></p>
                    <p id="pressure"></p>
        </div>
                <!--table rows for weather data -->
            <table id="pasttable">
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Temperature(Celsius)</th>
                    <th>Humidity(%)</th>
                    <th>Wind(m/s)</th>
                    <th>Pressure(hPa)</th>
                    <th>Date</th>
                </tr>
        </div>
        </div>
<?php
    include 'fetch.php';//contains the data retrieval PHP script
?>
    <script>
        function presentweatherdata(city) {//city as a parameter
                const presentdata = JSON.parse(localStorage.getItem(`Current${city}`));//retries data from local storage and parses into JSON //stores it into cd variable
                if (presentdata === null) {//checks if data is retrieved from the localstorage
                  alert("No data to display");//alerts the user if no data available
                  }else{
                    console.log("Data retrieved from Local Storage");//if data is available console logs this message
                }
                let cityName;
                if (city == 'D') {//if cityname is 'D'
                    cityName = 'Leeds';//sets it to Leeds(default city)
                } else {
                    cityName = presentdata.name;//retrieves from presentdata
                }
                const description = presentdata.weather[0].description;//retrives weather description from retrieved data and assigns it into description
                const temper = (presentdata.main.temp - 273.15).toFixed(2);//retrieves temperature and fixes into two decimal point
                const humidity = presentdata.main.temp;
                const windSpeed = presentdata.wind.speed;
                const pressure = presentdata.main.pressure;
                const timestamp = presentdata.dt;
                const date = new Date(timestamp * 1000);//creates a new data object from the timestamp value
                const options = {
                    weekday: "long",
                    month: "long",
                    day: "numeric",
                };
                const formattedTime = date.toLocaleString("en-US", options);//formats the time to human redable format
                //uses HTML elements to display the data
                document.getElementById("city-name").textContent = cityName;
                document.getElementById("description").textContent = description;
                document.getElementById("temperature").textContent = `Temperature: ${temper} °C ||`;
                document.getElementById("humidity").textContent = `Humidity: ${humidity}% ||`;
                document.getElementById("windspeed").textContent = `Wind: ${windSpeed} m/s ||`;
                document.getElementById("pressure").textContent = `Pressure: ${pressure} Pa`;
                document.getElementById("time").textContent = `${formattedTime}`;
        }

        function pastweatherdata(city) {
                const table = document.getElementById("pasttable");//gets reference to the table by using passtable id
                // clear the table which values are less than one (except header)
                while (table.rows.length > 1) {
                    table.deleteRow(1);
                }
                for (let i = 1; i <= 7; i++) {//loops over 7 days of data
                    const data = JSON.parse(localStorage.getItem(`Past${city}${i}`));//since i=1 in the above line retrives the data of seven days
                    if (data === null) {//checks if data is retrieved from the localstorage
                        alert("No data to display");//alerts the user if no data available
                    }else{
                        console.log("Data retrieved from Local Storage");//if data is available console logs this message
                    }
                let cityName;
                if (city == 'D') {//if cityname is 'D'
                    cityName = 'Leeds';//sets it to Leeds(default city)
                } else {
                    cityName = `${city}`;//cityName is taken from city parameter which is provided value
                }
                    const pastdata = data.list[0]; //Retrieves the first entry of past weather data for the day
                    //retrieves different weather data from the localstorage
                    const description = pastdata.weather[0].description;
                    const temper = (pastdata.main.temp - 273.15).toFixed(2);
                    const humidity = pastdata.main.humidity;
                    const windSpeed = pastdata.wind.speed;
                    const pressure = pastdata.main.pressure;
                    const timestamp = pastdata.dt * 1000;
                    const date = new Date(timestamp);//creates a date object using timestamp
                    const options = {
                        weekday: "long",
                        month: "long",
                        day: "numeric",
                    };
                    const formattedTime = date.toLocaleString("en-US", options);//formats into humanreadable format
                    //inserts cells into rows and enters data into them
                    const row = table.insertRow();
                    const nameCell = row.insertCell();
                    const descCell = row.insertCell();
                    const tempCell = row.insertCell();
                    const humCell = row.insertCell();
                    const windCell = row.insertCell();
                    const pressCell = row.insertCell();
                    const dateCell = row.insertCell();

                    nameCell.textContent = cityName;
                    descCell.textContent = description;
                    tempCell.textContent = `${temper} °C`;
                    humCell.textContent = `${humidity}%`;
                    windCell.textContent = `${windSpeed} m/s`;
                    pressCell.textContent = `${pressure} hPa`;
                    dateCell.textContent = formattedTime;
                }
        }

        window.onload = function () {
                presentweatherdata('D');//calls the presentweatherdata as D as parameter which displays data of Leeds
                pastweatherdata('D');//calls the pastweatherdata as D as parameter which displays data of Leeds
        };
        window.addEventListener('reload', function() {
        // Clear the form input
        document.getElementById('city').value = '';
        });
            const submitButton = document.getElementById("submit-Button");
            submitButton.addEventListener("click", function () {
                event.preventDefault();//prevents the default form submission
                const city = document.getElementById("city").value;
                presentweatherdata(city);//calls the function with user provided city
                pastweatherdata(city);//calls the function with user provided city
            });
</script>
</body>
</html>