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

window.productCarousel = function (images = [], interval = 4000) {
    return {
        images,
        interval,
        currentIndex: 0,
        timer: null,
        init() {
            if (this.images.length <= 1) {
                return;
            }

            this.timer = window.setInterval(() => {
                this.currentIndex =
                    (this.currentIndex + 1) % this.images.length;
            }, this.interval);
        },
        currentImage() {
            return this.images[this.currentIndex] || "";
        },
        hasMultipleImages() {
            return this.images.length > 1;
        },
        goTo(index) {
            this.currentIndex = index;
        },
        destroy() {
            if (this.timer) {
                window.clearInterval(this.timer);
            }
        },
    };
};

Alpine.start();

document.addEventListener("DOMContentLoaded", () => {
    const pageShell = document.querySelector("[data-page-enter]");
    if (pageShell) {
        requestAnimationFrame(() => pageShell.classList.add("is-ready"));
    }

    document.querySelectorAll("[data-auth-form]").forEach((form) => {
        const authFields = Array.from(
            form.querySelectorAll("[data-auth-field]"),
        );

        authFields.forEach((field, index) => {
            field.style.setProperty(
                "--auth-delay",
                `${Math.min(index * 70, 350)}ms`,
            );
            requestAnimationFrame(() => field.classList.add("is-visible"));
        });

        const toggleFocusState = (event) => {
            const field = event.target.closest("[data-auth-field]");
            if (!field) {
                return;
            }

            authFields.forEach((item) => item.classList.remove("is-focused"));
            field.classList.add("is-focused");
        };

        form.addEventListener("focusin", toggleFocusState);
        form.addEventListener("focusout", (event) => {
            const field = event.target.closest("[data-auth-field]");
            if (field) {
                field.classList.remove("is-focused");
            }
        });
    });

    const revealTargets = document.querySelectorAll("[data-reveal]");
    if (revealTargets.length) {
        if ("IntersectionObserver" in window) {
            const revealObserver = new IntersectionObserver(
                (entries, observer) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add("is-visible");
                            observer.unobserve(entry.target);
                        }
                    });
                },
                { threshold: 0.15 },
            );

            revealTargets.forEach((target, index) => {
                target.style.setProperty(
                    "--reveal-delay",
                    `${Math.min(index * 90, 420)}ms`,
                );
                revealObserver.observe(target);
            });
        } else {
            revealTargets.forEach((target) =>
                target.classList.add("is-visible"),
            );
        }
    }

    const stickyNav = document.querySelector("[data-sticky-nav]");
    if (stickyNav) {
        const updateNavState = () => {
            stickyNav.dataset.scrolled = window.scrollY > 8 ? "true" : "false";
        };

        updateNavState();
        window.addEventListener("scroll", updateNavState, { passive: true });
    }

    document.querySelectorAll("[data-chart]").forEach((canvas) => {
        const config = JSON.parse(canvas.dataset.chart || "{}");
        if (config.labels?.length) {
            new Chart(canvas, config);
        }
    });
});

window.initAuthFormAnimations = function (root = document) {
    const forms = root.querySelectorAll("[data-auth-form]");

    forms.forEach((form) => {
        const fields = Array.from(form.querySelectorAll("[data-auth-field]"));

        fields.forEach((field, index) => {
            field.style.setProperty(
                "--auth-delay",
                `${Math.min(index * 70, 350)}ms`,
            );
            requestAnimationFrame(() => field.classList.add("is-visible"));
        });

        const syncFieldState = (event) => {
            const target = event.target.closest("[data-auth-field]");
            if (!target) {
                return;
            }

            fields.forEach((field) => field.classList.remove("is-focused"));
            target.classList.add("is-focused");
        };

        form.addEventListener("focusin", syncFieldState);
        form.addEventListener("focusout", (event) => {
            const field = event.target.closest("[data-auth-field]");
            if (field) {
                field.classList.remove("is-focused");
            }
        });

        fields.forEach((field) => {
            const input = field.querySelector("input, select, textarea");
            if (!input) {
                return;
            }

            const updateFilledState = () => {
                const hasValue = String(input.value || "").trim().length > 0;
                field.classList.toggle("is-filled", hasValue);
            };

            updateFilledState();
            input.addEventListener("input", updateFilledState);
            input.addEventListener("change", updateFilledState);
        });
    });
};

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
