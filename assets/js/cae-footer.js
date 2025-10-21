/**
 * CAE Footer Widget JavaScript
 * Minimal interactions for footer functionality
 */

(function () {
  "use strict";

  // Initialize footer interactions when DOM is ready
  document.addEventListener("DOMContentLoaded", function () {
    initFooterAccessibility();
  });

  /**
   * Initialize accessibility enhancements for footer
   */
  function initFooterAccessibility() {
    const footerMenus = document.querySelectorAll(".cae-footer__menu");

    if (footerMenus.length === 0) return;

    footerMenus.forEach(function (menu) {
      // Add keyboard navigation support
      const menuItems = menu.querySelectorAll("a");

      menuItems.forEach(function (item, index) {
        // Add tabindex for better keyboard navigation
        item.setAttribute("tabindex", "0");

        // Add keyboard event listeners
        item.addEventListener("keydown", function (e) {
          // Handle Enter and Space keys
          if (e.key === "Enter" || e.key === " ") {
            e.preventDefault();
            this.click();
          }
        });
      });
    });
  }
})();
