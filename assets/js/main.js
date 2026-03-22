// Script principal du front : menu mobile et quelques effets d interaction sur les cartes.
// Menu burger pour mobile
document.addEventListener("DOMContentLoaded", () => {
  const canUseTilt =
    window.matchMedia("(hover: hover)").matches &&
    !window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const isFlatWorkspace = document.body.classList.contains("workspace-flat");

  const burger = document.querySelector(".burger");
  const navMobile = document.querySelector(".nav-mobile");

  if (burger && navMobile) {
    burger.addEventListener("click", () => {
      navMobile.classList.toggle("open");
      const spans = burger.querySelectorAll("span");
      if (navMobile.classList.contains("open")) {
        spans[0].style.transform = "translateY(4px) rotate(45deg)";
        spans[1].style.opacity = "0";
        spans[2].style.transform = "translateY(-4px) rotate(-45deg)";
      } else {
        spans.forEach((s) => {
          s.style.transform = "";
          s.style.opacity = "";
        });
      }
    });
  }

  if (canUseTilt && !isFlatWorkspace) {
    const tiltTargets = Array.from(document.querySelectorAll(
      ".hero-card, .offer-card, .side-card, .dash-card, .entry-card, .entry-reboot-panel, .btn-primary"
    ));

    tiltTargets.forEach((el) => {
      el.classList.add("tilt-3d");

      const updateTilt = (event) => {
        const rect = el.getBoundingClientRect();
        const relX = (event.clientX - rect.left) / rect.width;
        const relY = (event.clientY - rect.top) / rect.height;
        const rotateY = (relX - 0.5) * 10;
        const rotateX = (0.5 - relY) * 8;

        el.style.setProperty("--tilt-x", `${rotateX.toFixed(2)}deg`);
        el.style.setProperty("--tilt-y", `${rotateY.toFixed(2)}deg`);
      };

      el.addEventListener("mouseenter", () => el.classList.add("is-hovered"));
      el.addEventListener("mousemove", updateTilt);
      el.addEventListener("mouseleave", () => {
        el.classList.remove("is-hovered");
        el.style.setProperty("--tilt-x", "0deg");
        el.style.setProperty("--tilt-y", "0deg");
      });
    });
  }
});
