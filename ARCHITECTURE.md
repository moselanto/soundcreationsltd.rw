# ARCHITECTURE.md

Technical architecture of soundcreationsltd.rw. Short version: `ARCHITECTURE-ESSENTIALS.md`.

## 1. Platform

| Layer | Technology |
| --- | --- |
| CMS | WordPress 6.4+ (PHP 8.0+, HTTPS required) |
| Themes | `soundcreations` (parent) + `soundcreations-rwanda` (child, active, 2.3.2) |
| Plugins (custom) | Sound Creations Core 0.5.27, Sound Creations Enquiries 0.2.0 |
| Plugins (third party) | Elementor 4.3.x (About page, widgets), Rank Math SEO (head meta, sitemap, IndexNow, Local SEO) |
| Hosting | LiteSpeed/Apache host for .rw (separate from the soundcreationsltd.com cPanel); caching drop-in present (see SECURITY-AUDIT.md O-2) |
| Analytics | GA4 G-05TFN3KPEB (printed by child `inc/analytics.php`, skipped for logged-in users); Google Search Console and Bing Webmaster Tools |
| Source control | GitHub `moselanto/soundcreationsltd.rw`, branch `main`; deploy = upload folders to `wp-content` |

## 2. Repository layout

```
soundcreations/               parent theme (templates, design system, hardening)
soundcreations-rwanda/        child theme
  functions.php               loads inc/ modules in order, version constant
  inc/                        one concern per file (see section 4)
  template-parts/, *.php      Rwanda template overrides (Yamaha, products, footer)
  assets/css|js|img/          UI CSS, login CSS, images (clients/, products/, projects/)
sound-creations-core/         plugin: post types, fields, settings, SEO, schema, security
sound-creations-enquiries/    plugin: forms, handler, anti-spam, uploads, routing
server/                       .htaccess snippets (speed, old-domain 301s)
docs/                         SEO plan, keyword strategy, audit, screenshots
AGENTS.md, CHANGELOG.md, PRD.md, SECURITY-AUDIT.md, ARCHITECTURE*.md, README.md
```

## 3. Data model

| Post type | Purpose | Key fields / taxonomies |
| --- | --- | --- |
| `sc_solution` | Solutions and sector pages | summary, included, outcome; Industries, Applications, Solution Areas; FAQ |
| `sc_service` | Consultancy, distribution, integration, after-sale | FAQ |
| `sc_product` | Enquiry-only catalogue (60) | brand, model/SKU, availability, datasheet, specs; optional RWF price (unused by policy), in-stock flag; Product Categories, Brands, Applications |
| `sc_brand` | Brands (13) | country, category, tagline, website |
| `sc_project` | Case studies (7 + drafts) | client, location, year, challenge/solution/technology/result, scope, brands, gallery; Brands, Industries, Project Types, Locations |
| `sc_resource` (Videos), `sc_case_study`, `sc_fane_resource` | Ready, currently empty | |
| `sc_enquiry` | Private store of form submissions | not public, not in REST |
| `post` | Buyer Guides (4 drafts) | Rank Math title/description |

Settings live in the `soundcreations_settings` option (Sound Creations > Settings and Customizer). Rwanda GA4/GSC IDs are separate options in Settings > General.

## 4. Child theme modules (`soundcreations-rwanda/inc/`)

| Group | Files |
| --- | --- |
| Seeders (run once per version, never overwrite) | settings-seed, content-seed, pages-seed, projects-seed, products-seed (+ products-data), seo-content, guides-seed, about-content |
| Presentation | hero, menu, offices, fane-*, clients, brand-spotlight, complete-projects, solutions, section-titles, product-images, whatsapp-chat, login, elementor, elementor-widgets, wording-rw |
| SEO | seo, seo-boost (Rank Math integration), seo-health, seo-market, sitemap-rules, seo-ai |
| Schema (all via `sc_seo_graph`) | schema-rw (Rwanda business entity), schema-services (GBP services, sameAs), seo-market (FAQ, Offer only if priced), schema-tidy (sameAs normalisation, parent @id; priority 99) |
| Analytics | analytics |

## 5. SEO and schema pipeline

1. Rank Math prints title, description, canonical, robots and Open Graph. Child `seo-boost.php` removes Core's own head output when Rank Math is active and keeps only the graph.
2. Core `sc_seo_graph()` assembles Organization, WebSite, LocalBusiness, WebPage, ImageObject, BreadcrumbList, VideoObject, Service/Product/Brand/Project/Article, ItemList and FAQPage nodes.
3. Filters on `sc_seo_graph` adjust for Rwanda in priority order (seo 20, schema-rw, schema-services, schema-rw walk 45, seo-market 50, schema-tidy 99), then one `<script type="application/ld+json">` is printed.
4. Sitemap: Rank Math `/sitemap_index.xml`, rules made permanent by `sitemap-rules.php`. IndexNow via Rank Math Instant Indexing.

## 6. Enquiry pipeline

Form (shortcode/Elementor widget) -> JS fetches fresh nonce + signed render token from `admin-ajax.php?action=sc_enq_token` -> POST `admin-post.php?action=sc_enquiry` -> checks in order: rate limit, nonce, honeypot, render token age, user agent, consent, field validation, spam score (threshold 5, hard reject 100), upload validation -> `sc_enquiry` post (hashed IP) -> email via Enquiry Routing (default stefic@ and fred@soundcreationsltd.com) -> redirect back with `sc_sent` / `sc_error`.

## 7. Security architecture

Defence in depth across three units so controls survive a theme change: Core (`security.php`, `security-plus.php`), parent theme (`hardening.php`), Enquiries (`security.php`, `antispam.php`). Details and evidence: `SECURITY-AUDIT.md`.

## 8. Deployment

1. Back up files and database.
2. Upload changed folders from `main` (whole folders, not single files).
3. Open wp-admin once (seeders, rewrite flush).
4. Purge any page cache (see SECURITY-AUDIT.md O-2).
5. Verify: theme version, homepage, a product, validator.schema.org, Rich Results Test.
6. Log the deployment in `CHANGELOG.md`.
