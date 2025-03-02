//
const element = document.getElementById("navbarSecondary");
const navbarNav = document.getElementById("navbarNav");
const navbarNav2 = document.getElementById("navbarNav2");
const secondaryNav = document.getElementById("navbarSecondary");
const collapse = new bootstrap.Collapse(navbarNav, {
    toggle: false,
});
const collapse2 = new bootstrap.Collapse(navbarNav2, {
    toggle: false,
});
if (window.scrollY > 200) {
    element.classList.add("navbar-stick");
} else {
    element.classList.remove("navbar-stick");
}
window.addEventListener("scroll", function () {
    element.classList.toggle("navbar-stick", window.scrollY > 200);
    if (!secondaryNav.classList.contains("navbar-stick")) {
        if (window.scrollY < 200) {
            collapse2.hide();
        }
    } else {
        if (window.scrollY > 200) {
            collapse.hide();
        }
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const fadeElements = document.querySelectorAll(".fade-in");

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("visible");
                }
            });
        },
        { threshold: 0.5 }
    );

    fadeElements.forEach((element) => {
        observer.observe(element);
    });
});
