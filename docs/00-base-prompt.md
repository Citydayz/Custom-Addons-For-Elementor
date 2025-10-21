# 00-base-prompt.mdc
> **Cursor Initialization Prompt — Custom Addons for Elementor by Hugo Scheer**

---

## 🎯 Objective
Bootstrap a clean, modular, and secure **Elementor Addons Plugin** for WordPress named  
**“Custom Addons for Elementor by Hugo Scheer”**, compliant with all `.cursor/Rules/*.mdc` and orchestrated via `agent.md`.

The base implementation must include:
- ✅ Plugin loader, activation hooks, constants, and autoloading.
- ✅ Folder structure (`/lib`, `/assets/css`, `/assets/js`, `/languages`).
- ✅ A secure registry for widgets (conditional enqueue).
- ✅ Three Elementor widgets:
  1. **Hero Widget** — Fully customizable (background, overlay, text, CTA).
  2. **Footer Widget** — Dynamic (menus, logo toggle, legal text with `{year}` token).
  3. **Repeater Section Widget** — Rows alternating layout (image left/right, text).

---

## 🧩 Context
This plugin targets **WordPress 6+**, **PHP 8.1+**, **Elementor**, and follows:
- Security: `04-security.mdc`
- Accessibility: `05-accessibility.mdc`
- Performance: `06-performance.mdc`
- GDPR: `07-gdpr-consent.mdc`
- SEO: `13-seo.mdc`
- i18n: `14-localization.mdc`
- Standards & delivery: `03-wp-standards.mdc` / `10-release-versioning.mdc`

---

## 🧱 Tech & Versions
- WordPress 6.4+
- PHP 8.1+
- Elementor (latest)
- Tailwind v3.4 (optional, utility classes only)
- ESLint + Prettier / PHPCS (WP standard)
- Translation domain: `cae`

---

## 🧩 Required Folder Structure
/custom-addons-for-elementor-by-hugo-scheer.php
/includes/
loader.php
debug.php
/lib/
cae-hero/class-cae-hero.php
cae-footer/class-cae-footer.php
cae-repeater-section/class-cae-repeater-section.php
/assets/
/css/global.css
/js/global.js
/images/
/css/{widget}.css
/js/{widget}.js
/languages/
cae.pot
CHANGELOG.md
---

## 🧰 Implementation Requirements
- Follow Elementor `Widget_Base` class conventions.
- Register widgets under a dedicated category `Custom Addons by Hugo Scheer`.
- Use `sanitize_*`, `esc_*`, nonces, `$wpdb->prepare` as applicable.
- Add i18n wrappers with `__()`, `esc_html__()`, etc. (`textdomain: cae`).
- Conditionally enqueue assets **only when widget is used**.
- Respect responsive + a11y defaults (alt text, keyboard focus, ARIA).
- Apply GDPR gate for non-essential JS.
- Ensure Hero widget supports:
  - Background (image/video/color)
  - Overlay color/opacity
  - Title, subtitle, CTA text & URL
  - Alignment and spacing controls
- Ensure Repeater widget:
  - Alternates layout (image left / right)
  - Repeater control in Elementor editor
  - Proper semantic structure `<section>` + `<article>`
- Ensure Footer widget:
  - 1–4 columns, logo toggle, WP menu select, `{year}` token
  - Legal text field with i18n
  - `<footer>` + `<nav>` semantics

---

## 🧩 Deliverables
- [ ] Full plugin structure (ready to zip)
- [ ] Working registration & conditional enqueue
- [ ] 3 fully functional widgets
- [ ] Assets scoped per widget
- [ ] i18n ready (`cae.pot`)
- [ ] Readme + Changelog entry
- [ ] Pass all sanity checks (security, perf, a11y, gdpr)

---

## ⚙️ Cursor Execution Steps
1. Create plugin root files and directories.
2. Implement loader (hooks, constants, widget registry).
3. Generate Hero, Footer, and Repeater widgets.
4. Add enqueue registry and conditional loading.
5. Generate scoped CSS/JS placeholders.
6. Add i18n loader and sample translation string.
7. Write CHANGELOG.md (v1.0.0 initial release).
8. Validate against all `.cursor/Rules/*.mdc`.
9. Return summary + paths of all created files.

---

## ✅ Sanity Checks
- [ ] WP_DEBUG no warnings
- [ ] Lint OK / PHPCS clean
- [ ] A11y focus, alt, headings
- [ ] Security checks pass (sanitize/escape/nonce)
- [ ] Perf budgets respected
- [ ] GDPR gate integrated
- [ ] i18n domain loaded
- [ ] Conditional enqueue verified

---

## 🧭 Recommendation
Use this prompt as a **Markdown Plan Document** in Cursor.

**🪄 File name:** `.cursor/Rules/00-base-prompt.mdc`  
**To run in Cursor:**  
> _“Follow 00-base-prompt.mdc to scaffold the plugin base and implement the 3 widgets.”_

---

**End of 00-base-prompt.mdc — Cursor Initialization Plan.**