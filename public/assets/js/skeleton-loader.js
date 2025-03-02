$(document).ready(function () {
    const skeletonElements = document.querySelectorAll(".skeleton-loader");
    skeletonElements.forEach((el) => (el.style.display = "none"));

    const contents = document.querySelectorAll(".konten-loader");
    contents.forEach(content => {
        if (content.classList.contains("d-none")) {
            content.classList.remove("d-none");
        }
    });
    // content.classList.toggle("d-none");
});
