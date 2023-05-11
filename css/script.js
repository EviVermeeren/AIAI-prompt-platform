// Function to copy text to clipboard
function copyToClipboard(text) {
  // Create a new <input> element to hold the text
  const input = document.createElement("input");
  // Set the value of the input to the text to be copied
  input.setAttribute("value", text);
  // Append the input element to the <body> element
  document.body.appendChild(input);
  // Select the input element
  input.select();
  // Copy the selected text to the clipboard
  document.execCommand("copy");
  // Remove the input element from the <body> element
  document.body.removeChild(input);
  // Alert the user that the link has been copied to the clipboard
  alert("Link copied to clipboard!");
}

// Function to approve a prompt
function approvePrompt(id) {
  // Show a confirmation dialog to the user before proceeding
  if (confirm("Are you sure you want to approve this prompt?")) {
    // Send a GET request to the server to approve the prompt
    fetch("approvePrompt.php?id=" + id, {
      method: "GET"
    })
    // Parse the response as text
    .then(response => response.text())
    // Check if the response was successful
    .then(result => {
      if (result === "success") {
        // Alert the user that the prompt was approved successfully
        alert("Prompt approved successfully.");
        // Reload the page to reflect the changes
        location.reload();
      }
    })
    // Log any errors that occur during the request
    .catch(error => {
      console.error(error);
    });
  }
}

// Function to delete a prompt
function deletePrompt(id) {
  // Show a confirmation dialog to the user before proceeding
  if (confirm("Are you sure you want to delete this prompt?")) {
    // Send a GET request to the server to delete the prompt
    fetch("rejectPrompt.php?id=" + id, {
      method: "GET"
    })
    // Parse the response as text
    .then(response => response.text())
    // Check if the response was successful
    .then(result => {
      if (result === "success") {
        // Alert the user that the prompt was deleted successfully
        alert("Prompt deleted successfully.");
        // Reload the page to reflect the changes
        location.reload();
      }
    })
    // Log any errors that occur during the request
    .catch(error => {
      console.error(error);
    });
  }
}

// Function to handle the search bar
document.getElementById("search-data").addEventListener("keyup", function(event) {
  // Prevent the default behavior of the enter key
  event.preventDefault();
  // Check if the enter key was pressed
  if (event.keyCode === 13) {
    // Get the search term from the input element
    const searchTerm = encodeURIComponent(document.getElementById("search-data").value.trim());
    // Check if a search term was entered
    if (searchTerm) {
      // Redirect the user to the search page with the search term as a query parameter
      window.location.href = "searchPrompt.php?q=" + searchTerm;
    }
  }
});
