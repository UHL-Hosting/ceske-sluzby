## 2025-05-14 - [Dynamic tracking link preview with security considerations]
**Learning:** When implementing live previews that incorporate user input into the DOM, using `.html()` with string concatenation can lead to DOM-based XSS. Additionally, user input used in URLs must be properly encoded.
**Action:** Use jQuery's `.text()` for labels and `.attr()` for attributes when building elements from user input. Use `encodeURIComponent()` when substituting user input into URL templates.

## 2025-05-15 - [Reusable patterns for Admin UX consistency]
**Learning:** For a more polished and accessible admin experience in this plugin, small interaction patterns like input trimming, auto-focusing dependent fields, and consistent button spacing make a significant difference. Localizing external link hints for ARIA labels ensures non-visual users have the same context as visual users.
**Action:** Implement whitespace trimming on `.blur()`, auto-`.focus()` dependent fields when prerequisites are met, and always add a localized hint like `(otevře se v novém okně)` to links opening in new tabs. Use `margin-left: 5px` for secondary action buttons next to inputs.

## 2025-05-16 - [Descriptive navigation and focus persistence]
**Learning:** Generic link labels like "zde" (here) fail to provide context for screen readers and users scanning the page. Additionally, maintaining focus after asynchronous UI changes (like file uploads) is critical for keyboard accessibility.
**Action:** Replace non-descriptive links with labels that identify the destination. When an action reveals a new UI element (e.g., a "Remove" button after upload), programmatically shift focus to it immediately.
