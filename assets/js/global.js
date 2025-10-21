/**
 * CAE Global JavaScript
 * Shared utilities and helpers
 */

(function () {
  "use strict";

  // Global CAE object
  window.CAE = window.CAE || {};

  /**
   * Debounce function for performance
   */
  CAE.debounce = function (func, wait, immediate) {
    var timeout;
    return function () {
      var context = this,
        args = arguments;
      var later = function () {
        timeout = null;
        if (!immediate) func.apply(context, args);
      };
      var callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
      if (callNow) func.apply(context, args);
    };
  };

  /**
   * Check if element is in viewport
   */
  CAE.isInViewport = function (element) {
    var rect = element.getBoundingClientRect();
    return (
      rect.top >= 0 &&
      rect.left >= 0 &&
      rect.bottom <=
        (window.innerHeight || document.documentElement.clientHeight) &&
      rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
  };

  /**
   * Add smooth scrolling to anchor links
   */
  CAE.initSmoothScroll = function () {
    document.addEventListener("click", function (e) {
      if (e.target.matches('a[href^="#"]')) {
        e.preventDefault();
        var target = document.querySelector(e.target.getAttribute("href"));
        if (target) {
          target.scrollIntoView({
            behavior: "smooth",
            block: "start",
          });
        }
      }
    });
  };

  // Initialize global features
  document.addEventListener("DOMContentLoaded", function () {
    CAE.initSmoothScroll();
  });
})();
