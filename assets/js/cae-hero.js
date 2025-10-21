/**
 * CAE Hero Widget JavaScript
 * Minimal interactions for hero section
 */

(function () {
  "use strict";

  // Initialize hero interactions when DOM is ready
  document.addEventListener("DOMContentLoaded", function () {
    initHeroParallax();
  });

  /**
   * Initialize subtle parallax effect for background images
   */
  function initHeroParallax() {
    const heroElements = document.querySelectorAll(".cae-hero");

    if (heroElements.length === 0) return;

    // Only apply parallax if user hasn't requested reduced motion
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
      return;
    }

    heroElements.forEach(function (hero) {
      const backgroundImage = hero.style.backgroundImage;

      // Only apply parallax to elements with background images
      if (!backgroundImage || backgroundImage === "none") {
        return;
      }

      // Add scroll listener for parallax effect
      window.addEventListener(
        "scroll",
        function () {
          const scrolled = window.pageYOffset;
          const rate = scrolled * -0.5;

          hero.style.transform = "translateY(" + rate + "px)";
        },
        { passive: true }
      );
    });
  }
})();
