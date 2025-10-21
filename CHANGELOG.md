# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.1.0] - 2025-01-20

### Added

- **Hero Widget** - Fully customizable hero section with background (image/color), overlay, title, subtitle, and CTA button
- **Footer Widget** - Dynamic footer with 1-4 columns, logo toggle, WordPress menu selection, and legal text with `{year}` token replacement
- **Repeater Section Widget** - Alternating layout with image left/right per item, repeater control for unlimited items
- **GDPR Consent Gate** - CookieYes integration with localStorage fallback for consent management
- **Conditional Asset Loading** - CSS/JS only enqueued on pages where widgets are present (performance optimization)
- **i18n Ready** - Full translation support with textdomain `cae` and `.pot` file
- **WCAG 2.1 AA Compliance** - Semantic HTML, keyboard navigation, focus management, contrast ratios
- **Security Baseline** - Input sanitization, output escaping, nonce verification where needed
- **Performance Optimized** - Defer scripts, lazy-load images, scoped CSS, size budgets respected
- **Accessibility Features** - Screen reader support, reduced motion respect, touch targets ≥44px
- **SEO Basics** - Semantic markup, proper heading hierarchy, descriptive alt text

### Technical Details

- **WordPress 6.0+** compatibility
- **PHP 8.1+** requirement
- **Elementor 3.0+** integration
- **Mobile-first responsive** design
- **BEM CSS methodology** for scoped styles
- **Vanilla JavaScript** (no jQuery dependency)
- **Semantic HTML5** markup
- **ARIA attributes** for accessibility
- **Core Web Vitals** optimized

### Security

- All inputs sanitized with WordPress functions
- All outputs escaped contextually
- No SQL injection vectors
- No XSS vulnerabilities
- Secure file handling

### Performance

- CSS budgets: ≤8KB per widget
- JS budgets: ≤12KB per widget
- Conditional enqueue strategy
- Lazy-loading for below-fold images
- Defer non-critical scripts
- Minimal global footprint

### Accessibility

- WCAG 2.1 AA compliant
- Keyboard navigation support
- Screen reader friendly
- Focus management
- Color contrast ≥4.5:1
- Touch targets ≥44px
- Reduced motion support

### GDPR Compliance

- Consent-gated scripts
- CookieYes integration
- localStorage fallback
- Granular consent categories
- No tracking by default
- Revocable consent

### Internationalization

- Textdomain: `cae`
- Translation-ready strings
- `.pot` file included
- RTL support via logical properties
- Locale-aware date formatting
