# Weather App

## Details
A simple weather app built with Laravel, VueJS, Tailwind, OpenWeather(5-day weather forecast data with 3-hour step), Geoapify for location api

### Live Demo app
Check the live demo app at [Weather App](https://weather-app-adrian.herokuapp.com/)

## How to install

- Clone the repository with __`git clone https://github.com/aliadhillon/weather-app.git`__
- Copy __`.env.example`__ file to __`.env`__
- Run __`composer install`__
- Run __`php artisan key:generate --ansi`__
- Run __`npm install && npm run dev`__
- Create free account on [Weatherbit](https://www.weatherbit.io/) and get the API Key
  and set it in the __`.env`__ file as __`WEATHER_BIT_API_KEY`__
- That's it: launch with __`php artisan serve`__ 
