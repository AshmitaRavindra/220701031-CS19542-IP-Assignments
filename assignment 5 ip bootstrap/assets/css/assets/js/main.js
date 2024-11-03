document.addEventListener("DOMContentLoaded", function() {
    // Example: Display an alert when "Get Started" button is clicked
    const getStartedButton = document.querySelector(".btn-primary");
  
    if (getStartedButton) {
      getStartedButton.addEventListener("click", function() {
        alert("Welcome to TechDigitalStories! Let's get started.");
      });
    }
  
    // Example: Toggle visibility of extra blog content
    const readMoreButtons = document.querySelectorAll(".read-more");
  
    readMoreButtons.forEach(button => {
      button.addEventListener("click", function() {
        const content = this.previousElementSibling;
        content.classList.toggle("show");
        this.textContent = content.classList.contains("show") ? "Read Less" : "Read More";
      });
    });
  });
  