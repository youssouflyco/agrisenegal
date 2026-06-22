import "./bootstrap";
import Alpine from "alpinejs";
import Chart from "chart.js/auto";
import L from "leaflet";
import "leaflet.markercluster";
import "leaflet.markercluster/dist/MarkerCluster.css";
import "leaflet.markercluster/dist/MarkerCluster.Default.css";
import "leaflet/dist/leaflet.css";

window.Alpine = Alpine;
window.Chart = Chart;
window.L = L;

Alpine.start();

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-chart]").forEach((canvas) => {
        const config = JSON.parse(canvas.dataset.chart || "{}");
        if (config.labels?.length) {
            new Chart(canvas, config);
        }
    });
});

window.fillLocationFromDevice = async function (btn) {
    try {
        const container = btn.closest("form") || document;
        const latInput = container.querySelector('input[name="latitude"]');
        const lonInput = container.querySelector('input[name="longitude"]');
        const nameInput =
            container.querySelector('input[name="location_name"]') ||
            container.querySelector('input[name="label"]') ||
            container.querySelector('input[name="location_label"]');

        btn.setAttribute("disabled", "disabled");
        const initialText = btn.innerText;
        btn.innerText = "Récupération…";

        if (!("geolocation" in navigator)) {
            alert("Géolocalisation non supportée par ce navigateur.");
            btn.removeAttribute("disabled");
            btn.innerText = initialText;
            return;
        }

        navigator.geolocation.getCurrentPosition(
            async (pos) => {
                const lat = pos.coords.latitude.toFixed(6);
                const lon = pos.coords.longitude.toFixed(6);
                if (latInput) latInput.value = lat;
                if (lonInput) lonInput.value = lon;

                // Reverse geocode via Nominatim
                try {
                    const res = await fetch(
                        `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`,
                    );
                    if (res.ok) {
                        const data = await res.json();
                        const display = data.display_name || "";
                        if (nameInput && !nameInput.value) {
                            nameInput.value = display;
                        }
                    }
                } catch (e) {
                    console.debug("Reverse geocode failed", e);
                }

                btn.removeAttribute("disabled");
                btn.innerText = initialText;
            },
            (err) => {
                alert("Impossible d'obtenir la position: " + err.message);
                btn.removeAttribute("disabled");
                btn.innerText = initialText;
            },
            { enableHighAccuracy: true, timeout: 15000 },
        );
    } catch (e) {
        console.error(e);
    }
};
