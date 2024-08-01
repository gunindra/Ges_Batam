const dialog = document.querySelector("dialog");

// Automatically show the popup when the page loads
window.addEventListener("load", function() {
  dialog.showModal();
});

// Close the popup when the close button is clicked
dialog.querySelector(".close-btn").addEventListener("click", function() {
  dialog.close();
});
