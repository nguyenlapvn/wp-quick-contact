document.addEventListener('DOMContentLoaded', function() {
    // Get main container
    const container = document.querySelector('.wpqc-container');
    if (!container) return;

    // Get toggle button
    const toggle = container.querySelector('.wpqc-toggle');
    if (!toggle) return;

    // Toggle active class on click
    toggle.addEventListener('click', function(e) {
        e.stopPropagation();
        container.classList.toggle('active');
    });

    // Close when clicking outside
    document.addEventListener('click', function(e) {
        if (!container.contains(e.target)) {
            container.classList.remove('active');
        }
    });

    // Prevent closing when clicking inside the buttons container
    const buttonsContainer = container.querySelector('.wpqc-buttons');
    if (buttonsContainer) {
        buttonsContainer.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // Optional: Close when pressing ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            container.classList.remove('active');
        }
    });

    // Optional: Close when scrolling (uncomment if needed)
    /*
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(function() {
            container.classList.remove('active');
        }, 150);
    });
    */
});