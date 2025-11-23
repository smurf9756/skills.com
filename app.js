const API_BASE = "/api"; // frontend expects backend under /api. Change if needed.

document.addEventListener("DOMContentLoaded", () => {
  // set year
  const yearEl = document.getElementById("year");
  if (yearEl) yearEl.textContent = new Date().getFullYear();

  // fetch popular skills and populate on landing
  const popular = document.getElementById("popular-skills");
  if (popular) {
    fetch(`${API_BASE}/skills?limit=6`)
      .then((r) => r.json())
      .then((data) => {
        const rows = data.rows || [];
        popular.innerHTML = rows
          .map(
            (s) => `
          <div class="skill">
            <h4>${escapeHtml(s.title)}</h4>
            <p class="line-clamp-3" style="color:var(--muted)">${escapeHtml(
              s.description || ""
            )}</p>
            <div style="display:flex;justify-content:space-between;margin-top:10px">
              <div style="font-size:13px;color:var(--muted)">By ${
                s.user?.username || "Unknown"
              }</div>
              <a href="skills.html" class="btn small">Browse</a>
            </div>
          </div>
        `
          )
          .join("");
      })
      .catch((err) => {
        console.error("Popular skills load error", err);
        popular.innerHTML = `<div class="center" style="color:var(--muted)">Could not load popular skills</div>`;
      });
  }
});

// small XSS-safe escaper
function escapeHtml(s) {
  if (!s) return "";
  return s.replace(
    /[&<>"']/g,
    (c) =>
      ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[
        c
      ])
  );
}
