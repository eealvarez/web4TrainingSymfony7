document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.querySelector(".sidebar");
  const togglePin = document.createElement("button");
  let isPinned = false;

  // Configuración del botón para fijar el menú
  togglePin.textContent = "Fijar menú";
  togglePin.classList.add("toggle-pin");
  document.body.prepend(togglePin);

  togglePin.addEventListener("click", () => {
    isPinned = !isPinned;
    togglePin.textContent = isPinned ? "Desfijar menú" : "Fijar menú";
  });

  // Expandir el menú al pasar el mouse, si no está fijado
  sidebar.addEventListener("mouseenter", () => {
    if (!isPinned) {
      sidebar.classList.add("expanded");
    }
  });

  // Contraer el menú cuando el mouse salga, si no está fijado
  sidebar.addEventListener("mouseleave", () => {
    if (!isPinned) {
      sidebar.classList.remove("expanded");
    }
  });
});
