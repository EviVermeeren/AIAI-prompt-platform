function copyToClipboard(text) {
  // https://stackoverflow.com/a/30810322/1082716
  const input = document.createElement("input"); // Create a <input> element
  input.setAttribute("value", text); // Set its value to the string that you want copied
  document.body.appendChild(input); // Append it to the <body> element
  input.select(); // Select it
  document.execCommand("copy"); // Copy its contents
  document.body.removeChild(input); // Remove it from the <body> element
  alert("Link copied to clipboard!"); // Alert the copied text
}

function approvePrompt(id) {
  // Show a confirmation dialog to the user before proceeding
  if (confirm("Are you sure you want to approve this prompt?")) {
    // Send a GET request to the server to approve the prompt
    fetch("approvePrompt.php?id=" + id, {
      method: "GET",
    })
      // Parse the response as text
      .then((response) => response.text())
      // Check if the response was successful
      .then((result) => {
        if (result === "success") {
          // Alert the user that the prompt was approved successfully
          alert("Prompt approved successfully.");
          // Reload the page to reflect the changes
          location.reload();
        }
      })
      // Log any errors that occur during the request
      .catch((error) => {
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
      method: "GET",
    })
      // Parse the response as text
      .then((response) => response.text())
      // Check if the response was successful
      .then((result) => {
        if (result === "success") {
          // Alert the user that the prompt was deleted successfully
          alert("Prompt deleted successfully.");
          // Reload the page to reflect the changes
          location.reload();
        }
      })
      // Log any errors that occur during the request
      .catch((error) => {
        console.error(error);
      });
  }
}

// Function to handle the search bar
document
  .getElementById("search-data")
  .addEventListener("keyup", function (event) {
    // Prevent the default behavior of the enter key
    event.preventDefault();
    // Check if the enter key was pressed
    if (event.keyCode === 13) {
      // Get the search term from the input element
      const searchTerm = encodeURIComponent(
        document.getElementById("search-data").value.trim()
      );
      // Check if a search term was entered
      if (searchTerm) {
        // Redirect the user to the search page with the search term as a query parameter
        window.location.href = "searchPrompt.php?q=" + searchTerm;
      }
    }
  });
