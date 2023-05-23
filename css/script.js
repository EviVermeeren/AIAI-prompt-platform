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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Define the flagUser function
function reportUser() {
  // Get the ID of the user to flag from the query string
  const urlParams = new URLSearchParams(window.location.search);
  const id = urlParams.get("id");

  // Show a confirmation dialog to the user before proceeding
  if (confirm("Are you sure you want to flag this user?")) {
    // Send a GET request to the server to flag the user
    fetch(`reportUser.php?id=${id}`, {
      method: "GET",
    })
      // Parse the response as text
      .then((response) => response.text())
      // Check if the response was successful
      .then((result) => {
        if (result === "success") {
          // Alert the user that the user was flagged successfully
          alert("User flagged successfully.");
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

function reportPrompt(promptId) {
  fetch(`report_prompt.php?id=${promptId}`)
    .then((response) => {
      if (response.ok) {
        alert("Prompt reported successfully");
      } else {
        alert("Error reporting prompt");
      }
    })
    .catch((error) => {
      console.error("Error reporting prompt:", error);
      alert("Error reporting prompt");
    });
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function flagUser() {
  // Get the user ID from the URL query string
  const urlParams = new URLSearchParams(window.location.search);
  const userId = urlParams.get("id");

  // Show a confirmation dialog to the user before proceeding
  if (confirm("Are you sure you want to flag this user?")) {
    // Send a GET request to the server to flag the user
    fetch(`reportUser.php?id=${userId}`, {
      method: "GET",
    })
      // Parse the response as text
      .then((response) => response.text())
      // Check if the response was successful
      .then((result) => {
        if (result === "success") {
          // Alert the user that the user was flagged successfully
          alert("User flagged successfully.");
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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// This function frees a user with the given ID
function freeUser(id) {
  // Show a confirmation dialog to the user before proceeding
  if (confirm("Are you sure you want to free this user?")) {
    // Send a GET request to the server to free the user
    fetch("free_user.php?id=" + id, {
      method: "GET",
    })
      // Parse the response as text
      .then((response) => response.text())
      // Check if the response was successful
      .then((result) => {
        if (result === "success") {
          // Alert the user that the user was freed successfully
          alert("User freed successfully.");
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

// This function bans a user with the given ID
function banUser(id) {
  // Show a confirmation dialog to the user before proceeding
  if (confirm("Are you sure you want to ban this user?")) {
    // Send a GET request to the server to ban the user
    fetch("banUser.php?id=" + id, {
      method: "GET",
    })
      // Parse the response as text
      .then((response) => response.text())
      // Check if the response was successful
      .then((result) => {
        if (result === "success") {
          // Alert the user that the user was banned successfully
          alert("User banned successfully.");
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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// This function frees a user with the given ID
function unBlock(id) {
  // Show a confirmation dialog to the user before proceeding
  if (confirm("Are you sure you want to unblock this user?")) {
    // Send a GET request to the server to unblock the user
    fetch("unBlock_User.php?id=" + id)
      .then((response) => {
        if (response.ok) {
          // Alert the user that the user was unblocked successfully
          alert("User unblocked successfully.");
          // Reload the page to reflect the changes
          location.reload();
        } else {
          // Display an error message
          alert("Error unblocking user.");
        }
      })
      .catch((error) => {
        console.error(error);
        alert("Error unblocking user.");
      });
  }
}
