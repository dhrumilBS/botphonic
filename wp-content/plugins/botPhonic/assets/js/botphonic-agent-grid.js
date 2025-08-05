function botPhonicAgentGrid() { 
    const boxes = document.querySelectorAll(".ai-voices-agents-box");
    const isMobile = window.innerWidth <= 1200; // Adjust breakpoint as needed

    if (!boxes.length) return;

    // Reset all boxes first
    boxes.forEach(box => box.classList.remove("open"));

    if (isMobile) {
        // On mobile, all boxes open, no toggle
        boxes.forEach(box => box.classList.add("open"));
    } else {
        // On desktop/tablet: open first only
        boxes[0].classList.add("open");

        boxes.forEach(box => {
            box.addEventListener("click", function (e) {
                // Ignore click on play button
                if (e.target.closest(".playbutton")) return;

                const isAlreadyOpen = box.classList.contains("open");

                boxes.forEach(b => b.classList.remove("open"));
                if (!isAlreadyOpen) {
                    box.classList.add("open");
                }
            });
        });
    }

    // Audio play/pause logic
    const playButtons = document.querySelectorAll(".playbutton");

    playButtons.forEach(btn => {
        const audio = btn.closest(".audio_fill").querySelector("audio");

        btn.addEventListener("click", function (e) {
            e.stopPropagation(); // Prevent box toggle

            document.querySelectorAll("audio").forEach(a => {
                if (a !== audio) {
                    a.pause();
                    const otherBtn = a.closest(".audio_fill").querySelector(".playbutton");
                    if (otherBtn) otherBtn.classList.remove("playing");
                }
            });

            if (audio.paused) {
                audio.play();
                btn.classList.add("playing");
            } else {
                audio.pause();
                btn.classList.remove("playing");
            }

            audio.onended = () => {
                btn.classList.remove("playing");
            };
        });
    });
}

// Elementor widget hook
jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction(
        'frontend/element_ready/botphonic-audio-agent-grid.default',
        botPhonicAgentGrid
    );
});

// Optional: handle dynamic resize (optional but safe)
window.addEventListener("resize", () => {
    botPhonicAgentGrid();
});
