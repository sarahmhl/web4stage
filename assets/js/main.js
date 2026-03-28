document.addEventListener("DOMContentLoaded", () => {
  const canUseTilt =
    window.matchMedia("(hover: hover)").matches &&
    !window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const isFlatWorkspace = document.body.classList.contains("workspace-flat");

  const burger = document.querySelector(".burger");
  const navMobile = document.querySelector(".nav-mobile");

  if (burger && navMobile) {
    const spans = burger.querySelectorAll("span");
    const setBurgerState = (isOpen) => {
      navMobile.classList.toggle("open", isOpen);
      burger.setAttribute("aria-expanded", isOpen ? "true" : "false");

      if (isOpen) {
        spans[0].style.transform = "translateY(4px) rotate(45deg)";
        spans[1].style.opacity = "0";
        spans[2].style.transform = "translateY(-4px) rotate(-45deg)";
        return;
      }

      spans.forEach((span) => {
        span.style.transform = "";
        span.style.opacity = "";
      });
    };

    burger.addEventListener("click", () => {
      setBurgerState(!navMobile.classList.contains("open"));
    });

    navMobile.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => setBurgerState(false));
    });

    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape") {
        setBurgerState(false);
      }
    });

    const desktopMedia = window.matchMedia("(min-width: 769px)");
    const handleDesktopChange = (event) => {
      if (event.matches) {
        setBurgerState(false);
      }
    };

    if (typeof desktopMedia.addEventListener === "function") {
      desktopMedia.addEventListener("change", handleDesktopChange);
    } else if (typeof desktopMedia.addListener === "function") {
      desktopMedia.addListener(handleDesktopChange);
    }
  }

  if (canUseTilt && !isFlatWorkspace) {
    const tiltTargets = Array.from(
      document.querySelectorAll(
        ".hero-card, .offer-card, .side-card, .dash-card, .entry-card, .entry-reboot-panel, .btn-primary"
      )
    );

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

  const forms = Array.from(document.querySelectorAll("form[data-js-validate]"));
  forms.forEach((form) => {
    const fields = Array.from(form.querySelectorAll("input, textarea, select"));

    const validateField = (field) => {
      if (!(field instanceof HTMLInputElement || field instanceof HTMLTextAreaElement || field instanceof HTMLSelectElement)) {
        return true;
      }

      field.setCustomValidity("");

      if (field.disabled || field.type === "hidden") {
        return true;
      }

      const value = field.value.trim();

      if (field.required && value === "") {
        field.setCustomValidity("Merci de renseigner ce champ.");
        return false;
      }

      if (field instanceof HTMLInputElement && field.type === "email" && value !== "") {
        const isValidEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
        if (!isValidEmail) {
          field.setCustomValidity("Merci de saisir une adresse e-mail valide.");
          return false;
        }
      }

      if (field instanceof HTMLInputElement && field.type === "password" && field.minLength > 0 && value !== "" && value.length < field.minLength) {
        field.setCustomValidity(`Merci de saisir au moins ${field.minLength} caracteres.`);
        return false;
      }

      if (field instanceof HTMLInputElement && field.type === "number" && value !== "") {
        const numericValue = Number(field.value);
        if (Number.isNaN(numericValue)) {
          field.setCustomValidity("Merci de saisir une valeur numerique valide.");
          return false;
        }
        if (field.min !== "" && numericValue < Number(field.min)) {
          field.setCustomValidity(`La valeur minimale autorisee est ${field.min}.`);
          return false;
        }
      }

      if (field instanceof HTMLInputElement && field.type === "file" && field.files && field.files.length > 0) {
        const file = field.files[0];
        const accept = field.getAttribute("accept");
        const maxBytes = Number(field.dataset.maxBytes || "0");

        if (accept) {
          const allowedExtensions = accept
            .split(",")
            .map((item) => item.trim().toLowerCase())
            .filter(Boolean);
          const lowerName = file.name.toLowerCase();
          const isAllowed = allowedExtensions.some((extension) => lowerName.endsWith(extension));
          if (!isAllowed) {
            field.setCustomValidity("Format de fichier non autorise.");
            return false;
          }
        }

        if (maxBytes > 0 && file.size > maxBytes) {
          field.setCustomValidity("Le fichier depasse la taille maximale autorisee.");
          return false;
        }
      }

      return true;
    };

    fields.forEach((field) => {
      field.addEventListener("input", () => {
        validateField(field);
      });
      field.addEventListener("change", () => {
        validateField(field);
      });
    });

    form.addEventListener("submit", (event) => {
      let firstInvalidField = null;

      fields.forEach((field) => {
        const isValid = validateField(field);
        if (!isValid && firstInvalidField === null) {
          firstInvalidField = field;
        }
      });

      if (firstInvalidField !== null) {
        event.preventDefault();
        firstInvalidField.reportValidity();
        firstInvalidField.focus();
      }
    });
  });
});
