// script.js
$(document).ready(function () {
    let score = 0;
    const gameArea = $("#game-area");
    const target = $("#target");

    // Function to move target to a random position
    function moveTarget() {
        const maxX = gameArea.width() - target.width();
        const maxY = gameArea.height() - target.height();
        const newLeft = Math.floor(Math.random() * maxX);
        const newTop = Math.floor(Math.random() * maxY);

        target.animate({
            left: newLeft,
            top: newTop,
        }, 500);
    }

    // Increase score and move target on click
    target.on("click", function () {
        score += 10;
        $("#score").text(score);
        moveTarget();
    });

    // Initial target movement
    moveTarget();

    // Reset score when game area is clicked outside the target
    gameArea.on("click", function (e) {
        if (!$(e.target).is("#target")) {
            score = 0;
            $("#score").text(score);
        }
    });
});
