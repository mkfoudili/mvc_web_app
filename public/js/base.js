document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".sortable-table th").forEach((th, colIndex) => {
    th.style.cursor = "pointer";
    th.addEventListener("click", () => {
      const table = th.closest("table");
      const tbody = table.querySelector("tbody");
      const rows = Array.from(tbody.querySelectorAll("tr"));
      const isAsc = th.classList.toggle("asc");

      rows.sort((a, b) => {
        const cellA = a.children[colIndex].textContent.trim().toLowerCase();
        const cellB = b.children[colIndex].textContent.trim().toLowerCase();
        return isAsc ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
      });

      rows.forEach(row => tbody.appendChild(row));
    });
  });
});