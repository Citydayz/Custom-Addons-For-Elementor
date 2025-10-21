/**
 * CAE Card Widget JavaScript
 * Enhanced interactions for card hover effects and accessibility
 */

(function () {
  "use strict";

  // Initialize card interactions when DOM is ready
  document.addEventListener("DOMContentLoaded", function () {
    initCardAccessibility();
    initCardHoverEffects();
  });

  /**
   * Initialize accessibility enhancements for cards
   */
  function initCardAccessibility() {
    const cards = document.querySelectorAll(".cae-card");

    if (cards.length === 0) return;

    cards.forEach(function (card) {
      // Add keyboard navigation support
      if (card.tagName === "A") {
        card.setAttribute("tabindex", "0");

        // Add keyboard event listeners
        card.addEventListener("keydown", function (e) {
          // Handle Enter and Space keys
          if (e.key === "Enter" || e.key === " ") {
            e.preventDefault();
            this.click();
          }
        });
      }

      // Add ARIA attributes for better screen reader support
      const title = card.querySelector(".cae-card__title");
      const text = card.querySelector(".cae-card__text");

      if (title && text) {
        card.setAttribute(
          "aria-labelledby",
          title.id ||
            "cae-card-title-" + Math.random().toString(36).substr(2, 9)
        );
        if (!title.id) {
          title.id =
            "cae-card-title-" + Math.random().toString(36).substr(2, 9);
        }
      }
    });
  }

  /**
   * Initialize enhanced hover effects for cards
   */
  function initCardHoverEffects() {
    const cards = document.querySelectorAll(".cae-card");

    if (cards.length === 0) return;

    // Only apply enhanced effects if user hasn't requested reduced motion
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
      return;
    }

    cards.forEach(function (card) {
      // Add intersection observer for performance
      const observer = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              entry.target.classList.add("cae-card--in-view");
            }
          });
        },
        {
          threshold: 0.1,
          rootMargin: "0px 0px -50px 0px",
        }
      );

      observer.observe(card);

      // Enhanced hover effects
      card.addEventListener("mouseenter", function () {
        this.classList.add("cae-card--hovering");
      });

      card.addEventListener("mouseleave", function () {
        this.classList.remove("cae-card--hovering");
      });

      // Parallax effect for fixed background
      if (card.classList.contains("cae-card--parallax")) {
        initParallaxEffect(card);
      }
    });
  }

  /**
   * Initialize parallax effect for cards with fixed background
   */
  function initParallaxEffect(card) {
    window.addEventListener(
      "scroll",
      function () {
        const scrolled = window.pageYOffset;
        const rate = scrolled * -0.5;
        card.style.transform = "translateY(" + rate + "px)";
      },
      { passive: true }
    );
  }
})();
