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
