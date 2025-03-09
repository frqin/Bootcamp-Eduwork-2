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






