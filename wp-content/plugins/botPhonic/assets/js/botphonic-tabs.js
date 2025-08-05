function botPhonicTabs() {
    const tabContainer = document.querySelector('.botphonic-tab-left ul');
    const listItems = document.querySelectorAll('.botphonic-tab-left ul li');
    const contentItems = document.querySelectorAll('.botphonic-tab-content');
    const prevArrow = document.getElementById('prevArrow');
    const nextArrow = document.getElementById('nextArrow');
    let activeIndex = 0;
    let isInitialLoad = true; // Track initial page load

    const updateActiveTab = (index) => {
        // Remove active class from all items
        listItems.forEach((item, idx) => {
            item.classList.toggle('active-product', idx === index);
        });

        contentItems.forEach((item, idx) => {
            item.classList.toggle('active', idx === index);
        });

        // Enable/Disable arrows
        // prevArrow.disabled = index === 0;
        nextArrow.disabled = index === listItems.length - 1;

        // Scroll the active tab into view (skip during initial load)
        if (!isInitialLoad) {
            listItems[index].scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'center',
            });
        }
    };

    prevArrow.addEventListener('click', () => {
        if (activeIndex > 0) {
            activeIndex--;
            updateActiveTab(activeIndex);
        }
    });

    nextArrow.addEventListener('click', () => {
        if (activeIndex < listItems.length - 1) {
            activeIndex++;
            updateActiveTab(activeIndex);
        }
    });

    // Add hover event listeners for individual list items
    listItems.forEach((item, index) => {
        item.addEventListener('mouseenter', () => {
            activeIndex = index;
            updateActiveTab(activeIndex);
        });
    });

    // Initialize first item as active
    updateActiveTab(activeIndex);
    isInitialLoad = false; // Set to false after initial setup
}


jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/botphonic-tabs.default', botPhonicTabs);
});