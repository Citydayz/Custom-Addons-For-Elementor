/**
 * CAE GDPR Consent Gate
 * Handles CookieYes integration and localStorage fallback for consent management
 */

(function () {
  "use strict";

  // Global consent state
  window.CAE_CONSENT_STATE = {
    analytics: false,
    marketing: false,
    functional: false,
  };

  /**
   * Load consent state from localStorage or CookieYes
   */
  function loadConsentState() {
    // Try CookieYes first
    if (typeof window.cookieyes !== "undefined" && window.cookieyes.consent) {
      // Map CookieYes categories to our format
      window.CAE_CONSENT_STATE = {
        analytics: window.cookieyes.consent.analytics || false,
        marketing: window.cookieyes.consent.marketing || false,
        functional: window.cookieyes.consent.functional || false,
      };
      return;
    }

    // Fallback to localStorage
    try {
      var stored = localStorage.getItem("cae_consent_state");
      if (stored) {
        var parsed = JSON.parse(stored);
        window.CAE_CONSENT_STATE = {
          analytics: parsed.analytics || false,
          marketing: parsed.marketing || false,
          functional: parsed.functional || false,
        };
      }
    } catch (e) {
      // Invalid JSON or localStorage not available
      console.warn("CAE: Could not load consent state from localStorage");
    }
  }

  /**
   * Save consent state to localStorage
   */
  function saveConsentState() {
    try {
      localStorage.setItem(
        "cae_consent_state",
        JSON.stringify(window.CAE_CONSENT_STATE)
      );
    } catch (e) {
      console.warn("CAE: Could not save consent state to localStorage");
    }
  }

  /**
   * Apply consent state to gated scripts
   */
  function applyConsentState() {
    var categories = Object.keys(window.CAE_CONSENT_STATE);

    categories.forEach(function (category) {
      if (window.CAE_CONSENT_STATE[category]) {
        enableCategory(category);
      }
    });
  }

  /**
   * Enable scripts for a specific category
   */
  function enableCategory(category) {
    var nodes = document.querySelectorAll(
      '[data-cae-consent="' + category + '"]'
    );

    nodes.forEach(function (node) {
      if (node.type === "text/plain") {
        var script = document.createElement("script");

        // Copy attributes
        for (var i = 0; i < node.attributes.length; i++) {
          var attr = node.attributes[i];
          if (attr.name !== "data-cae-consent") {
            script.setAttribute(attr.name, attr.value);
          }
        }

        // Set content
        script.textContent = node.textContent;

        // Replace the placeholder
        node.parentNode.replaceChild(script, node);
      }
    });
  }

  /**
   * Update consent state and reapply
   */
  window.CAE_CONSENT_APPLY = function (newState) {
    // Update state
    Object.keys(newState).forEach(function (category) {
      if (window.CAE_CONSENT_STATE.hasOwnProperty(category)) {
        window.CAE_CONSENT_STATE[category] = newState[category];
      }
    });

    // Save to localStorage
    saveConsentState();

    // Reapply consent
    applyConsentState();
  };

  /**
   * Listen for CookieYes consent changes
   */
  function initCookieYesListener() {
    if (typeof window.cookieyes !== "undefined") {
      // Listen for consent changes
      document.addEventListener("cookieyes_consent_update", function (e) {
        if (e.detail && e.detail.consent) {
          window.CAE_CONSENT_APPLY({
            analytics: e.detail.consent.analytics || false,
            marketing: e.detail.consent.marketing || false,
            functional: e.detail.consent.functional || false,
          });
        }
      });
    }

    // Also listen for CookieYes initialization
    if (typeof window.cookieyes !== "undefined" && window.cookieyes.consent) {
      window.CAE_CONSENT_APPLY({
        analytics: window.cookieyes.consent.analytics || false,
        marketing: window.cookieyes.consent.marketing || false,
        functional: window.cookieyes.consent.functional || false,
      });
    }
  }

  /**
   * Initialize consent management
   */
  function init() {
    loadConsentState();
    applyConsentState();
    initCookieYesListener();
  }

  // Initialize when DOM is ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
