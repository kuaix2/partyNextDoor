// Function to open the modal
function openModal() {
    document.getElementById("myModal").style.display = "block";
}

// Function to close the modal
function closeModal() {
    document.getElementById("myModal").style.display = "none";
}

// Close the modal when the user clicks anywhere outside of it
window.onclick = function(event) {
    if (event.target === document.getElementById("myModal")) {
        closeModal();
    }
}

// Handle the form submission
document.getElementById("eventForm").onsubmit = function(event) {
    event.preventDefault(); // Prevent the default form submission
    // Here you would typically handle the event data, e.g., send it to a server
    alert("Event submitted successfully!");
    closeModal();
    // Optionally reset the form
    document.getElementById("eventForm").reset();
};