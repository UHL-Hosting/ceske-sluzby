## 2025-05-14 - [Dynamic tracking link preview with security considerations]
**Learning:** When implementing live previews that incorporate user input into the DOM, using `.html()` with string concatenation can lead to DOM-based XSS. Additionally, user input used in URLs must be properly encoded.
**Action:** Use jQuery's `.text()` for labels and `.attr()` for attributes when building elements from user input. Use `encodeURIComponent()` when substituting user input into URL templates.

## 2025-05-15 - [Reusable patterns for Admin UX consistency]
**Learning:** For a more polished and accessible admin experience in this plugin, small interaction patterns like input trimming, auto-focusing dependent fields, and consistent button spacing make a significant difference. Localizing external link hints for ARIA labels ensures non-visual users have the same context as visual users.
**Action:** Implement whitespace trimming on `.blur()`, auto-`.focus()` dependent fields when prerequisites are met, and always add a localized hint like `(otevře se v novém okně)` to links opening in new tabs. Use `margin-left: 5px` for secondary action buttons next to inputs.

## 2025-05-16 - [Enhanced Link Accessibility and Focus Management]
**Learning:** Generic "here" (zde) links are an accessibility anti-pattern. Centralizing external link hints via a static method (e.g., `admin_external_link_tip()`) and using `sprintf` for translatable HTML allows for cleaner attribute insertion (like `aria-label`) and better localization. In the WordPress Media API, programmatically shifting focus to the "Remove" button after selection ensures keyboard users aren't left in a focus vacuum.
**Action:** Replace all generic link text with descriptive labels. Use `sprintf()` to construct links with `target="_blank"`, `rel="noopener noreferrer"`, and `aria-label`. Always manage focus transitions between related "Upload" and "Remove" actions.
