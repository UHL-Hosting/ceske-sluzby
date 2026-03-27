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

## Phase 4: Modernization and AI Integration

Status: Completed (v1.1.0)

- Declared full compatibility with WooCommerce Cart and Checkout Blocks.
- Migrated all pickup-point integrations to the modern Blocks architecture using provider-specific Additional Checkout Fields and a centralized JavaScript bridge.
- Implemented Zásilkovna (Packeta) widget v6 support within the block-based checkout flow.
- Removed legacy EET (Electronic Sales Records) functionality as it was abolished on January 1, 2023.
- Integrated with WordPress 7.0 "Abilities API" to expose plugin functionality (like shipment tracking) to AI agents.
- Optimized Google Merchant Center XML feed with modern attributes and structured data.
- Refactored core logic for PHP 8.x and High-Performance Order Storage (HPOS) standards.
