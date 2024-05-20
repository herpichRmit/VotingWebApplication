// Get all elements with the class 'dot'
const dots = document.querySelectorAll('.dot');

// Function to generate a random animation duration and delay
function randomAnimation() {
    return (Math.random() * 2 + 5 + 's'); // Adjust the range and units as needed
}

dots.forEach((dot, index) => {
    const animationDuration = randomAnimation();
    const animationDelay = `-${index * 2}s`; // Adjust the delay as needed
    dot.style.setProperty('--animation-duration', animationDuration);
    dot.style.animationDelay = animationDelay;
});