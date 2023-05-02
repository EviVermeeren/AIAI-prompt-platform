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
  if (confirm("Are you sure you want to approve this prompt?")) {
    fetch("approvePrompt.php?id=" + id, {
      method: "GET",
    })
      .then((response) => response.text())
      .then((result) => {
        if (result === "success") {
          alert("Prompt approved successfully.");
          location.reload();
        }
      })
      .catch((error) => {
        console.error(error);
      });
  }
}

function deletePrompt(id) {
  if (confirm("Are you sure you want to delete this prompt?")) {
    fetch("rejectPrompt.php?id=" + id, {
      method: "GET",
    })
      .then((response) => response.text())
      .then((result) => {
        if (result === "success") {
          alert("Prompt deleted successfully.");
          location.reload();
        }
      })
      .catch((error) => {
        console.error(error);
      });
  }
}
