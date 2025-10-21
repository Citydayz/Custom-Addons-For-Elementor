/**
 * CAE Repeater Section Widget JavaScript
 * Minimal interactions for repeater section functionality
 */

(function () {
  "use strict";

  // Initialize repeater section interactions when DOM is ready
  document.addEventListener("DOMContentLoaded", function () {
    initRepeaterAnimations();
  });

  /**
   * Initialize subtle animations for repeater items
   */
  function initRepeaterAnimations() {
    const repeaterItems = document.querySelectorAll(".cae-repeater__item");

    if (repeaterItems.length === 0) return;

    // Only apply animations if user hasn't requested reduced motion
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
      return;
    }

    // Create intersection observer for scroll animations
    const observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add("cae-repeater__item--visible");
          }
        });
      },
      {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px",
      }
    );

    // Observe all repeater items
    repeaterItems.forEach(function (item) {
      observer.observe(item);
    });
  }
})();
