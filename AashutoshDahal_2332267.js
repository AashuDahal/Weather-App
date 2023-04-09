//API key to access weather data
const apikey = 'b569296d2cea6a189834774440bfeffd';
//get input of the city from HTML form
const form = document.getElementById('form');
const forminput = document.getElementById('forminput');
const cityname=document.getElementById('cityname');
//function to get data of the needed city
async function getWeather(city) {
  try {
    const response = await fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apikey}&units=metric`);//weather API URL
    const data = await response.json();//extracts the information from API and changes it into JSON format
    const temperature = `${data.main.temp} °C`;
    const humidity = `${data.main.humidity} %`;
    const windSpeed = `${data.wind.speed} m/s`;
    const pressure= `${data.main.pressure} atm`;
    const weatherdescription = data.weather[0].description;
    const iconCode = data.weather[0].icon;//icon code for the weather icon
    const iconUrl = `http://openweathermap.org/img/w/${iconCode}.png`;//weather image URL
    const bckgrndImg=`https://source.unsplash.com/1600x900/?${city}`;
    
    // Get current date and time
    const now = new Date();
    const day=['SUNDAY','MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY'];//defining array days
    const days=day[now.getDay()];//days of the week
    const date = now.toLocaleDateString();//local date
    const time = now.toLocaleTimeString([],{hour:'2-digit',minute:'2-digit'});//local time 

    //this sends the data to the HTML to the id defined in the HTML
    document.getElementById('weathericon').src = iconUrl;
    document.getElementById('weatherdescription').innerHTML=weatherdescription.toUpperCase();
    document.getElementById('cityname').innerHTML=city.toUpperCase();
    document.getElementById('date').innerHTML = date;
    document.getElementById('days').innerHTML = days;
    document.getElementById('time').innerHTML= time;
    document.getElementById('temperature').innerHTML = temperature;
    document.getElementById('humidity').innerHTML = humidity;
    document.getElementById('windspeed').innerHTML = windSpeed;
    document.getElementById('pressure').innerHTML = pressure;
    document.body.style.backgroundimage=`url(${bckgrndImg})`;
  } 
  //handles the error
    catch (error) {
    alert("Error check your input/code");
  }
}
//Listen for form submission events and prevent the form from submitting in the default way
form.addEventListener('submit', (event) => {
  event.preventDefault();//prevents the page from loading when the form is submitted
  const city = forminput.value;//input value stored in city variable
  getWeather(city);//which is passed to the function above and fetches the data
});

// Show weather for default city when the page loads
getWeather('Leeds');
