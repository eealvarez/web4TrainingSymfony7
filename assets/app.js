document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.querySelector(".sidebar");
  const toggleButton = document.createElement("button");

  toggleButton.textContent = "Menú";
  toggleButton.classList.add("toggle-btn");

  toggleButton.addEventListener("click", () => {
    sidebar.classList.toggle("hidden");
  });

  document.body.prepend(toggleButton);
});
