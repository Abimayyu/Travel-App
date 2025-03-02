// script.js
document.addEventListener("DOMContentLoaded", () => {
    const buttons = document.querySelectorAll(".collapse-btn");

    buttons.forEach((button) => {
        button.addEventListener("click", () => {
            const targetId = button.getAttribute("data-target");
            const content = document.getElementById(targetId);
            const openText = button.querySelector(".open-text");
            const closeText = button.querySelector(".close-text");

            // Cek apakah konten sedang dibuka atau tidak
            const isOpen = content.classList.contains("open");

            // Set nilai max-height ke scrollHeight (tinggi konten)
            if (!isOpen) {
                openText.style.display = "none";
                closeText.style.display = "inline";
                content.style.maxHeight = content.scrollHeight + "px"; // Set max-height ke tinggi konten
            } else {
                openText.style.display = "inline";
                closeText.style.display = "none";
                content.style.maxHeight = 0; // Atur max-height kembali ke 0 untuk menyembunyikan
            }

            // Tambahkan atau hapus kelas 'open'
            content.classList.toggle("open");

            // Transisi opacity untuk efek fading
            content.style.opacity = isOpen ? 0 : 1;
        });
    });
});
