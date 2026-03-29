## 2025-05-14 - [Dynamic tracking link preview with security considerations]
**Learning:** When implementing live previews that incorporate user input into the DOM, using `.html()` with string concatenation can lead to DOM-based XSS. Additionally, user input used in URLs must be properly encoded.
**Action:** Use jQuery's `.text()` for labels and `.attr()` for attributes when building elements from user input. Use `encodeURIComponent()` when substituting user input into URL templates.

## 2025-05-15 - [Real-time UI responsiveness with unified state management]
**Learning:** For interactive admin forms where multiple UI elements (buttons, previews, action menus) depend on the same input fields, fragmented event listeners can lead to inconsistent UI states. Using only 'change' or 'keyup' often misses interactions like pasting or browser autocomplete.
**Action:** Combine 'input' and 'change' events in a single unified listener that calls a central 'update' function. This ensures that all dependent UI elements (like 'Clear' buttons and 'Send Email' actions) stay synchronized in real-time.
