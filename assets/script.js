document.addEventListener("DOMContentLoaded", function () {
    const body = document.querySelector("body");

    // 🌌 Galaxy Background - Creating Stars
    const numStars = 100; // Number of stars
    let galaxy = document.createElement("div");
    galaxy.classList.add("galaxy-bg");
    body.prepend(galaxy);

    for (let i = 0; i < numStars; i++) {
        let star = document.createElement("div");
        star.classList.add("star");

        // Random position and size
        star.style.left = Math.random() * 100 + "vw";
        star.style.top = Math.random() * 100 + "vh";
        if (Math.random() > 0.8) star.classList.add("large");

        body.appendChild(star);
    }

    // 🌫 Adding Nebula Effect
    let nebula = document.createElement("div");
    nebula.classList.add("nebula");
    nebula.style.left = "30vw";
    nebula.style.top = "20vh";

    let nebula2 = document.createElement("div");
    nebula2.classList.add("nebula");
    nebula2.style.left = "70vw";
    nebula2.style.top = "60vh";

    body.appendChild(nebula);
    body.appendChild(nebula2);

    // 🌈 Adding Rainbow at Bottom
    let rainbow = document.createElement("div");
    rainbow.classList.add("rainbow");
    body.appendChild(rainbow);

    // ✅ Auto-hide alerts with fade-out
    const alerts = document.querySelectorAll(".alert");
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = "opacity 0.5s ease-out";
            alert.style.opacity = "0";
            setTimeout(() => alert.style.display = "none", 500);
        }, 3000);
    });

    // 🌙 Dark Mode Toggle (Remembers state)
    const darkModeToggle = document.getElementById("darkModeToggle");
    const isDarkMode = localStorage.getItem("dark-mode") === "enabled";

    if (isDarkMode) {
        document.body.classList.add("dark-mode");
        darkModeToggle.checked = true;
    }

    darkModeToggle.addEventListener("change", function () {
        document.body.classList.toggle("dark-mode");
        localStorage.setItem("dark-mode", document.body.classList.contains("dark-mode") ? "enabled" : "disabled");
    });

    // 🕒 Live Clock
    function updateClock() {
        const now = new Date();
        document.getElementById("clock").innerText = `🕒 ${now.toLocaleTimeString()}`;
    }
    setInterval(updateClock, 1000);
    updateClock();
});

// 🔀 Redirect function with better error handling
function redirectToRegister() {
    var userType = document.getElementById("userType").value;
    if (userType) {
        window.location.href = userType;
    } else {
        const errorMessage = document.createElement("div");
        errorMessage.innerHTML = "⚠️ Please select a user type to register.";
        errorMessage.style.color = "red";
        errorMessage.style.marginTop = "10px";
        document.getElementById("registerSection").appendChild(errorMessage);
        setTimeout(() => errorMessage.remove(), 3000);
    }
}
