/**
 * CAE Newsletter Widget JavaScript
 * Handles form submission via AJAX with accessibility support.
 */

(function () {
  "use strict";

  /**
   * Initialize newsletter forms on the page
   */
  function initNewsletterForms() {
    const forms = document.querySelectorAll(".cae-newsletter__form");

    forms.forEach(function (form) {
      // Skip if already initialized
      if (form.dataset.initialized === "true") {
        return;
      }

      form.dataset.initialized = "true";

      // Get config from JSON script tag
      const configScript = form
        .closest(".cae-newsletter")
        ?.querySelector(".cae-newsletter-config");
      if (!configScript) {
        return;
      }

      let config;
      try {
        config = JSON.parse(configScript.textContent);
      } catch (e) {
        if (typeof CAE_DEBUG !== "undefined") {
          CAE_DEBUG.log("error", "CAE Newsletter: Failed to parse config", e);
        }
        return;
      }

      // Get nonce from form field (wp_nonce_field generates _cae_newsletter_nonce)
      const nonceField = form.querySelector('input[name="_cae_newsletter_nonce"]');
      if (!nonceField) {
        if (typeof CAE_DEBUG !== "undefined") {
          CAE_DEBUG.log("error", "CAE Newsletter: Nonce field not found");
        }
        return;
      }
      config.nonce = nonceField.value;

      // Get form elements
      const emailInput = form.querySelector(".cae-newsletter__input");
      const consentCheckbox = form.querySelector(".cae-newsletter__checkbox");
      const submitButton = form.querySelector(".cae-newsletter__button");
      const messageDiv = form.querySelector(".cae-newsletter__message");

      if (!emailInput || !consentCheckbox || !submitButton || !messageDiv) {
        return;
      }

      // Store original button text
      if (!submitButton.dataset.originalText) {
        submitButton.dataset.originalText = submitButton.textContent.trim();
      }

      // Handle form submission
      form.addEventListener("submit", function (e) {
        e.preventDefault();

        // Reset previous states
        hideMessage(messageDiv);
        emailInput.setAttribute("aria-invalid", "false");

        // Get form values
        const email = emailInput.value.trim();
        const consent = consentCheckbox.checked;

        // Client-side validation
        if (!email) {
          showError(
            messageDiv,
            config.errorMessage || "Please enter your email address."
          );
          emailInput.setAttribute("aria-invalid", "true");
          emailInput.focus();
          return;
        }

        if (!isValidEmail(email)) {
          showError(
            messageDiv,
            config.errorMessage || "Please enter a valid email address."
          );
          emailInput.setAttribute("aria-invalid", "true");
          emailInput.focus();
          return;
        }

        if (!consent) {
          showError(
            messageDiv,
            config.errorMessage || "Please accept the terms to continue."
          );
          consentCheckbox.focus();
          return;
        }

        // Disable form during submission
        setFormDisabled(form, true);

        // Prepare form data
        const formData = new FormData();
        formData.append("action", "cae_newsletter_subscribe");
        formData.append("email", email);
        formData.append("consent", consent ? "1" : "0");
        formData.append("_cae_newsletter_nonce", config.nonce);
        formData.append("widget_id", config.widgetId || "");

        // Send AJAX request
        fetch(config.ajaxUrl, {
          method: "POST",
          body: formData,
          credentials: "same-origin",
        })
          .then(function (response) {
            return response.json();
          })
          .then(function (data) {
            if (data.success) {
              showSuccess(
                messageDiv,
                config.successMessage || "Thank you for subscribing!"
              );
              form.reset();
              emailInput.setAttribute("aria-invalid", "false");
            } else {
              showError(
                messageDiv,
                data.data?.message ||
                  config.errorMessage ||
                  "An error occurred. Please try again."
              );
              if (data.data?.field === "email") {
                emailInput.setAttribute("aria-invalid", "true");
                emailInput.focus();
              }
            }
          })
          .catch(function (error) {
            if (typeof CAE_DEBUG !== "undefined") {
              CAE_DEBUG.log("error", "CAE Newsletter: AJAX error", error);
            }
            showError(
              messageDiv,
              config.errorMessage || "An error occurred. Please try again."
            );
          })
          .finally(function () {
            setFormDisabled(form, false);
          });
      });

      // Real-time email validation
      emailInput.addEventListener("blur", function () {
        const email = this.value.trim();
        if (email && !isValidEmail(email)) {
          this.setAttribute("aria-invalid", "true");
        } else {
          this.setAttribute("aria-invalid", "false");
        }
      });
    });
  }

  /**
   * Validate email format
   *
   * @param {string} email Email address to validate
   * @return {boolean} True if valid
   */
  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  /**
   * Show success message
   *
   * @param {HTMLElement} messageDiv Message container element
   * @param {string} message Success message text
   */
  function showSuccess(messageDiv, message) {
    messageDiv.textContent = message;
    messageDiv.className = "cae-newsletter__message show success";
    messageDiv.setAttribute("role", "status");
    messageDiv.setAttribute("aria-live", "polite");
    messageDiv.setAttribute("aria-atomic", "true");
  }

  /**
   * Show error message
   *
   * @param {HTMLElement} messageDiv Message container element
   * @param {string} message Error message text
   */
  function showError(messageDiv, message) {
    messageDiv.textContent = message;
    messageDiv.className = "cae-newsletter__message show error";
    messageDiv.setAttribute("role", "alert");
    messageDiv.setAttribute("aria-live", "assertive");
    messageDiv.setAttribute("aria-atomic", "true");
  }

  /**
   * Hide message
   *
   * @param {HTMLElement} messageDiv Message container element
   */
  function hideMessage(messageDiv) {
    messageDiv.className = "cae-newsletter__message";
    messageDiv.removeAttribute("role");
    messageDiv.removeAttribute("aria-live");
    messageDiv.removeAttribute("aria-atomic");
  }

  /**
   * Enable or disable form elements
   *
   * @param {HTMLElement} form Form element
   * @param {boolean} disabled Whether to disable
   */
  function setFormDisabled(form, disabled) {
    const inputs = form.querySelectorAll("input, button");
    inputs.forEach(function (input) {
      input.disabled = disabled;
    });

    const button = form.querySelector(".cae-newsletter__button");
    if (button && disabled) {
      const loadingText = button.dataset.loadingText || "Subscribing...";
      if (!button.dataset.currentText) {
        button.dataset.currentText = button.textContent.trim();
      }
      button.textContent = loadingText;
    } else if (button && !disabled) {
      // Restore original button text
      if (button.dataset.currentText) {
        button.textContent = button.dataset.currentText;
        delete button.dataset.currentText;
      } else if (button.dataset.originalText) {
        button.textContent = button.dataset.originalText;
      }
    }
  }

  // Initialize on DOM ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initNewsletterForms);
  } else {
    initNewsletterForms();
  }

  // Re-initialize for dynamically loaded content (Elementor)
  if (typeof elementorFrontend !== "undefined") {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/cae-newsletter.default",
      function ($scope) {
        initNewsletterForms();
      }
    );
  }
})();
