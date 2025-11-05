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

  /**
   * CAE Debug Helper
   * Safe logging system that only logs when enabled
   */
  window.CAE_DEBUG = (function () {
    function isEnabled() {
      try {
        return (
          typeof window !== "undefined" &&
          window.localStorage.getItem("CAE_DEBUG") === "1"
        );
      } catch (e) {
        return false;
      }
    }

    function tag(level) {
      return "[CAE][" + level.toUpperCase() + "]";
    }

    function safe(obj) {
      try {
        return JSON.stringify(obj);
      } catch (e) {
        return "[unserializable]";
      }
    }

    return {
      enable: function () {
        try {
          localStorage.setItem("CAE_DEBUG", "1");
        } catch (e) {
          // Ignore if localStorage not available
        }
      },
      disable: function () {
        try {
          localStorage.removeItem("CAE_DEBUG");
        } catch (e) {
          // Ignore if localStorage not available
        }
      },
      log: function (level, msg, ctx) {
        if (!isEnabled()) {
          return;
        }
        var line = tag(level) + " " + msg + (ctx ? " | ctx=" + safe(ctx) : "");
        if (console && console[level]) {
          console[level](line);
        } else if (console && console.log) {
          console.log(line);
        }
      },
    };
  })();

  // Initialize global features
  document.addEventListener("DOMContentLoaded", function () {
    CAE.initSmoothScroll();
  });
})();
