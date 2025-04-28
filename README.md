<h1>🏋️‍♂️ Workout Logger </h1>
<h3>A web-based workout planner and logger that helps users track their running and cycling activities. Built with PHP, MySQL, HTML, CSS, JavaScript, and Leaflet.js for an interactive map experience.</h3>

<h2>🚀 Features</h2>
<ul>
<li>🗺️ Display map centered on user's current location.</li>
<li>📍 If location is denied, show a default location.</li>
<li>✍️ Log running and cycling workouts with distance, duration, cadence, and elevation gain.</li>
<li>👤 User authentication (Login/Register).</li>
<li>📈 Track user's daily steps.</li>
<li>🔔 Prompt users to complete profile (like setting height) if missing.</li>
<li>🧠 Smart error handling and location request retrying.</li>
  
</ul>

<h2>🛠️ Tech Stack</h2>
<ul>
  <li>Frontend: HTML, CSS, JavaScript, Leaflet.js</li>
  <li>Backend: PHP</li>
  <li>Database: MySQL</li>
  <li>Other Libraries:
  <ul>
    <li>Toastify.js (notifications)</li>
    <li>Leaflet.js (maps)</li>
  </ul>
  </li>
</ul>

<h2>🧑‍💻 How to Run Locally</h2>
<ul>
 - Clone the repository:
  ```bash
  git clone https://github.com/your-username/workout-logger.git
  ```
  <li>Set up a local server:
    <ul>
      <li>Use XAMPP / MAMP / WAMP.</li>
      <li>Place project inside /htdocs (for XAMPP).</li>
    </ul>
  </li>
  <li>Create MySQL database:
    <ul>
      <li>Import the provided database.sql file (if available).</li>
      <li>Update backend/config.php with your DB credentials.</li>
    </ul>
  </li>
  <li>Run the app:
  <ul>
    <li>Visit http://localhost/workout-planner/public/ .</li>
  </ul>
  </li>
</ul>

<h2>⚙️ Environment Variables</h2>
<p>You might need to configure:</p>
<ul>
  <li>DB_HOST</li>
  <li>DB_USER</li>
  <li>DB_PASSWORD</li>
  <li>DB_NAME</li>
</ul>
<small>All settings are inside backend/config.php.</small>

<h2>✨ Future Improvements</h2>
<ul>
  <li>📱 Make it fully responsive for mobile.</li>
  <li>🎯 Add goal tracking (e.g., 5K run goal).</li>
  <li>🏆 Implement badges/achievements.</li>
  <li>🧩 Export workouts to CSV or JSON.</li>
  <li>🧠 Improve error handling and offline support.</li>
</ul>
<h2>🙌 Acknowledgements</h2>
<ul>
  <li>Leaflet.js</li>
  <li>Toastify.js</li>
  <li>OpenStreetMap</li>
</ul>

<h2>📜 License</h2>
<p>This project is licensed under the MIT License — feel free to use, modify, and distribute it!</p>

<h2>📬 Contact</h2>
<p>Feel free to reach out if you have suggestions or questions:</p>
<p>GitHub: Rafael Walder</p>
<p>Email: rafaelwalder99@gmail.com</p>
