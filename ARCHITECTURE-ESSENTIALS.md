# ARCHITECTURE-ESSENTIALS.md

One-page orientation for developers and AI agents. Full detail: `ARCHITECTURE.md`.

## What it is

WordPress site for Sound Creations Ltd Rwanda (soundcreationsltd.rw), Kigali. Shares a parent theme and two plugins with the group site soundcreationsltd.com; a Rwanda child theme layers local content, SEO and schema on top.

## The four code units

| Unit | Folder | Role | Edit for Rwanda? |
| --- | --- | --- | --- |
| Parent theme | `soundcreations/` | Templates, design system, hardening, shared with the group site | Avoid; override in the child |
| Child theme (active) | `soundcreations-rwanda/` | Rwanda content, seeders, SEO, schema, Elementor widgets, UI CSS | Yes, all Rwanda work |
| Core plugin | `sound-creations-core/` | Post types, taxonomies, fields, central settings, JSON-LD graph, security | Shared; change carefully |
| Enquiries plugin | `sound-creations-enquiries/` | Forms, anti-spam, rate limiting, uploads, routing | Shared; change carefully |

## Rules that must not break

1. Rank Math owns `<head>`; exactly one JSON-LD graph per page, extended only via the `sc_seo_graph` filter.
2. No invented facts, no prices (owner decision), no ratings or reviews.
3. Seeders run once per version and never overwrite edited content.
4. Never change published URLs without a redirect.
5. Sanitise input, escape output, nonce + capability on every write (see `SECURITY-AUDIT.md`).
6. Bump `SCRW_VERSION` (functions.php) and `Version:` (style.css) together; log every change in `CHANGELOG.md`.

## Request flow

Visitor -> WordPress -> parent template -> child overrides and hooks -> Core builds the schema graph -> `sc_seo_graph` filters (Rwanda layers) -> Rank Math prints meta. Enquiry form -> `admin-post.php?action=sc_enquiry` -> nonce/token/rate-limit/spam checks -> stored as private `sc_enquiry` -> emailed per Enquiry Routing.

## Where to look

| Need | File |
| --- | --- |
| Business details, homepage text | Sound Creations > Settings (`sound-creations-core/includes/settings.php`) |
| Schema graph | `sound-creations-core/includes/seo-graph.php` + child `inc/schema-*.php` |
| Titles/descriptions fallback | `sound-creations-core/includes/seo-titles.php`, child `inc/seo*.php` |
| Product price box / FAQ | child `inc/seo-market.php` |
| Buyer guides | child `inc/guides-seed.php` |
| Security | core `includes/security*.php`, parent `inc/hardening.php`, enquiries `includes/security.php`, `antispam.php` |
