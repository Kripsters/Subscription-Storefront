import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

if (document.URL == "http://127.0.0.1:8000/dashboard") {
document.addEventListener("DOMContentLoaded", () => {
    const numImages = 4; // configurable number of images
    const images = []; // set image array

    for (let i = 1; i <= numImages; i++) {
        images.push(`storage/images/background-dash-${i}.jpg`); // add image URLs to array from 1 to numImages
    }

    const slideshow = document.getElementById("slideshow");
    const slides = [];
    let current = 0;
    let isLoaded = {}; // track which images have been loaded

    // Create slide elements (without background images yet)
    images.forEach((src, i) => {
        const slide = document.createElement("div");
        slide.className =
            "slide absolute inset-0 bg-cover bg-center filter blur-[10px] transition-opacity duration-1000 opacity-0";
        slide.dataset.src = src; // store URL for lazy loading
        slideshow.appendChild(slide);
        slides.push(slide);
    });

    // Function to load an image
    function loadImage(index) {
        if (isLoaded[index]) return Promise.resolve();

        return new Promise((resolve) => {
            const img = new Image();
            img.onload = () => {
                slides[index].style.backgroundImage = `url(${images[index]})`;
                isLoaded[index] = true;
                resolve();
            };
            img.onerror = () => resolve(); // continue even if image fails
            img.src = images[index];
        });
    }

    // Load first image and show it
    loadImage(0).then(() => {
        slides[0].classList.add("opacity-100");
    });

    // Preload next image
    if (images.length > 1) {
        loadImage(1);
    }

    function changeSlide() {
        const next = (current + 1) % slides.length;

        // Load next image if not already loaded
        loadImage(next).then(() => {
            // Fade out current
            slides[current].classList.remove("opacity-100");
            slides[current].classList.add("opacity-0");

            // Fade in next
            slides[next].classList.remove("opacity-0");
            slides[next].classList.add("opacity-100");

            current = next;

            // Preload the image after next
            const afterNext = (next + 1) % slides.length;
            loadImage(afterNext);
        });
    }

    setInterval(changeSlide, 5000); // switch every 5s
});
}

// Dark mode toggle
const themeToggle = document.getElementById("theme-toggle");
const lightIcon = document.getElementById("theme-toggle-light");
const darkIcon = document.getElementById("theme-toggle-dark");

// On page load -> set icon
if (
    localStorage.theme === "dark" ||
    (!("theme" in localStorage) &&
        window.matchMedia("(prefers-color-scheme: dark)").matches)
) {
    document.documentElement.classList.add("dark");
    darkIcon.classList.add("hidden");
    lightIcon.classList.remove("hidden");
} else {
    document.documentElement.classList.remove("dark");
    lightIcon.classList.add("hidden");
    darkIcon.classList.remove("hidden");
}

// On click -> toggle
themeToggle.addEventListener("click", () => {
    document.documentElement.classList.toggle("dark");
    if (document.documentElement.classList.contains("dark")) {
        localStorage.setItem("theme", "dark");
        darkIcon.classList.add("hidden");
        lightIcon.classList.remove("hidden");
    } else {
        localStorage.setItem("theme", "light");
        lightIcon.classList.add("hidden");
        darkIcon.classList.remove("hidden");
    }
});
