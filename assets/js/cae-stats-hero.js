/**
 * CAE Stats Hero - Counter Animation
 *
 * Agent 0 implementation for smooth counter animations
 * Features:
 * - Intersection Observer for performance
 * - Easing functions for smooth animations
 * - Support for numbers, percentages, and custom formats
 * - Configurable duration and delay
 * - Responsive and accessible
 */

(function ($) {
  "use strict";

  // Script re-enabled after fixing template syntax error

  // Exit if jQuery is not available
  if (typeof $ === "undefined") {
    return;
  }

  // Multiple checks to ensure we're not in Elementor editor
  if (
    typeof elementorFrontend !== "undefined" &&
    elementorFrontend.isEditMode()
  ) {
    return;
  }

  // Check for editor classes
  if (
    document.body.classList.contains("elementor-editor-active") ||
    document.body.classList.contains("elementor-editor") ||
    document.documentElement.classList.contains("elementor-editor")
  ) {
    return;
  }

  // Check for editor URL parameters
  if (
    window.location.search.includes("elementor") ||
    window.location.search.includes("action=elementor")
  ) {
    return;
  }

  // Check for editor iframe
  if (window.self !== window.top) {
    return;
  }

  /**
   * Counter Animation Class
   * Handles smooth number counting animations
   */
  class CaeCounterAnimation {
    constructor(element, options = {}) {
      this.element = element;
      this.options = {
        duration: 2000,
        delay: 200,
        easing: "easeOutCubic",
        separator: "",
        decimal: ".",
        prefix: "",
        suffix: "",
        ...options,
      };

      this.isAnimating = false;
      this.hasAnimated = false;
      this.observer = null;

      this.init();
    }

    /**
     * Initialize the counter animation
     */
    init() {
      if (!this.element) return;

      this.setupIntersectionObserver();
      this.prepareElement();
    }

    /**
     * Setup Intersection Observer for performance
     */
    setupIntersectionObserver() {
      if (!("IntersectionObserver" in window)) {
        // Fallback for older browsers
        this.startAnimation();
        return;
      }

      this.observer = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting && !this.hasAnimated) {
              this.startAnimation();
              this.hasAnimated = true;
            }
          });
        },
        {
          threshold: 0.1,
          rootMargin: "50px",
        }
      );

      this.observer.observe(this.element);
    }

    /**
     * Prepare element for animation
     */
    prepareElement() {
      const text = this.element.textContent.trim();
      this.originalText = text;
      this.targetValue = this.parseValue(text);
      this.startValue = 0;

      // Set initial value to 0
      this.element.textContent = this.formatValue(0);
    }

    /**
     * Parse value from text (handles numbers, percentages, etc.)
     */
    parseValue(text) {
      // Remove common prefixes/suffixes and parse number
      const cleanText = text.replace(/[^\d.,]/g, "");
      const number = parseFloat(cleanText.replace(",", "."));
      return isNaN(number) ? 0 : number;
    }

    /**
     * Format value for display
     */
    formatValue(value) {
      const formatted = Math.floor(value).toString();

      // Add separators if needed
      let result = formatted;
      if (this.options.separator && formatted.length > 3) {
        result = formatted.replace(
          /\B(?=(\d{3})+(?!\d))/g,
          this.options.separator
        );
      }

      return this.options.prefix + result + this.options.suffix;
    }

    /**
     * Start the counter animation
     */
    startAnimation() {
      if (this.isAnimating) return;

      this.isAnimating = true;

      // Add delay if specified
      setTimeout(() => {
        this.animate();
      }, this.options.delay);
    }

    /**
     * Animate the counter
     */
    animate() {
      const startTime = performance.now();
      const duration = this.options.duration;

      const animateFrame = (currentTime) => {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);

        // Apply easing
        const easedProgress = this.ease(this.options.easing, progress);

        // Calculate current value
        const currentValue =
          this.startValue +
          (this.targetValue - this.startValue) * easedProgress;

        // Update display
        this.element.textContent = this.formatValue(currentValue);

        // Continue animation
        if (progress < 1) {
          requestAnimationFrame(animateFrame);
        } else {
          this.isAnimating = false;
          this.element.textContent = this.originalText; // Restore original text
        }
      };

      requestAnimationFrame(animateFrame);
    }

    /**
     * Easing functions
     */
    ease(type, t) {
      const easingFunctions = {
        linear: (t) => t,
        easeInQuad: (t) => t * t,
        easeOutQuad: (t) => t * (2 - t),
        easeInOutQuad: (t) => (t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t),
        easeInCubic: (t) => t * t * t,
        easeOutCubic: (t) => --t * t * t + 1,
        easeInOutCubic: (t) =>
          t < 0.5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1,
        easeInQuart: (t) => t * t * t * t,
        easeOutQuart: (t) => 1 - --t * t * t * t,
        easeInOutQuart: (t) =>
          t < 0.5 ? 8 * t * t * t * t : 1 - 8 * --t * t * t * t,
        easeInQuint: (t) => t * t * t * t * t,
        easeOutQuint: (t) => 1 + --t * t * t * t * t,
        easeInOutQuint: (t) =>
          t < 0.5 ? 16 * t * t * t * t * t : 1 + 16 * --t * t * t * t * t,
      };

      return easingFunctions[type] ? easingFunctions[type](t) : t;
    }

    /**
     * Destroy the animation
     */
    destroy() {
      if (this.observer) {
        this.observer.disconnect();
      }
    }
  }

  /**
   * Initialize counter animations for CAE Stats Hero widgets
   */
  function initCaeCounterAnimations() {
    $(".cae-stats-hero__number").each(function () {
      const $this = $(this);
      const $widget = $this.closest(".elementor-widget-cae-stats-hero");

      // Skip if already initialized
      if ($this.data("cae-counter-initialized")) {
        return;
      }

      // Check for animation data attributes
      const hasAnimationData = $this.attr("data-cae-animation") === "true";
      if (!hasAnimationData) {
        return;
      }

      // Get animation settings from data attributes
      const duration = parseInt($this.attr("data-cae-duration")) || 2000;
      const delay = parseInt($this.attr("data-cae-delay")) || 200;
      const easing = $this.attr("data-cae-easing") || "easeOutCubic";

      // Initialize counter animation
      const counter = new CaeCounterAnimation(this, {
        duration: duration,
        delay: delay,
        easing: easing,
      });

      // Mark as initialized
      $this.data("cae-counter-initialized", true);
      $this.data("cae-counter-instance", counter);
    });
  }

  /**
   * Initialize on document ready
   */
  $(document).ready(function () {
    initCaeCounterAnimations();
  });

  /**
   * Re-initialize on Elementor frontend events
   */
  if (typeof elementorFrontend !== "undefined") {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/cae-stats-hero.default",
      function ($scope) {
        initCaeCounterAnimations();
      }
    );
  }

  /**
   * Re-initialize on window resize (for responsive changes)
   */
  $(window).on("resize", function () {
    setTimeout(initCaeCounterAnimations, 100);
  });
})(jQuery);
