function scrollServices(direction) {
    const slider = document.getElementById('servicesSlider');
    if (!slider) return;

    const cardWidth = slider.querySelector('.service-card').offsetWidth + 16;

    slider.scrollBy({
        left: direction * cardWidth,
        behavior: 'smooth'
    });
}