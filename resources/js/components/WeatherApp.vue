<template>
  <div class="text-white mb-8">
    <div class="places-input text-gray-800">
      <div class="autocomplete-container" id="autocomplete"></div>
    </div>
    <div
      class="
        weather-container
        font-sans
        w-128
        max-w-lg
        overflow-hidden
        bg-gray-900
        shadow-lg
        mt-4
        rounded-lg
      "
    >
      <div class="current-weather flex items-center justify-between px-6 py-8">
        <div class="flex items-center">
          <div>
            <div class="text-6xl font-semibold">{{ currentTemp.actual }}°C</div>
            <div>Feels like {{ currentTemp.feels }}°C</div>
          </div>
          <div class="mx-5">
            <div class="font-semibold">{{ currentTemp.summary }}</div>
            <div>{{ location.name }}</div>
          </div>
        </div>
        <div>
          <img
            class="h-15 w-auto sm:h-10"
            src="http://openweathermap.org/img/wn/10d@2x.png"
            alt=""
          />
        </div>
      </div>
      <div class="future-weather text-sm bg-gray-800 px-6 py-8 overflow-hidden">
        <div
          v-for="(day, index) in daily"
          :key="day.dt"
          class="flex items-center"
          :class="{ 'mt-8': index > 0 }"
        >
          <!-- v-if="index < 5" make daily a computed property-->
          <div class="w-1/6 text-lg text-gray-200">
            {{ toDayOfWeek(day.dt) }}
            <div class="text-sm overflow-hidden">{{ toHour(day.dt) }}</div>
          </div>
          <div class="w-4/6 px-4 flex items-center">
            <div>
              <img
                class="h-10 w-auto sm:h-10"
                :src="getSummary(day.weather).icon"
                alt=""
              />
            </div>
            <!-- <div class="ml-3" v-if="day.weather[0].description"> -->
            <div class="ml-3">
              <!-- {{ capitaLizeFirstLetter(day.weather[0].description) }} -->
              {{ getSummary(day.weather).description }}
            </div>
          </div>
          <div class="w-1/6 text-right">
            <div>{{ Math.round(day.main.temp_max) }}°C</div>
            <div>{{ Math.round(day.main.temp_min) }}°C</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { GeocoderAutocomplete } from "@geoapify/geocoder-autocomplete";
export default {
  mounted() {
    this.fetchCurrentWeather();
    this.fetchFutureWeather();

    const autocomplete = new GeocoderAutocomplete(
      document.getElementById("autocomplete"),
      "48ebd1d2ae4c47088ba7c1588f2dd06e",
      {
        /* Geocoder options */
        type: "city",
      }
    );

    autocomplete.on("select", (location) => {
      // check selected location here
      if (location) {
        this.location.name = `${location.properties.city},${location.properties.country}`;
        this.location.lat = location.properties.lat;
        this.location.lon = location.properties.lon;
      }
    });

    // autocomplete.on("suggestions", (suggestions) => {
    //   // process suggestions here
    // });
  },
  watch: {
    location: {
      handler(newValue, oldValue) {
        this.fetchCurrentWeather();
        this.fetchFutureWeather();
      },
      deep: true,
    },
  },
  data() {
    return {
      currentTemp: {
        actual: "",
        feels: "",
        summary: "",
        icon: "",
      },
      daily: [],
      address: "Tokyo",
      location: {
        name: "Tokyo",
        lat: "33.5230707",
        lon: "134.2429417",
      },
    };
  },
  methods: {
    fetchCurrentWeather() {
      fetch(
        `/api/get-weather?lat=${this.location.lat}&lon=${this.location.lon}&endpoint=weather`
      )
        .then((response) => response.json())
        .then((data) => {
          //   console.log(data);
          const description = this.capitaLizeFirstLetter(
            data.weather[0].description
          );

          this.currentTemp.actual = Math.round(data.main.temp);
          this.currentTemp.feels = Math.round(data.main.feels_like);
          this.currentTemp.summary = description;
          this.currentTemp.icon = data.weather[0].icon;
        });
    },
    fetchFutureWeather() {
      fetch(
        `/api/get-weather?lat=${this.location.lat}&lon=${this.location.lon}&endpoint=forecast`
      )
        .then((response) => response.json())
        .then((data) => {
          this.daily = data.list;
        });
    },
    capitaLizeFirstLetter(word) {
      return word
        .split(" ")
        .map((word) => {
          return word[0].toUpperCase() + word.substring(1);
        })
        .join(" ");
    },
    toDayOfWeek(timestamp) {
      const newDate = new Date(timestamp * 1000);
      const days = ["SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"];
      return days[newDate.getDay()];
    },
    toHour(timestamp) {
      const newDate = new Date(timestamp * 1000);
      const hours = newDate.getHours();

      var timeValue;

      if (hours > 0 && hours <= 12) {
        timeValue = "" + hours;
      } else if (hours > 12) {
        timeValue = "" + (hours - 12);
      } else if (hours == 0) {
        timeValue = "12";
      }
      timeValue += hours >= 12 ? " PM" : " AM"; // get AM/PM
      return timeValue;
    },
    getSummary(weather) {
      return {
        description: this.capitaLizeFirstLetter(weather[0].description),
        icon: `http://openweathermap.org/img/wn/${weather[0].icon}@2x.png`,
      };
    },
  },
  computed: {},
};
</script>
