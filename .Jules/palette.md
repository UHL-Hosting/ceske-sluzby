## 2025-05-14 - [Dynamic tracking link preview with security considerations]
**Learning:** When implementing live previews that incorporate user input into the DOM, using `.html()` with string concatenation can lead to DOM-based XSS. Additionally, user input used in URLs must be properly encoded.
**Action:** Use jQuery's `.text()` for labels and `.attr()` for attributes when building elements from user input. Use `encodeURIComponent()` when substituting user input into URL templates.

## 2025-05-15 - [Reusable patterns for Admin UX consistency]
**Learning:** For a more polished and accessible admin experience in this plugin, small interaction patterns like input trimming, auto-focusing dependent fields, and consistent button spacing make a significant difference. Localizing external link hints for ARIA labels ensures non-visual users have the same context as visual users.
**Action:** Implement whitespace trimming on `.blur()`, auto-`.focus()` dependent fields when prerequisites are met, and always add a localized hint like `(otevře se v novém okně)` to links opening in new tabs. Use `margin-left: 5px` for secondary action buttons next to inputs.

## 2025-05-16 - [Focus management in Media API interactions]
**Learning:** When using the WordPress Media API to pick files, focus is often lost because the original trigger (the "Upload" button) is hidden or the modal closure resets focus to the body. Programmatically shifting focus to the newly revealed secondary action (the "Remove" button) maintains keyboard context.
**Action:** Always call `.focus()` on the next logical interactive element after a media library selection if the original trigger is removed or hidden.
## 2026-04-13 - [Descriptive link labels and focus management]
**Learning:** Generic link labels like "zde" (here) are a common accessibility pitfall. Replacing them with descriptive destination labels improves context for all users, especially those using screen readers. Furthermore, programmatically shifting focus to newly revealed interactive elements (like a "Remove" button after upload) maintains keyboard navigation continuity.
**Action:** Always replace generic navigation text with descriptive labels. After a user action reveals a new primary interaction point, use `.focus()` to guide the keyboard focus to that element.
## 2025-05-16 - [Descriptive Link Text and External Link Accessibility]
**Learning:** Generic link labels like "zde" (here) are a significant accessibility barrier for screen reader users and provide poor context for all users. In complex admin settings with many external documentation links, descriptive labels combined with explicit external link hints and security attributes (`rel="noopener noreferrer"`) are essential.
**Action:** Always replace generic "zde" or "here" with descriptive labels (e.g., "v administraci Heureky"). For links opening in new tabs, add `target="_blank"`, `rel="noopener noreferrer"`, and an `aria-label` that includes both the link's purpose and a localized hint like `(otevře se v novém okně)`. Use `sprintf()` and `__()` for clean, translatable link templates in PHP.
