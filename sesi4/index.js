document.addEventListener("DOMContentLoaded", function () {
    const sizeSlider = document.getElementById("sizeSlider");
    const projectImages = document.querySelectorAll(".project-img");

    sizeSlider.addEventListener("input", function () {
        const newSize = sizeSlider.value + "%";
        projectImages.forEach(img => {
            img.style.width = newSize;
        });
    });
});

// Fungsi untuk menampilkan profil - alamat saat box diklik
<script>
        function toggleText(id) {
            var element = document.getElementById(id);
            if (element.style.display === "none" || element.style.display === "") {
                element.style.display = "block";
            } else {
                element.style.display = "none";
            }
        }
    </script>





