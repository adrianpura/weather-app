<template>
  <div class="text-white mb-8">
    <div class="places-input text-gray-800">
      <input type="text" class="w-full" />
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
          </div>
          <div class="w-4/6 px-4 flex items-center">
            <div>
              <!-- <img
                class="h-10 w-auto sm:h-10"
                src="http://openweathermap.org/img/wn/10d@2x.png"
                alt=""
              /> -->
              icon
            </div>
            <div class="ml-3" v-if="day.weather[0].description">
              {{ capitaLizeFirstLetter(day.weather[0].description) }}
            </div>
          </div>
          <div class="w-1/6 text-right">
            <div>{{ Math.round(day.main.temp_max) }}°C</div>
            <div>{{ Math.round(day.main.temp_min) }}°C</div>
          </div>
        </div>
        <!-- <div class="flex items-center mt-8">
          <div class="w-1/6 text-lg text-gray-200">MON</div>
          <div class="w-4/6 px-4 flex items-center">
            <div>icon</div>
            <div class="ml-3">Cloudy with chance of showers</div>
          </div>
          <div class="w-1/6 text-right">
            <div>8°C</div>
            <div>-8°C</div>
          </div>
        </div> -->
        <!-- <div class="flex items-center mt-8">
          <div class="w-1/6 text-lg text-gray-200">MON</div>
          <div class="w-4/6 px-4 flex items-center">
            <div>icon</div>
            <div class="ml-3">Cloudy with chance of showers</div>
          </div>
          <div class="w-1/6 text-right">
            <div>8°C</div>
            <div>-8°C</div>
          </div>
        </div> -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    this.fetchCurrentWeather();
    this.fetchFutureWeather();
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
      location: {
        name: "Shuzenji",
        lat: "35",
        lon: "139",
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
  },
  computed: {
    getSummary() {
      console.log(this.daily[0]);
    },
  },
};
</script>
