<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <link rel="stylesheet" href="2332267_AashutoshDahal.css">
</head>

<body>
    <div class="container">
        <div class="weather-box">
            <div class="weather-header">
                <h2>Weather App</h2>
            </div>
            <form method="post" id="form">
                <div class="search-box">
                    <input type="text" name="nameInput" id="city" placeholder="Enter city name" method="post">
                    <button type="submit" name="submit">Search</button>
                    <button type="submit" id="submitButton">Display</button>
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


                <table id="weather-table">
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Temperature(Celsius)</th>
                        <th>Humidity(%)</th>
                        <th>Wind(m/s)</th>
                        <th>Pressure(hPa)</th>
                    </tr>

            </div>
        </div>
        <?php
        include 'fetch2(aashu).php';
        ?>
        <script>
            function current(city) {
                const cd = JSON.parse(localStorage.getItem(`Current${city}`));
                if (cd === null) {
                  alert("No data to display");
                  }
                let cityName;
                if (city == 'D') {
                    cityName = 'Leeds';
                } else {
                    cityName = cd.name;
                }
                const description = cd.weather[0].decription;
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
                };
                const formattedTime = date.toLocaleString("en-US", options);

                document.getElementById("city-name").textContent = cityName;
                document.getElementById("description").textContent = description;
                document.getElementById("temp").textContent = `Temperature: ${temp} °C`;
                document.getElementById("humidity").textContent = `Humidity: ${humidity}%`;
                document.getElementById("wind").textContent = `Wind: ${windSpeed} m/s`;
                document.getElementById("pressure").textContent = `Pressure: ${pressure} Pa`;
                document.getElementById("time").textContent = `Time:${formattedTime}`;
            }

            function past(city) {
  const table = document.getElementById("weather-table");

  // clear the table
  while (table.rows.length > 1) {
      table.deleteRow(1);
  }

  for (let i = 1; i <= 7; i++) {
      const data = JSON.parse(localStorage.getItem(`Past${city}${i}`));
      if (data === null) {
          alert("No data to display");
      }
      let cityName;
      if (city == 'D') {
          cityName = 'Leeds';
      } else {
          cityName = `${city}`;
      }
      const pd = data.list[0];
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
      };
      const formattedTime = date.toLocaleString("en-US", options);

      const row = table.insertRow();
      const dateCell = row.insertCell();
      const nameCell = row.insertCell();
      const descCell = row.insertCell();
      const tempCell = row.insertCell();
      const humCell = row.insertCell();
      const windCell = row.insertCell();
      const pressCell = row.insertCell();

      dateCell.textContent = formattedTime;
      nameCell.textContent = cityName;
      descCell.textContent = description;
      tempCell.textContent = `${temp} °C`;
      humCell.textContent = `${humidity}%`;
      windCell.textContent = `${windSpeed} m/s`;
      pressCell.textContent = `${pressure} hPa`;
  }
}

            window.onload = function () {
                current('D');
                past('D');
            };
            const submitButton = document.getElementById("submitButton");
            submitButton.addEventListener("click", function () {
                event.preventDefault();
                const city = document.getElementById("city").value;
                current(city);
                past(city);
            });
</script>
</body>
</html>