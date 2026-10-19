# Changelog

Newest first. Every change must add an entry here (see AGENTS.md).

## 2.3.19 - 19 October 2026: keep first-place keywords, full keyword bank
- Homepage title "Sound Creations Rwanda | Audio Visual, Acoustics & Yamaha Kigali": keeps the brand and Yamaha (both ranking first) while adding audio visual and acoustics. Description names AV company, Yamaha distributor, installation, acoustics, stage lighting and PA.
- `scrw_seo_keywords()` now holds 100 phrases: the first-place keywords plus the full October 2026 bank.
- docs/KEYWORD-STRATEGY.md section 6: protected keywords, priority targets, full bank.
- Upload: child `functions.php`, `style.css`, `inc/seo-boost.php`.

## 2.3.18 - 19 October 2026: priority keyword targeting
- Google titles and descriptions retargeted to the agreed priority keywords: home "Audio Visual & Acoustic Solutions Rwanda", About "Audio Visual Company in Kigali, Rwanda", Installation "Professional Sound System Installation Rwanda", Architectural Acoustics "Acoustic Solutions Rwanda", Church "Church Sound System Installation Rwanda", Conference "Conference Room AV Systems Kigali", Professional Audio "Professional Sound Systems Rwanda", Solutions archive "Audio Visual Solutions Rwanda". A title typed in the Rank Math box still wins.
- New `inc/seo-keywords.php`: homepage "Audio visual company in Rwanda" section with 7 visible FAQs (incl. "Which audio visual and event technology companies operate in Rwanda?") and matching FAQPage schema. Editable in Settings > Homepage FAQ.
- Schema keywords extended with the priority keyword bank.
- Upload: child `functions.php`, `style.css`, `inc/seo-boost.php`, `inc/seo-keywords.php`, `assets/css/scrw-ui.css`, `assets/css/scrw-ui.min.css`.

## 2.3.17 / parent 0.10.22 - 19 October 2026: Kenya X (Twitter) removed, social links fully editable
- The X icon in the top bar and footer linked to the Kenya account (x.com/SCL_kenya). It came from the group defaults, which the site fell back to whenever a Settings field was blank, so it could not be removed from wp-admin.
- Parent: `sc_default_settings()` is now filterable (`sc_default_settings`). `sc_setting()` treats a saved value of "none" or "-" as "hide this item".
- Child: `scrw_default_settings()` makes every Rwanda value the fallback. X and YouTube are blank for Rwanda; Facebook is the Rwanda page. Seed rw-settings-14 clears the stored Kenya X and Facebook values (only if never edited by hand).
- Upload: `soundcreations/inc/template-tags.php`, `soundcreations/style.css`; child `functions.php`, `style.css`, `inc/settings-seed.php`. Then open any wp-admin page once.

## 2.3.16 / parent 0.10.21 - 19 October 2026: hide hero caption text, About refinements
- Homepage: the captions line "[Background video: ...]" was showing on the hero because the track was marked default. The track stays (PageSpeed) but is no longer shown.
- About (below Our Clients): intro in a panel; "What our Kigali team does" as a 2 x 2 grid of numbered cards (01-04); tick lists in two columns (no single orphan box); single column on phones.
- Upload: `soundcreations/template-parts/designs/home.php`, `soundcreations/style.css`; child `functions.php`, `style.css`, `assets/css/scrw-ui.css`, `assets/css/scrw-ui.min.css`.

## 2.3.15 / parent 0.10.20 - 19 October 2026: About page layout and the last PageSpeed notes
- About page text below "Our Clients" (`inc/about-content.php`): the Elementor/editor content is restructured, words unchanged: intro lead, one block per H2 with a red accent, H3 sub-sections as a card grid ("What our Kigali team does"), lists as check cards, last block with Contact/Quote links as a call-to-action panel, tall photo cropped to a banner (max 520 px), Elementor padding gaps removed. Uses DOMDocument; falls back to the original HTML on any problem; off switch filter `scrw_about_enhance`.
- PageSpeed unscored notes: hero video gets a captions track (`assets/video/hero-captions.vtt`, decorative, no speech) and is hidden from screen readers; Brands links read "Visit Yamaha website" etc.; external links in the About text get an accessible name "... official website (external)".
- Upload: whole `soundcreations-rwanda/`; from `soundcreations/`: `archive-sc_brand.php`, `template-parts/designs/home.php`, `assets/video/hero-captions.vtt`, `style.css`.

## 2.3.14 / parent 0.10.19 - 19 October 2026: About and Brands speed
Baseline (mobile): About 87 (LCP 4.0 s, text LCP waiting 1.0 s on render-blocking CSS and jQuery in head); Brands 94 (LCP 3.0 s, 202 KB JPG hero background found late).
- jQuery and jQuery Migrate load in the footer for visitors (only Elementor's footer scripts use them; no theme or plugin front-end code needs jQuery in head). Admin and signed-in users unchanged.
- Brands hero: new `brands-hero.webp` (smaller, same image) used instead of the JPG, and preloaded with fetchpriority high on /brands/.
- Lighter images: FANE hero (Brands page), white footer logo, 4 client logos.
- Upload: whole `soundcreations-rwanda/` (functions.php, style.css, assets/img/clients/); from `soundcreations/`: `archive-sc_brand.php`, `style.css`, `assets/img/brands/brands-hero.webp`, `assets/img/fane/fane-hero-sov15.webp`, `assets/img/logo-white.webp`.

## 2.3.13 / parent 0.10.18 - 19 October 2026: last two re-test findings
- Contact and consultation office list: role="button", tabindex and aria-label actually removed from the list item (the 2.3.11 change did not match the markup). Clears the only scored Accessibility failure (Contact 97).
- Sector solution pages (hotel, school, installation): project cards no longer print the name twice (second card renderer in `inc/seo-content.php`).
- Upload: `soundcreations-rwanda/` (functions.php, style.css, inc/seo-content.php); from `soundcreations/`: `template-parts/contact-page.php`, `template-parts/consult-page.php`, `style.css`.

## 2.3.12 / parent 0.10.17 / Enquiries 0.2.1 - 19 October 2026: full-site crawl fixes
Crawl of 113 URLs (all 60 products, 12 solutions, 7 projects, 13 brands, 4 services, guides and pages).
- Broken links: /solutions/ "Acoustics" card and menu fallback now go to /solutions/architectural-acoustics/; 301s for /solutions/acoustics/, /solutions/conferencing/, /solutions/system-integration/. New "Become a Dealer" page (pages seed rw-pages-4; the Enquiries plugin adds the dealer form), so "Become a FANE Dealer" no longer 404s.
- One-time content corrections (`inc/content-fixes.php`, runs once on the first wp-admin load by an administrator, exact-text only): Kenya phone and Nairobi address on Warranty and After-Sale Services replaced with the Kigali details; warranty grammar (manufacturer's); "over 13 years" and "since 1989" replaced with the 22+ years claim; "exam announcements"; UK spelling in product specs (analogue, colour, programme, zipped); "22 frets"; HS7 white wording; BLX14/P98H description; CBR10 depth 289 mm; AVer model field VC520 Pro2. Result logged in option `scrw_content_fixes_log`.
- /yamaha/: "Loudspeakers: DBR powered and CBR passive series" (CBR10 is passive).
- Solution pages: project cards no longer print the project name twice.
- Enquiry forms: "Company / Organisation".
- Needs the business (not changed): Ntare Louisenlund vs Atelier du Vin identical equipment; MINECOFIN wired MXC vs wireless MXCW; "Mulindi Hall" label; PGA48 SKU vs model; MG20XU dimensions; after-sales "one year free" vs manufacturer-warranty wording.
- Upload: whole `soundcreations-rwanda/`; from `soundcreations/`: `header.php`, `archive-sc_solution.php`, `template-parts/designs/home.php`, `inc/template-tags.php`, `style.css`; whole `sound-creations-enquiries/`. Then open wp-admin once as administrator.

## 2.3.11 / parent 0.10.16 - 19 October 2026: accessibility pass on every page type
Lighthouse run on 12 page types (home, products, product, projects, project, solution, guide, contact, quote, about, Yamaha, brands): Performance 83-100, Accessibility 94-100, Best Practices 96-100, SEO 100; schema 0 errors on product, project, guide and contact. Fixed:
- Contrast (WCAG 1.4.3): red text on the dark theme raised to #ff6b6b (6.7:1) for project card links, case-study block labels, brand card categories, card kickers, "Price in Rwanda" label, links in page text, accordions and the enquiry form (Privacy Policy, Terms). Light theme keeps #BA0B0B.
- Links in text are underlined, not colour only (WCAG 1.4.1; About page).
- Heading order: hidden "Product list" heading on /products/, hidden "Why buy Yamaha..." heading on /yamaha/.
- Contact and consultation office list: removed role="button" from list items (invalid list semantics).
- Carousel dots: 24 px touch targets (WCAG 2.5.8).
- Architectural Acoustics: YouTube video is now click-to-play (thumbnail first); the player and its third-party cookies load only on play. Fixes the Chrome cookie issue and the 4.6 s mobile LCP.
- Upload: whole `soundcreations-rwanda/` folder; from `soundcreations/`: `single-sc_solution.php`, `template-parts/contact-page.php`, `template-parts/consult-page.php`, `style.css`.

## 2.3.10 - 19 October 2026: Featured Projects carousel navigation
- Homepage Featured Projects: the desktop arrow buttons had no script and did nothing; now they move one project at a time and grey out at the ends.
- Phones (up to 700 px): a control bar under the cards with large previous / next buttons (48 px touch targets), dots for each project, a "1 / 6" counter and a "Swipe to see more" hint that fades after the first swipe. Cards snap into place when swiped.
- Keyboard: the strip is focusable and the left/right arrow keys move it. Respects reduced motion.
- Built with DOM methods only (`assets/js/scrw-ui.js`), compatible with strict script security. CSS in `scrw-ui.css` / `.min.css`.
- Upload: whole `soundcreations-rwanda/` folder (also contains 2.3.9 hero video).

## 2.3.9 - 19 October 2026: homepage hero video restored
- The hero video setting points to the group site (soundcreationsltd.com), which failed to load from this site (Lighthouse console error), so 2.3.6 fell back to the poster image.
- Now shipped with the theme: `assets/video/hero.mp4`, the same footage re-encoded for the web (1280x720, 25 fps, H.264, no sound track, fast start, about 4 MB instead of 23 MB). The `sc_hero_video` filter uses it whenever the setting is empty or points to another site. A video uploaded to this site's Media Library and set in Settings still takes priority.
- Still loaded after the page has finished loading (theme.js), so it does not affect first paint or the PageSpeed scores. Compatible with strict script security (media is not restricted).
- Upload: whole `soundcreations-rwanda/` folder (includes the new `assets/video/` folder).

## 2.3.8 - 19 October 2026: strict script security (CSP nonces + Trusted Types)
- New `inc/csp.php`. For visitors on the public site, the Content-Security-Policy now adds `script-src 'nonce-<random per request>' 'strict-dynamic' https: 'unsafe-inline'` and `require-trusted-types-for 'script'`. Clears Lighthouse "Ensure CSP is effective against XSS attacks" and "Mitigate DOM-based XSS with Trusted Types".
- Every `<script>` in the page gets the nonce (output buffer plus WordPress script attribute filters), so theme, plugin, Rank Math JSON-LD and Google tag keep working; scripts they load are trusted via 'strict-dynamic'. Code audit: no inline event handlers (onclick= etc.), no javascript: links, no eval in theme code.
- Trusted Types default policy (first script in head): strips <script>, on* handlers and javascript: URLs from HTML written by JavaScript; script URLs allowed only from this site and Google tag/analytics domains.
- Not applied in wp-admin, for signed-in users (editors, Elementor, admin bar), previews, REST, AJAX or cron.
- Off switch: Settings > General > "Strict script security" (or filter `scrw_strict_csp`). Use it if a new plugin stops working.
- license.txt: the 2.3.7 server rule covers it; re-check after a cache purge.
- Upload: whole `soundcreations-rwanda/` folder.

## 2.3.7 / parent 0.10.15 - 19 October 2026: final audit items
Re-test of 2.3.6: Lighthouse 99/100/100/100 (mobile and desktop), security headers A+, schema 0 errors.
- Images: project cards (homepage carousel, /projects/, solution pages, Elementor widget) now use the 768 px "medium_large" size instead of "large" (1024 px). Removes most of the remaining "Improve image delivery" savings (Atelier du Vin, Jalia, Intare, Christian Life Assembly photos).
- Brands page: breadcrumb now "Home > Brands" (was "Products"); eyebrow "Our brands" instead of the old "What we offer" setting.
- Partner logo strip: the 0 s opacity fallback animation (Lighthouse "non-composited animation") replaced by a normal 0.3 s fade after 2 s.
- HSTS header now includes "preload" (max-age 1 year, includeSubDomains). Submitting to hstspreload.org is optional and a separate decision.
- Server rules: the child theme writes a marked block "# BEGIN SCRW Performance" into .htaccess once per version (WordPress insert_with_markers, first admin page load by an administrator): 1-year browser cache for versioned CSS, JS, fonts and images, and blocks readme.html, license.txt and wp-config-sample.php. Removed automatically if the theme is switched. If .htaccess is not writable, option scrw_htaccess_status = not-writable and the host must add the same rules (see `scrw_htaccess_rules()`).
- Not changed on purpose: Content-Security-Policy script-src / Trusted Types (would block inline scripts and Google tag without a nonce system; unscored, planned separately); unused-CSS split (low gain, high regression risk).
- Upload: whole `soundcreations-rwanda/` folder; from `soundcreations/`: `inc/template-tags.php`, `inc/hardening.php`, `archive-sc_brand.php`, `style.css`. Then open any wp-admin page once as administrator.

## 2.3.6 / parent 0.10.14 - 19 October 2026: quality audit fixes
Full audit on 19 Oct 2026 (page crawl, securityheaders.com, PageSpeed Insights / Lighthouse 13.5, WCAG 2.2 AA checks). Baseline: security headers A+; Lighthouse mobile Performance 81, Accessibility 96, Best Practices 96, SEO 100. See `docs/QUALITY-AUDIT-2026-10.md`.
- Accessibility: "Learn more" / "Explore solution" card links raised from about 2.9:1 to about 6.7:1 contrast (`scrw-ui.css`).
- Best practices / speed: the hero no longer requests the group-site MP4 that failed to load (`sc_hero_video` filter; poster image used). Google tag now loads after first interaction or 4 s after load (`inc/analytics.php`). `scrw-ui.min.css` added and used. 8 large theme photos resized/recompressed (about 45% smaller) and 4 brand logos optimised.
- SEO: `/brands/yamaha/` and `/brands/fane/` 301 to `/yamaha/` and `/fane/` (duplicate pages). `/guides/`, `/buyer-guides/` and `/blog/` 301 to the Buyer Guides list. Guides show "By Sound Creations Rwanda technical team, Updated <date>" (child `single.php`); featured image loads eagerly with high priority.
- Content quality: brands page wording ("Brands we represent", "Partnering with world-class brands."), brand count now automatic (was a fixed "11+"); /products/ heading "Browse our product range."; no more "FANE FANE Products" label; brand pages now list all of that brand's products and no longer print brand and model twice; project pages hide empty "Brands used" and empty galleries; Architectural Acoustics grammar and British spelling (analyse, theatres); product price box uses the product's Availability field instead of contradicting it.
- Local SEO: contact and consultation pages list the Kigali office only (`sc_offices` filter), with one address format: KN1 Rd, Muhima, Kigali, Rwanda.
- Upload: whole `soundcreations-rwanda/` folder and whole `soundcreations/` folder (template files, style.css and assets/img changed).

## 2.3.5 / parent 0.10.13 - 19 October 2026: product use filter, testimonials, project results
- Products (`archive-sc_product.php`, new `inc/product-uses.php`): new "Filter by use" dropdown (Church & worship, Conference & meetings, Live events & DJ, Studio & podcast, Installed & public address). Uses are worked out from product name, brand and category; override per product with a custom field `_sc_uses` (e.g. `church,live`). Also works as a link: /products/?use=church.
- Testimonials (new `inc/testimonials.php`): "What our clients say" section on the homepage after Our Clients, also `[scrw_testimonials]`. Hidden until quotes are entered in Settings > Client Testimonials (one per line: Quote | Name | Role, Organisation; Editors can edit). Real, client-approved quotes only.
- Homepage project cards (parent `template-parts/designs/home.php`): show a one-line result from the project's Excerpt when filled (filter `sc_home_project_result`).
- Parent stand-in projects (Nairobi, Dubai) can now be switched off with `sc_home_projects_fallback`; the Rwanda child theme switches them off.
- Already in place and confirmed in code (no change needed): category, brand, search and sort filters on /products/; lazy-loaded images; deferred theme scripts; WebP product and project photos.
- Upload: whole `soundcreations-rwanda/` folder and `soundcreations/template-parts/designs/home.php` + `soundcreations/style.css`.

## 2.3.4 / Core 0.5.28 - 19 October 2026: sign-in page fix and polish
- Bug: on wp-login.php typed username and password were invisible. Cause: Core's older dark login style (`includes/login-branding.php`) set field text to near-white #ecebf2, while the child theme's light design (`assets/css/scrw-login.css`) gave the fields a near-white background.
- Fix (theme): fields now force dark text, purple caret, visible border, readable placeholder and browser autofill colours; `color-scheme: light`.
- Fix (Core 0.5.28): Core's login CSS and second footer are skipped when the theme provides its own sign-in design (`sc_core_login_theme_owns_design()`); `SC_CORE_VERSION` constant brought in line with the plugin header.
- Polish: card vertically centred with a red accent line, stale "Staff sign in" pseudo-heading removed, link row spacing, headline width, small-phone sizing.
- Upload: `soundcreations-rwanda/` (style.css, functions.php, assets/css/scrw-login.css) and `sound-creations-core/` (sound-creations-core.php, includes/login-branding.php). The theme CSS alone fixes the visibility issue.

## 2.3.3 - 18 October 2026: load the schema tidy-up
- Root cause found: `functions.php` never loaded `inc/schema-tidy.php` (the require line was lost between 2.3.0 and 2.3.1), so the sameAs de-duplication and the parent company @id never ran. Confirmed while logged in (uncached): the live JSON-LD still had web.facebook.com and no https://soundcreationsltd.com/#organization. Cache was not the cause.
- Fix: `require_once SCRW_DIR . 'inc/schema-tidy.php';` added after guides-seed. Upload `functions.php` and `style.css` (or the whole folder).

## 2.3.2 / Core 0.5.27 - 18 October 2026: security review, CTA styling, documentation
- Security review of all custom code (themes, Core, Enquiries): no SQL injection, XSS, CSRF, access-control or upload vulnerability found; existing controls documented with evidence in new `SECURITY-AUDIT.md`.
- Fixed S-01 (low) in `sound-creations-core/includes/post-types.php`: the /resources/ to /videos/ 301 no longer builds its target from the client-supplied Host header; it uses `home_url()`. Core version 0.5.27.
- `soundcreations-rwanda/assets/css/scrw-ui.css`: photo CTA band button (Projects page "Request a Consultation") keeps label and arrow on one line with the arrow sized to the text; wraps centred only below 420 px.
- New docs: `ARCHITECTURE.md`, `ARCHITECTURE-ESSENTIALS.md`, `PRD.md`, `SECURITY-AUDIT.md`. README (docs index, versions) and AGENTS.md (read order, security rules, known operational issues) updated.
- Not changed on purpose: no code was removed or restructured without a PHP runtime to test it. Placeholder markers (`[VERIFY]`, `[CONTENT TO BE CONFIRMED]`) live only in admin-only seed tools and are already hidden from visitors by the placeholder guard in `security-plus.php`.

## 2.3.1 - 18 October 2026: post-2.3.0 live check fixes
- `inc/seo-market.php`: product "Price in Rwanda" box rebuilt with explicit sizes on divs and a self-styled WhatsApp button. Live check found the explanatory text rendering at heading size and the WhatsApp button showing as a red bar with no readable label.
- Live check of 2.3.0 (WordPress shows 2.3.0, no PHP errors): validator.schema.org still shows the PRE-2.3.0 sameAs list and no parentOrganization @id, even with a cache-busting query string, so the 2.3.0 schema tidy-up (`inc/schema-tidy.php`) is not running on the server. Either `functions.php` / `inc/schema-tidy.php` were not uploaded, or the page cache is serving stale HTML: WP_CACHE is enabled and the `advanced-cache.php` drop-in from the inactive Seraphinite Accelerator plugin is still installed. Theme File Editor is disabled (403), so files cannot be checked from wp-admin. Fix: re-upload the whole `soundcreations-rwanda/` folder, then clear the cache (reactivate Seraphinite and purge, or remove the stale drop-in and set WP_CACHE false via the host).
- Settings (not code): Projects page stats sub-note for "4 Regional Locations" changed from "Rwanda" to "Rwanda, Kenya, DRC Congo, UAE" to match the homepage.
- Projects page bottom call-to-action shows awkward label wrapping and an oversized arrow (parent theme styling); logged for the next design pass.

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
