const header = document.getElementById("site-header");
const menuToggle = document.getElementById("menu-toggle");
const siteNav = document.getElementById("site-nav");
const yearNode = document.getElementById("year");
const studiesTrack = document.getElementById("studies-track");
const prevButton = document.getElementById("prev-study");
const nextButton = document.getElementById("next-study");
const testimonialsTrack = document.getElementById("testimonials-track");
const prevTestimonialButton = document.getElementById("prev-testimonial");
const nextTestimonialButton = document.getElementById("next-testimonial");

if (yearNode) {
  yearNode.textContent = new Date().getFullYear();
}

const syncHeader = () => {
  if (!header) return;
  header.classList.toggle("scrolled", window.scrollY > 10);
};

syncHeader();
window.addEventListener("scroll", syncHeader, { passive: true });

if (menuToggle && siteNav) {
  const dropdowns = Array.from(siteNav.querySelectorAll(".nav-dropdown"));

  const closeDropdowns = () => {
    dropdowns.forEach((dropdown) => {
      dropdown.classList.remove("open");
      const toggle = dropdown.querySelector(".nav-dropdown-toggle");
      if (toggle) {
        toggle.setAttribute("aria-expanded", "false");
      }
    });
  };

  const closeMenu = () => {
    menuToggle.setAttribute("aria-expanded", "false");
    siteNav.classList.remove("open");
    document.body.classList.remove("menu-open");
    closeDropdowns();
  };

  menuToggle.addEventListener("click", () => {
    const isOpen = siteNav.classList.toggle("open");
    menuToggle.setAttribute("aria-expanded", String(isOpen));
    document.body.classList.toggle("menu-open", isOpen);
  });

  siteNav.querySelectorAll(".nav-dropdown-toggle").forEach((toggle) => {
    toggle.addEventListener("click", (event) => {
      event.preventDefault();
      event.stopPropagation();

      const dropdown = toggle.closest(".nav-dropdown");
      if (!dropdown) return;

      const shouldOpen = !dropdown.classList.contains("open");
      closeDropdowns();

      if (shouldOpen) {
        dropdown.classList.add("open");
        toggle.setAttribute("aria-expanded", "true");
      }
    });
  });

  siteNav.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      closeDropdowns();
      closeMenu();
    });
  });

  document.addEventListener("click", (event) => {
    if (!event.target.closest(".nav-dropdown")) {
      closeDropdowns();
    }
  });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      closeDropdowns();
      closeMenu();
    }
  });
}

const createAutoplayControls = (track, move, autoplayMs = 0) => {
  if (!autoplayMs) return;

  const prefersReducedMotion = window.matchMedia(
    "(prefers-reduced-motion: reduce)"
  ).matches;
  let autoplayId = null;

  const startAutoplay = () => {
    if (prefersReducedMotion || autoplayId) return;
    autoplayId = window.setInterval(() => move(1), autoplayMs);
  };

  const stopAutoplay = () => {
    if (!autoplayId) return;
    window.clearInterval(autoplayId);
    autoplayId = null;
  };

  track.addEventListener("mouseenter", stopAutoplay);
  track.addEventListener("mouseleave", startAutoplay);
  track.addEventListener("focusin", stopAutoplay);
  track.addEventListener("focusout", startAutoplay);

  startAutoplay();
};

const initScrollCarousel = ({
  track,
  prevBtn,
  nextBtn,
  itemSelector,
  autoplayMs = 0,
}) => {
  if (!track || !prevBtn || !nextBtn) return;

  const getItems = () => Array.from(track.querySelectorAll(itemSelector));

  const findNearestIndex = () => {
    const items = getItems();
    if (!items.length) return 0;

    let nearestIndex = 0;
    let minDistance = Number.POSITIVE_INFINITY;
    const currentLeft = track.scrollLeft;

    items.forEach((item, index) => {
      const distance = Math.abs(item.offsetLeft - currentLeft);
      if (distance < minDistance) {
        minDistance = distance;
        nearestIndex = index;
      }
    });

    return nearestIndex;
  };

  const scrollToIndex = (index) => {
    const items = getItems();
    if (!items.length) return 0;

    const normalizedIndex = (index + items.length) % items.length;
    track.scrollTo({
      left: items[normalizedIndex].offsetLeft,
      behavior: "smooth",
    });
    return normalizedIndex;
  };

  let currentIndex = findNearestIndex();

  const move = (direction = 1) => {
    currentIndex = findNearestIndex();
    currentIndex = scrollToIndex(currentIndex + direction);
  };

  prevBtn.addEventListener("click", () => move(-1));
  nextBtn.addEventListener("click", () => move(1));

  createAutoplayControls(track, move, autoplayMs);
};

const initInfiniteScrollCarousel = ({
  track,
  prevBtn,
  nextBtn,
  itemSelector,
  autoplayMs = 0,
  cloneCount = 1,
}) => {
  if (!track || !prevBtn || !nextBtn) return;

  const originalItems = Array.from(track.querySelectorAll(itemSelector));
  if (originalItems.length <= 1) {
    initScrollCarousel({ track, prevBtn, nextBtn, itemSelector, autoplayMs });
    return;
  }

  const safeCloneCount = Math.min(cloneCount, originalItems.length - 1);
  const createClone = (item) => {
    const clone = item.cloneNode(true);
    clone.classList.add("is-clone");
    clone.setAttribute("aria-hidden", "true");
    clone
      .querySelectorAll(
        "a, button, input, select, textarea, [tabindex]"
      )
      .forEach((node) => {
        node.setAttribute("tabindex", "-1");
      });
    return clone;
  };

  const leadingClones = originalItems
    .slice(-safeCloneCount)
    .map(createClone);
  const trailingClones = originalItems
    .slice(0, safeCloneCount)
    .map(createClone);

  leadingClones.forEach((clone) => track.insertBefore(clone, track.firstChild));
  trailingClones.forEach((clone) => track.appendChild(clone));

  const getItems = () => Array.from(track.querySelectorAll(itemSelector));
  const realCount = originalItems.length;
  let currentIndex = safeCloneCount;
  let normalizeTimer = null;
  let scrollStopTimer = null;

  const scrollToIndex = (index, behavior = "smooth") => {
    const items = getItems();
    if (!items.length) return;
    const clampedIndex = Math.max(0, Math.min(index, items.length - 1));
    track.scrollTo({
      left: items[clampedIndex].offsetLeft,
      behavior,
    });
  };

  const normalizeIndex = () => {
    if (currentIndex >= safeCloneCount + realCount) {
      currentIndex = safeCloneCount;
      scrollToIndex(currentIndex, "auto");
      return;
    }
    if (currentIndex < safeCloneCount) {
      currentIndex = safeCloneCount + realCount - 1;
      scrollToIndex(currentIndex, "auto");
    }
  };

  const scheduleNormalize = () => {
    window.clearTimeout(normalizeTimer);
    normalizeTimer = window.setTimeout(normalizeIndex, 450);
  };

  const findNearestIndex = () => {
    const items = getItems();
    if (!items.length) return currentIndex;
    let nearestIndex = currentIndex;
    let minDistance = Number.POSITIVE_INFINITY;
    const currentLeft = track.scrollLeft;

    items.forEach((item, index) => {
      const distance = Math.abs(item.offsetLeft - currentLeft);
      if (distance < minDistance) {
        minDistance = distance;
        nearestIndex = index;
      }
    });

    return nearestIndex;
  };

  const move = (direction = 1) => {
    currentIndex += direction;
    scrollToIndex(currentIndex, "smooth");
    scheduleNormalize();
  };

  scrollToIndex(currentIndex, "auto");

  prevBtn.addEventListener("click", () => move(-1));
  nextBtn.addEventListener("click", () => move(1));

  track.addEventListener(
    "scroll",
    () => {
      window.clearTimeout(scrollStopTimer);
      scrollStopTimer = window.setTimeout(() => {
        currentIndex = findNearestIndex();
        normalizeIndex();
      }, 120);
    },
    { passive: true }
  );

  window.addEventListener("resize", () => {
    scrollToIndex(currentIndex, "auto");
  });

  createAutoplayControls(track, move, autoplayMs);
};

initInfiniteScrollCarousel({
  track: studiesTrack,
  prevBtn: prevButton,
  nextBtn: nextButton,
  itemSelector: ".study-card",
  autoplayMs: 5000,
  cloneCount: 1,
});

initScrollCarousel({
  track: testimonialsTrack,
  prevBtn: prevTestimonialButton,
  nextBtn: nextTestimonialButton,
  itemSelector: "blockquote",
  autoplayMs: 5500,
});
