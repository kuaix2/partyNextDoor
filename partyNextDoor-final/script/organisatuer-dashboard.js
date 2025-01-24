
function openModal() {
    document.getElementById("myModal").style.display = "block";
}


function closeModal() {
    document.getElementById("myModal").style.display = "none";
}


window.onclick = function(event) {
    if (event.target === document.getElementById("myModal")) {
        closeModal();
    }
}


document.getElementById("eventForm").onsubmit = function(event) {
    event.preventDefault(); // Prevent the default form submission
    // Here you would typically handle the event data, e.g., send it to a server
    alert("Event submitted successfully!");
    closeModal();
    // Optionally reset the form
    document.getElementById("eventForm").reset();
};