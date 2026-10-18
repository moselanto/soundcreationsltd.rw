# AGENTS.md: handoff notes for AI coding agents

Read this first before changing anything in this repository. Update it (and `CHANGELOG.md`) in the same commit as your change, so the next agent knows what was done.

## What this repo is

WordPress code for **soundcreationsltd.rw** (Sound Creations Ltd Rwanda, KN1 Rd, Muhima, Kigali).

| Folder | Role |
| --- | --- |
| `soundcreations/` | Parent theme, shared with the group site soundcreationsltd.com. Avoid Rwanda-only edits here. |
| `soundcreations-rwanda/` | **Active child theme.** All Rwanda content, SEO, schema and GEO work goes here. Version lives in `functions.php` (`SCRW_VERSION`) and `style.css`; keep both equal. |
| `sound-creations-core/` | Shared plugin: post types, settings, one JSON-LD graph (`includes/seo-graph.php`), titles, FAQ schema, security, robots.txt hygiene. |
| `sound-creations-enquiries/` | Shared enquiry/forms plugin. |
| `server/` | `.htaccess` snippets (speed, old-domain 301s) to paste on the server. |
| `docs/` | `SEO-PLAN.md` (audit + release history), `KEYWORD-STRATEGY.md` (Rwanda search demand), screenshots. |

## SEO / GEO / AEO rules (do not break)

1. **Rank Math owns `<head>`** (title, description, canonical, robots, Open Graph). Do not print a second title/description/robots tag. Anything typed in Rank Math wins.
2. **Exactly one JSON-LD graph per page**, built by `sc_seo_graph()` in core and extended only via the `sc_seo_graph` filter (see `inc/schema-rw.php`, `inc/schema-services.php`, `inc/seo-ai.php`). Rank Math schema stays off.
3. **Never invent facts**: no prices, ratings, review counts, stock, coordinates or awards that the business has not supplied. Product Offer schema only appears when an RWF price is entered.
4. Business identity must stay consistent everywhere (name, KN1 Rd Muhima address, phone): `@id #localbusiness`, never `#branch-nairobi`; no Kenya/UAE accounts in `sameAs` or `areaServed`.
5. Solution/sector page titles avoid the words "audio", "acoustic", "install", "integ", "visual" (the parent template switches layout on them).
6. Seeders run once per version on wp-admin load and never overwrite edited content. Any new seeder must follow that pattern.
7. Rewrite flushes can drop Rank Math sitemap rules; `inc/sitemap-rules.php` re-adds them on every rebuild. Do not remove it.
8. One primary search intent per page; no near-duplicate "Kigali" vs "Rwanda" doorway pages.

## Where SEO lives (child theme `inc/`)

| File | Since | Purpose |
| --- | --- | --- |
| `seo.php`, `seo-boost.php` | 1.0-1.2 | Rwanda titles, descriptions, OG image fallback, Rank Math integration |
| `schema-rw.php` | 1.2.0 | Rwanda LocalBusiness/Organization fixes in the graph |
| `seo-content.php` | 1.3.0 | Sector landing pages (conference, church, school, hotel) + FAQs |
| `seo-health.php` | 1.6.0 | Sitemap self-heal, Rwanda wording for fallback descriptions |
| `seo-market.php` | 1.7.0 | "Price in Rwanda" box, optional RWF Offer, buyer FAQ on products |
| `sitemap-rules.php` | 1.7.4 | Permanent Rank Math sitemap rewrite rules |
| `schema-services.php` | 1.7.x | Service nodes |
| `analytics.php` | 1.x | GA4 + Search Console fields |
| `seo-ai.php` | 2.0.0, corrected 2.1.0 | Optional `/llms.txt` (off; `scrw_llms_enabled`), optional training-crawler opt-out (`scrw_ai_training_policy`). Never add named-bot Allow groups: they bypass the core hygiene rules |
| `guides-seed.php` | 2.2.0 | Four buyer guides seeded once as drafts for engineer review; never overwrites existing posts |
| `wording-rw.php` | 2.1.0 | Rwanda-first visible copy where the seeded value still says Africa / Middle East; never touches edited text |

## Open items (need the business or server access, not code)

Full ranked list: `docs/SEO-AUDIT-2026-10.md` (gap report, competitor matrix, question-to-page map, AI citation log).

- **Yamaha distributor directory shows the wrong main phone** (+250 734 016 816); ask Yamaha to list +250 783 141 050.
- **Old site rwanda.soundcreationsltd.com is still live and indexed**: paste `server/htaccess-old-domain-redirect.txt`.

- Google Business Profile with identical name/address/phone; ask clients for reviews.
- Search Console: verify domain, submit `/sitemap_index.xml`, request indexing.
- GA4 baseline report.
- Paste `server/htaccess-old-domain-redirect.txt` on rwanda.soundcreationsltd.com.
- Link from soundcreationsltd.com to this site.
- Kigali lat/long in Settings (left blank rather than guessed).
- Real RWF prices on top products; real photos on sector pages; 300+ word project write-ups.
- Buyer guides (blog) answering real questions: they are the main fuel for AI answers.

## Edits made in WordPress admin

Some SEO changes live in the database (Rank Math fields), not in this repo. Log every such edit in CHANGELOG.md under "Live site edits" with the old and new values. Values typed in Rank Math override the theme's titles.

## Owner decisions

- **No prices on the website (18 Oct 2026).** Every shipment is priced differently, so no RWF prices are entered or displayed. Product pages keep the "ask for today's price" WhatsApp prompt. Consequences: no Product/Offer schema on product pages (`inc/seo-market.php` drops the Product node when no price is set) and no `priceRange` on the business node. Do not add prices, price ranges, ratings or reviews to schema.
- **Shure is handled by Sound Creations Kenya** (one group); do not ask Shure to change its listing for Rwanda.

## Brief

The owner's master brief ("SCL RWANDA SEO, AEO, GEO") governs this work: audit before editing, verified facts only, no doorway or thin country pages, no fake authority, no llms.txt or schema just because it is fashionable, label observed vs inferred, never claim AI tests that were not run.

## How to ship a change

1. Bump `SCRW_VERSION` (functions.php) and `Version:` (style.css) together.
2. Add an entry to `CHANGELOG.md` and, for SEO work, a release section in `docs/SEO-PLAN.md`.
3. Update the tables above if you add a file or close an open item.
4. Deploy: back up, upload `soundcreations-rwanda/`, open wp-admin once, clear LiteSpeed cache, then test with the Rich Results Test and schema validator.
