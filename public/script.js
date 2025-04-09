console.log("Script Loaded");

if (document.body.classList.contains("index")) {
  //* Home Carousel Navigator//
  const track = document.querySelector(".carousel-track");
  const items = document.querySelectorAll(".carousel-item");
  const dotsContainer = document.querySelector(".carousel-dots");
  let currentIndex = 0;

  // Create dots based on the number of items
  items.forEach((_, index) => {
    const dot = document.createElement("button");
    dot.addEventListener("click", () => moveToSlide(index));
    dotsContainer.appendChild(dot);
  });

  const dots = dotsContainer.querySelectorAll("button");
  dots[currentIndex].classList.add("active");

  function moveToSlide(index) {
    track.style.transform = `translateX(-${index * 100}%)`;
    dots[currentIndex].classList.remove("active");
    dots[index].classList.add("active");
    currentIndex = index;
  }
  // Periodically move to the next slide
  setInterval(() => {
    // Calculate the next index, and loop back to 0 if we're at the last slide
    const nextIndex = (currentIndex + 1) % items.length;
    moveToSlide(nextIndex);
  }, 2500);
}

//* Home Carousel Navigator//

if (document.body.classList.contains("log-workout")) {
  class Workout {
    date = new Date();
    id = (Date.now() + "").slice(-10);

    constructor(coords, distance, duration) {
      this.coords = coords; // [lat,lng]
      this.distance = distance;
      this.duration = duration;
    }
    _setDescription() {
      const months = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ];

      this.description = `${this.type[0].toUpperCase()}${this.type.slice(
        1
      )} on ${months[this.date.getMonth()]}${this.date.getDate()}`;
    }
  }

  class Running extends Workout {
    type = "running";
    constructor(coords, distance, duration, cadence) {
      super(coords, distance, duration);
      this.cadence = cadence;
      this.calcPace();
      this._setDescription();
    }
    calcPace() {
      //min/km
      this.pace = this.duration / this.distance;
      return this.pace;
    }
  }

  class Cycling extends Workout {
    type = "cycling";
    constructor(coords, distance, duration, elevationGain) {
      super(coords, distance, duration);
      this.elevationGain = elevationGain;
      this.calcSpeed();
      this._setDescription();
    }
    calcSpeed() {
      this.speed = this.distance / (this.duration / 60);
      return this.speed;
    }
  }

  const form = document.querySelector(".form");
  const containerWorkouts = document.querySelector(".workouts");
  const input_distance = document.querySelector(".form__input--distance");
  const input_duration = document.querySelector(".form__input--duration");
  const input_cadence = document.querySelector(".form__input--cadence");
  const input_elevation = document.querySelector(".form__input--elevation");
  const input_type = document.querySelector(".form__input--type");

  class App {
    #map;
    #mapEvent;
    #workouts = [];

    constructor() {
      this._getPosition();
      this._loadWorkouts();
      form.addEventListener("submit", this._newWorkout.bind(this));
      input_type.addEventListener("change", this._toggleElevationField);
      containerWorkouts.addEventListener("click", this._moveToPopup.bind(this));
    }

    _getPosition() {
      document.querySelector(".spinner-container").style.display = "block";
      navigator.geolocation.getCurrentPosition(
        this._loadMap.bind(this), // Success callback
        (error) => {
          // Error callback
          if (error.code === error.PERMISSION_DENIED) {
            alert("Please allow location access to proceed.");
          }
          document.querySelector(".spinner-container").style.display = "none";
        }
      );
    }

    _loadMap(position) {
      const latitude = position["coords"]["latitude"];
      const longitude = position["coords"]["longitude"];

      const coords = [latitude, longitude];

      this.#map = L.map("map").setView(coords, 13);

      L.tileLayer("https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png", {
        attribution:
          '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      }).addTo(this.#map); // Fixed reference to this.#map

      this.#map.on("click", this._showForm.bind(this));
    }

    _showForm(mapE) {
      this.#mapEvent = mapE;
      form.classList.remove("hidden");
      input_distance.focus();
    }

    _hideForm() {
      input_cadence.value = input_distance.value = input_duration.value = "";
      form.classList.add("hidden");
    }

    _toggleElevationField() {
      input_elevation
        .closest(".form__row")
        .classList.toggle("form__row--hidden");
      input_cadence.closest(".form__row").classList.toggle("form__row--hidden");
    }

    _newWorkout(e) {
      e.preventDefault();
      const type = input_type.value;
      const distance = +input_distance.value;
      const duration = +input_duration.value;
      const { lat, lng } = this.#mapEvent.latlng;
      let workout;

      if (type == "running") {
        const cadence = +input_cadence.value;

        if (
          !Number.isFinite(distance) ||
          !Number.isFinite(duration) ||
          !Number.isFinite(cadence) ||
          distance <= 0 ||
          duration <= 0 ||
          cadence < 0
        )
          return alert("Inputs should be positive");

        workout = new Running([lat, lng], distance, duration, cadence);
      }

      if (type == "cycling") {
        const elevation = +input_elevation.value;
        if (
          !Number.isFinite(distance) ||
          !Number.isFinite(duration) ||
          !Number.isFinite(elevation) ||
          distance <= 0 ||
          duration <= 0
        )
          return alert("Inputs should be positive");

        workout = new Cycling([lat, lng], distance, duration, elevation);
      }
      this.#workouts.push(workout);
      console.log(workout);

      this._renderWorkoutMarker(workout);

      this._renderWorkout(workout);

      this._hideForm();

      this._uploadWorkout(workout);
    }

    _renderWorkoutMarker(workout) {
      L.marker(workout.coords)
        .addTo(this.#map)
        .bindPopup(
          L.popup({
            maxWidth: 250,
            minWidth: 100,
            autoClose: false,
            closeOnClick: false,
            className: `${workout.type}-popup`,
          })
        )
        .setPopupContent(
          `${workout.type === "running" ? "🏃‍♂️" : "🚴‍♀️"} ${workout.description}`
        )
        .openPopup();
    }

    _renderWorkout(workout) {
      let html = `
    <li class="workout workout--${workout.type}" data-id="${workout.id}">
    <h2 class="workout__title">${workout.description}</h2>
    <div class="workout__details">
      <span class="workout__icon">${
        workout.type === "running" ? "🏃‍♂️" : "🚴‍♀️"
      }</span>
      <span class="workout__value">${workout.distance}</span>
      <span class="workout__unit">km</span>
    </div>
    <div class="workout__details">
      <span class="workout__icon">⏱</span>
      <span class="workout__value">${workout.duration}</span>
      <span class="workout__unit">min</span>
    </div>`;

      if (workout.type === "running") {
        html += `
    <div class="workout__details">
      <span class="workout__icon">⚡️</span>
      <span class="workout__value">${workout.pace.toFixed(1)}</span>
      <span class="workout__unit">min/km</span>
    </div>
    <div class="workout__details">
      <span class="workout__icon">🦶🏼</span>
      <span class="workout__value">${workout.cadence}</span>
      <span class="workout__unit">spm</span>
    </div>
    </li>`;
      }
      if (workout.type === "cycling") {
        html += `
      <div class="workout__details">
        <span class="workout__icon">⚡️</span>
        <span class="workout__value">${workout.speed.toFixed(1)}</span>
        <span class="workout__unit">km/h</span>
      </div>
      <div class="workout__details">
        <span class="workout__icon">⛰</span>
        <span class="workout__value">${workout.elevationGain}</span>
        <span class="workout__unit">m</span>
      </div>
    </li>`;
      }
      form.insertAdjacentHTML("afterend", html);
    }

    _moveToPopup(e) {
      const workoutEl = e.target.closest(".workout");

      if (!workoutEl) return;
      const workout = this.#workouts.find(
        (work) => work.id === workoutEl.dataset.id
      );

      this.#map.setView(workout.coords, 15, {
        animate: true,
        pan: { duration: 2 },
      });
    }

    _uploadWorkout(workout) {
      fetch("../backend/save_workout.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(workout),
      })
        .then((response) => response.text()) // Get raw text first
        .then((text) => {
          return JSON.parse(text); // Then parse JSON
        })
        .then((data) => {
          console.log("Parsed response:", data);
          if (data.status === "success") {
            Toastify({
              text: "Workout uploaded successfully! 🎉",
              duration: 3000,
              gravity: "top", // top or bottom
              position: "center", // left, center, or right
              backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
              className: "large-toast",
            }).showToast();
          } else {
            Toastify({
              text: "Upload failed: " + data.message,
              duration: 4000,
              gravity: "top",
              position: "center",
              backgroundColor: "#ff5733",
              className: "large-toast",
            }).showToast();
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          Toastify({
            text: "An error occurred while uploading. Please try again.",
            duration: 4000,
            gravity: "top",
            position: "center",
            backgroundColor: "#ff5733",
            className: "large-toast",
          }).showToast();
        });
    }

    _loadWorkouts() {
      document.querySelector(".spinner-container").style.display = "block";
      fetch("../backend/get_workout.php")
        .then((response) => response.json())
        .then((data) => {
          console.log("Received workouts:", data); // Check if all workouts are received

          if (!Array.isArray(data)) throw new Error("Invalid data format");

          data.sort((a, b) => new Date(b.date) - new Date(a.date));

          data.forEach((workoutData) => {
            let workout;
            if (workoutData.type === "running") {
              workout = new Running(
                [workoutData.latitude, workoutData.longitude],
                workoutData.distance,
                workoutData.duration,
                workoutData.cadence
              );
            } else if (workoutData.type === "cycling") {
              workout = new Cycling(
                [workoutData.latitude, workoutData.longitude],
                workoutData.distance,
                workoutData.duration,
                workoutData.elevationGain
              );
            }
            workout.description = workoutData.description;
            this.#workouts.push(workout);
            this._renderWorkout(workout);
            this._renderWorkoutMarker(workout);
          });
        })
        .catch((error) => console.error("Error loading workouts:", error))
        .finally(() => {
          // Hide spinner after loading is complete
          document.querySelector(".spinner-container").style.display = "none";
        });
    }
  }

  const app = new App();
}

if (
  document.body.classList.contains("loggin-Page") ||
  document.body.classList.contains("register-Page")
) {
  const usernameInput = document.querySelector(".usernameIn");
  usernameInput.focus();
}

if (document.body.classList.contains("profile")) {
  const editButton = document.getElementById("editProfile");
  const editModal = document.getElementById("editProfileModal");
  const closeButtons = document.querySelectorAll(".close");
  const uploadImage = document.getElementById("uploadImage");
  const imagePreview = document.getElementById("imagePreview");
  const imagePreviewContainer = document.getElementById(
    "imagePreviewContainer"
  );
  const cropButton = document.getElementById("cropButton");
  const croppedImageInput = document.getElementById("croppedImageInput");
  const currentProfileImageContainer = document.getElementById(
    "currentProfileImageContainer"
  );
  let cropper;

  function openModal(modal) {
    console.log("Opening modal...");
    modal.style.display = "flex"; // Show modal
  }

  function closeModal(modal) {
    console.log("Closing modal...");
    modal.style.display = "none"; // Hide modal

    // Reset the form inside the modal
    const form = modal.querySelector("form");
    if (form) {
      // Check if the cropper instance exists before calling destroy
      if (typeof cropper !== "undefined" && cropper !== null) {
        cropper.destroy(); // Destroy the cropper instance
      }

      // Clear image preview
      if (imagePreview) {
        imagePreview.src = ""; // Clear the preview image
      }

      if (imagePreviewContainer) {
        imagePreviewContainer.style.display = "none"; // Hide the preview container
      }
      form.reset(); // Clear all form inputs
    }

    Toastify({
      text: "Your changes were not saved. Try again",
      duration: 3000,
      gravity: "top", // top or bottom
      position: "center", // left, center, or right
      className: "large-toast",
      backgroundColor: "linear-gradient(to right, #ff0000, #ff7f7f)", // Red gradient for failure
    }).showToast();
  }

  if (editButton) {
    editButton.addEventListener("click", () => openModal(editModal));
  }

  closeButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const modal = button.closest(".modal");
      closeModal(modal);
    });
  });

  window.addEventListener("click", (event) => {
    if (event.target.classList.contains("modal")) {
      closeModal(event.target);
    }
  });

  currentProfileImageContainer.addEventListener("click", function () {
    uploadImage.click();
  });

  uploadImage.addEventListener("change", function (e) {
    const file = e.target.files[0];
    if (file && file.type.startsWith("image/")) {
      const reader = new FileReader();
      reader.onload = function () {
        imagePreview.src = reader.result;
        imagePreviewContainer.style.display = "block";

        if (cropper) cropper.destroy();
        cropper = new Cropper(imagePreview, {
          aspectRatio: 1,
          viewMode: 1,
        });
      };
      reader.readAsDataURL(file);
    }
  });

  cropButton.addEventListener("click", function () {
    if (cropper) {
      const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
      canvas.toBlob(function (blob) {
        const reader = new FileReader();
        reader.onloadend = function () {
          croppedImageInput.value = reader.result; // Set to hidden input
        };
        reader.readAsDataURL(blob);
        cropButton.style.backgroundColor = "#77b55a";
        cropButton.style.color = "white";
        cropButton.disabled = true;
      });
    }
  });
}
