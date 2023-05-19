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
                <input type="text" name="nameinput" id="city" placeholder="Enter city name" method="post">
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
        include 'fetch2(aashu).php';
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
                ).textContent = `DATE:${formattedTime}`;
            }
            

function pastlocal() {
  const table = document.getElementById("weathertable");

  for (let i = 1; i <= 7; i++) {
    const data = JSON.parse(localStorage.getItem(`PastD${i}`));
    const pd = data.list[0];
    const cityName = "Leeds";
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