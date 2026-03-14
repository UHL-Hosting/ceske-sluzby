# Compatibility Bump Plan

## Goal

Bring the plugin back to a stable baseline on current WordPress and WooCommerce without breaking the existing classic checkout flow.

## Phase 1: Current baseline

Status: Applied in `0.8.0`

- Keep the plugin on current WordPress plugin headers and WooCommerce feature declarations.
- Replace deprecated WooCommerce term meta helpers with modern Woocommerce term meta APIs.
- Make order admin integrations work on both legacy `shop_order` screens and HPOS order screens.
- Keep Cart and Checkout Blocks explicitly unsupported until the checkout UI is migrated to the Blocks integration model.

## Phase 2: Provider and runtime audit

Status: In progress

- Review every external provider integration one by one and verify current endpoints, auth expectations, and embeds.
- Replace outdated tracking or widget URLs where providers have changed their official integration method.
- Add defensive handling for unavailable provider APIs so checkout and order management still degrade safely.
- Added a compatibility admin surface with current official source links for Packeta, DPD, and Uloženka.
- Legacy pickup-point lookups now fail safely instead of breaking checkout review when a remote provider endpoint is unavailable.

## Phase 3: Local and Playground testing

Status: Next

- Add a reproducible local test harness for the plugin on a modern WooCommerce stack.
- Prepare a WordPress Playground blueprint for automated smoke testing and handoff to the custom Playground deployment at `https://playground.uhlhosting.ch`.
- use playwright to automate smoke testing of the plugin on the Playground deployment, including activation, admin settings, classic checkout pickup-point flows, HPOS order editing, and XML feed generation.
- Use that blueprint to verify activation, admin settings, classic checkout pickup-point flows, HPOS order editing, and XML feed generation.

## Phase 4: Checkout UI modernization

Status: In progress

- Decide whether to keep classic-checkout-only support or build Cart and Checkout Blocks support.
- If Blocks support is required, move pickup-point UI integrations to the WooCommerce Blocks extension approach with frontend JavaScript instead of PHP hooks plus footer scripts.
- Keep the current jQuery admin UI only where WooCommerce admin still expects classic metabox-style extensions.
- Added provider-specific Additional Checkout Fields registrations for pickup-point methods so Checkout Blocks can collect branch data without relying on legacy classic-checkout hooks.
- Added a Packeta bridge for the Zásilkovna Checkout Block field. Full `cart_checkout_blocks` compatibility is still intentionally undeclared until the remaining classic-only checkout extensions are migrated.
