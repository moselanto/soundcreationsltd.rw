# Changelog

Newest first. Every change must add an entry here (see AGENTS.md).

## 2.3.0 - 18 October 2026: schema cross-check and entity tidy-up
- Live cross-check: validator.schema.org on 7 pages (home, /yamaha/, conference room, /products/shure-sm57/, Christian Life Assembly project, /about/, /contact/): 0 errors, 0 warnings, exactly one JSON-LD graph per page. Google Rich Results Test (home, church page): Breadcrumbs, Local business and Organisation all valid; only optional warnings (priceRange, postalCode), intentionally not filled.
- New `inc/schema-tidy.php` (last on `sc_seo_graph`): normalises and de-duplicates sameAs (web.facebook.com vs www.facebook.com, trailing slashes), gives the Organization and LocalBusiness the same complete sameAs list, and gives the parent company node the @id https://soundcreationsltd.com/#organization so the branch links to the group site's own graph.
- AGENTS.md: owner decisions recorded (no prices on the site; Shure handled by Sound Creations Kenya).
- Needs the theme upload to go live.

## Old domain: search engine move - 18 October 2026
- Google Search Console: added rwanda.soundcreationsltd.com as a Domain property, verified by one DNS TXT record on rwanda.soundcreationsltd.com in the soundcreationsltd.com zone (cPanel Zone Editor): `google-site-verification=9NZZM86oV77wu9_AWVt6lGWIeUmfRm1vEKuWI8_KzyA`. KEEP this record; deleting it removes verification.
- Change of address submitted to https://soundcreationsltd.rw/ (301 check and verification passed). Status "This site is currently moving", started 18 Oct 2026. Leave the 301s in place permanently.
- Bing: https://rwanda.soundcreationsltd.com/ imported into Bing Webmaster Tools. Bing's current interface has no Site Move tool, so none was submitted; Bing follows the 301s on recrawl.

## Old domain redirect check - 18 October 2026
- cPanel (d9742.usc1.stableserver.net): rwanda.soundcreationsltd.com document root is /public_html/rwanda.soundcreationsltd.com. Its .htaccess ALREADY starts with the full rule set from `server/htaccess-old-domain-redirect.txt` (page-by-page 301s, catch-all to https://soundcreationsltd.rw/). Nothing was changed; a backup copy was downloaded.
- Checked in a browser: https://rwanda.soundcreationsltd.com/ lands on https://soundcreationsltd.rw/ and /about-us/ lands on /about/. The old URLs still seen in search results are stale index entries that drop out as search engines recrawl.
- Open item "301 the old domain" is therefore DONE. Optional next step: verify rwanda.soundcreationsltd.com in Search Console and Bing and use their site-move / Change of Address tools.

## Deployment check - 18 October 2026 (after owner uploaded 2.2.2)
- WordPress shows the Rwanda child theme at 2.2.2.
- The four Buyer Guides drafts were created by `inc/guides-seed.php` and await engineer review (not published).
- LiteSpeed Cache is NOT installed on this WordPress site (5 plugins installed), so there is no cache purge step. Older deploy notes that say "clear LiteSpeed cache" do not apply unless a caching plugin or host cache is added later.
- Search Console live test of /products/shure-sm57/: no Product snippets item detected, Breadcrumbs valid. "Validate fix" started 18/10/2026 for "Either 'offers', 'review' or 'aggregateRating' should be specified" (28 items). Check the validation result in about 2 to 4 weeks.

## 2.2.2 - 18 October 2026: fix invalid Product snippets
- Search Console > Product snippets: 28 invalid, 0 valid, critical issue "Either 'offers', 'review' or 'aggregateRating' should be specified" (first detected 14 Oct; e.g. /products/shure-sm57/, /products/yamaha-psr-sx600/, /products/db-b-hype-series/).
- Cause: core `seo-graph.php` outputs a Product node on every product page, but the catalogue is enquiry-only with no price, rating or review, so Google marks each one invalid.
- Fix in `soundcreations-rwanda/inc/seo-market.php` (Rwanda layer, core untouched): the Product node is removed when no RWF price is entered. When a real price is entered in the product's "Price in Rwanda (RWF)" field, Product + Offer (RWF) is output and becomes eligible for product rich results. No invented prices, ratings or reviews.
- After deploying: Search Console > Product snippets > the issue > "Validate fix". The invalid count should fall as Google recrawls.

## 2.2.1 - 18 October 2026: client logo alt text
- `inc/clients.php`: the duplicated (aria-hidden) copy of the "Our Clients" logo marquee now carries the client name as alt text instead of `alt=""`. Bing Webmaster Tools flagged 22 empty alts on the homepage; screen readers still skip the duplicate because its list items are aria-hidden.

## Search engine tools (not code) - 18 October 2026
- Google Search Console (soundcreationsrwanda@gmail.com): URL-prefix property https://soundcreationsltd.rw/ verified; `/sitemap_index.xml` submitted 14 Oct, status Success (117 URLs). All 10 key pages inspected: indexed. Linked to GA4 property soundcreationsltd.rw (G-05TFN3KPEB), data flowing.
- Bing Webmaster Tools: settings imported from Google by the owner. Sitemap imported 18 Oct (processing). Indexing requested for 6 pages that were "Discovered but not crawled": conference room, church, professional audio, installation, school and auditorium, hotel/bar/restaurant. Already indexed: home, /yamaha/, architectural acoustics, /projects/, /about/. Bing AI Performance: 0 citations at baseline.
- Rank Math Instant Indexing (IndexNow) module confirmed enabled in WordPress.
- Google Business Profile: still owned by the original company email; do not edit until access is transferred.

## 2.2.0 - 18 October 2026: buyer guides (AEO / GEO content)
- New `soundcreations-rwanda/inc/guides-seed.php`, loaded from `functions.php`. On the next wp-admin load it creates four answer-first guides as **drafts** in a new "Buyer Guides" category, with Rank Math title and description, visible FAQs and links to the matching solution and project pages:
  - `sound-system-quotation-checklist-rwanda` (procurement checklist)
  - `acoustic-treatment-vs-soundproofing`
  - `choosing-pa-system-church-hall-rwanda`
  - `maintaining-commercial-sound-system`
- Never overwrites: a guide is skipped if any post with its slug exists (any status). No invented prices, measurements or results. An admin notice lists drafts awaiting engineer review (meta `_scrw_guide_needs_review`).
- Article schema comes from the existing core graph for posts; no new schema generator.

## Live site edits (WordPress admin, not code) - 18 October 2026
- Confirmed the live child theme is 2.1.0 (deployed).
- Conference page `/solutions/conference-room-sound-systems/` (post 464): Rank Math SEO title set to "Conference Room AV & Video Conferencing Rwanda | Sound Creations" and meta description to "Conference room AV and video conferencing in Kigali: boardroom microphones, Zoom/Teams audio, cameras and acoustics. Proven at MINECOFIN. Get a quote." Previous: title template "%title% | Sound Creations Rwanda" (live title came from theme code: "Conference Room Sound Systems Kigali | Boardroom Audio Rwanda"), description empty (excerpt fallback). URL, page title and body unchanged. Reason: the page did not appear for "conference room AV Rwanda" (see docs/SEO-AUDIT-2026-10.md section 9).
- Pending: Google sign-in blocked at phone verification; Google Business Profile still owned by the original company email (do not edit until access is transferred).

## 2.1.0 - 18 October 2026: audit against the SEO/AEO/GEO brief
- New `docs/SEO-AUDIT-2026-10.md`: ranked gap report, competitor matrix, question-to-page map, AI citation log template, content plan, regional assessment, blocked items.
- `inc/seo-ai.php` corrected: removed named AI-bot Allow groups (they would have bypassed core crawl-hygiene rules); `/llms.txt` now off by default (`scrw_llms_enabled`); removed `speakable` and `lastReviewed` (no benefit for this site); training-crawler block available via `scrw_ai_training_policy`.
- New `inc/wording-rw.php`: homepage "What we do" lead now Rwanda-first (Kigali, Rwanda, DRC, East Africa) instead of "Africa and the Middle East", only while the seeded text is unedited. Seed default updated in `inc/settings-seed.php`.
- AGENTS.md: brief rules, new open items (Yamaha listing phone, old domain still indexed).

## 2.0.0 - 18 October 2026: GEO and AEO layer
- New `soundcreations-rwanda/inc/seo-ai.php`, loaded from `functions.php`:
  - `/llms.txt`: live Markdown summary of the business (facts, contact, areas served, Yamaha = music/pro audio not motorcycles) with links to solutions, services, projects, brands and posts. Cached 12 h, cleared on publish. Served with `X-Robots-Tag: noindex`.
  - robots.txt: explicit Allow for AI answer/search crawlers (OAI-SearchBot, ChatGPT-User, PerplexityBot, Claude-SearchBot, Applebot, Bingbot, etc.) and, by default, training crawlers (filter `scrw_ai_allow_training` to turn off). Points to `/llms.txt`.
  - Schema: `speakable` (H1 + visible FAQ) and `lastReviewed` on the WebPage node, via the existing `sc_seo_graph` filter (still one graph).
- Version sync: `style.css` was still 1.8.0 while `SCRW_VERSION` was 1.9.6; both now 2.0.0.
- Added `AGENTS.md` (handoff for AI agents) and this changelog.

## 1.9.3 - 1.9.6
- Mobile tab bar and menu fixes; branded sign-in page (`inc/login.php`, `assets/css/scrw-login.css`).

## 1.7.x
- 1.7.0 "price in Rwanda" product SEO (`inc/seo-market.php`); 1.7.4 permanent sitemap rewrite rules (`inc/sitemap-rules.php`). See docs/SEO-PLAN.md.

## 1.6.0
- Sitemap self-heal, sector pages actually loaded, Rwanda wording, old-domain 301 file.

## 1.2.0 - 1.3.0
- Schema audit fixes (`#localbusiness`, areaServed, sameAs) and four sector landing pages.
