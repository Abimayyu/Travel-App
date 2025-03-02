// Ambil elemen tombol
const scrollToTopBtn = document.getElementById("scrollToTop");

// Fungsi untuk memunculkan tombol ketika menggulir
window.onscroll = function () {
    if (
        document.body.scrollTop > 200 ||
        document.documentElement.scrollTop > 200
    ) {
        scrollToTopBtn.classList.add("show");
    } else {
        scrollToTopBtn.classList.remove("show");
    }
};

// Fungsi untuk menggulir kembali ke atas saat tombol diklik
scrollToTopBtn.onclick = function () {
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });
};
