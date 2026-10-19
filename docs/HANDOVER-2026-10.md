# Handover: work log 11-19 October 2026

Prepared by Pimofy Digital (Moses Munyi) with the AI assistant. Read with `AGENTS.md`, `CHANGELOG.md`, `SECURITY-AUDIT.md` and `docs/QUALITY-AUDIT-2026-10.md`.

## Current state (19 Oct 2026)
- Live: child theme **Sound Creations Rwanda 2.3.10**, parent **0.10.15**, Core **0.5.28**, Enquiries 0.2.0, Elementor 4.3.5, Rank Math. Committed but **not yet uploaded**: **2.3.11 / parent 0.10.16** (accessibility pass, YouTube click-to-play).
- Lighthouse homepage (2.3.8+): mobile 99/100/100/100, desktop 100/100/100/100. Security headers A+. Schema 0 errors / 0 warnings.
- Deploy method: owner uploads folders by FTP/file manager; no CI. Theme File Editor is disabled (403). cPanel at d9742.usc1.stableserver.net does **not** host the .rw domain (only soundcreationsltd.com and the old rwanda. subdomain).

## Releases in this period
| Version | What |
|---|---|
| 2.3.1 | Product "Price in Rwanda" box rebuilt |
| 2.3.2 / Core 0.5.27 | Security review (SECURITY-AUDIT.md), redirect uses home_url(), Projects CTA button, new docs |
| 2.3.3 | Loaded `inc/schema-tidy.php` (sameAs de-duplication, parent @id) |
| 2.3.4 / Core 0.5.28 | Sign-in page: invisible field text fixed (Core dark login CSS skipped when theme owns design) |
| 2.3.5 / parent 0.10.13 | Products "Filter by use" (`inc/product-uses.php`, override meta `_sc_uses`), testimonials (`inc/testimonials.php`, Settings > Client Testimonials, hidden until filled), project result line from Excerpt, no Nairobi/Dubai stand-in projects |
| 2.3.6 / parent 0.10.14 | Quality audit fixes: contrast, hero video off-site guard, delayed Google tag, minified `scrw-ui.min.css`, smaller images, /brands/yamaha and /brands/fane 301, /guides/ 301 to Buyer Guides, guide byline (child `single.php`), brand pages list all products, empty project sections hidden, Kigali-only offices (`sc_offices` filter) |
| 2.3.7 / parent 0.10.15 | 768 px project card images, Brands breadcrumb/eyebrow, partner fade, HSTS preload, `.htaccess` block "SCRW Performance" (1-year cache, blocks readme.html, license.txt, wp-config-sample.php) written once per version on admin load |
| 2.3.8 | Strict CSP: per-request nonce + 'strict-dynamic', Trusted Types default policy (`inc/csp.php`). Visitors only. **Off switch: Settings > General > "Strict script security"** |
| 2.3.9 | Hero video shipped in theme `assets/video/hero.mp4` (4 MB re-encode of the group-site 23 MB file), used when the setting points off-site |
| 2.3.10 | Featured Projects carousel: working desktop arrows; mobile prev/next, dots, counter, swipe hint; keyboard |
| 2.3.14 / parent 0.10.19 | jQuery to footer for visitors, Brands hero WebP + preload, lighter FANE hero, logo and client logos (About 87, Brands 94 before) |
| 2.3.13 / parent 0.10.18 | Contact office-list semantics; no duplicate project names on sector pages |
| 2.3.12 / parent 0.10.17 / Enquiries 0.2.1 | Full-site crawl fixes: broken links (acoustics, become-a-dealer page), one-time exact-text content corrections (`inc/content-fixes.php`), Yamaha loudspeaker label, no duplicate project names, UK spelling |
| 2.3.11 / parent 0.10.16 | Contrast on red text (dark theme #ff6b6b), underlined text links, heading order (products, yamaha), contact list semantics, 24 px dots, YouTube click-to-play on Architectural Acoustics |

## Changes made in WordPress admin (not in code)
- Sound Creations > Settings > Projects stats: `22+ | Years of Group Experience`, `4 | Regional Locations | Rwanda, Kenya, DRC Congo, UAE`, `850+ | Group Projects Completed`.
- Site Title: "Sound Creations Rwanda". About: "Sound Creations since 2004" (22+ years is the agreed figure; company founded 2004). FANE brand: "since 1958". Product renamed "AVer VC520 Pro2" (URL unchanged).
- Privacy Policy page replaced with a full policy under Rwanda Law No. 058/2021 (effective 19 Oct 2026; contact fred@soundcreationsltd.com). Copy in the Town library. Fred should review.
- Four Buyer Guides published with AI illustrations (captioned "AI-generated illustration"), Rank Math focus keywords set, Google indexing requested: quotation checklist, acoustic treatment vs soundproofing, PA system for church, commercial sound system maintenance.

## Search and listings
- Google Search Console: verified; old domain change of address submitted 18 Oct 2026 (keep the DNS TXT record and the 301s).
- Bing Webmaster Tools: the account in the browser session only has vtechaudio.co.ke; the .rw site is under another login. The 4 guides are not yet submitted to Bing.
- Google Business Profile: owned by the original company email; waiting for transfer/manager access. Do not change it until access is confirmed.
- Dealer listing correction emails (Yamaha, Shure, FANE) are drafted in Agnes's Gmail, not sent.

## Documents delivered (Town library, collection "Sound Creations Rwanda")
- Website User & Editing Guide v2, SEO/AEO/GEO Work Report v4 (spacing fixed), privacy policy HTML, guide photos.
- Email to Fred (fred@soundcreationsltd.com, Manager) with the work report: text prepared, not sent by the assistant.

## Open items
| Item | Owner |
|---|---|
| Upload 2.3.11 and re-test the 12 page types | Pimofy |
| Upload 2.3.12 (theme, parent files, Enquiries plugin), open wp-admin once, re-test | Pimofy |
| Crawl data checks: MINECOFIN wired MXC vs wireless MXCW, "Mulindi Hall" label, PGA48 SKU vs model, MG20XU dimensions, after-sales "one year free" vs manufacturer warranty | Fred / sales team |
| Ntare Louisenlund equipment list (identical to Atelier du Vin), school district, Intare Conference Arena vs Intare Kivu Arena naming | Fred |
| Two-step login for every administrator; staff as Editors | Sound Creations |
| Stale `advanced-cache.php` from inactive Seraphinite; LiteSpeed cache still serving old license.txt | Host |
| Real project photos to replace AI images; client testimonials | Sound Creations |
| Results check against the report baseline around 16 Nov 2026 | Pimofy |
| Mobile menu and sub-menu behaviour under strict CSP: confirm on a phone | Owner |

## Rules learned
- Do not restructure code without a PHP runtime to test. Keep SCRW_VERSION in `functions.php` and `style.css` equal; add a CHANGELOG entry per release.
- Generated images must be copied to `vfs:///session/files/...` before the browser agent can upload them; it cannot read Content Library or session text files (paste text inline).
- The old VTECH number +254 728135246 is outdated (other client, not this site).

## Final re-test, 19 October 2026 (2.3.12 live; 14:46-14:53 EAT, Lighthouse 13.5)
Scores: Performance / Accessibility / Best Practices / SEO.

| Page | Mobile | Mobile LCP | Desktop |
|---|---|---|---|
| Home | 99 / 100 / 100 / 100 | 2.0 s | 100 / 100 / 100 / 100 |
| Products | 99 / 100 / 100 / 100 | 1.8 s | 100 / 100 / 100 / 100 |
| Shure SM57 | 100 / 100 / 100 / 100 | 1.7 s | 100 / 100 / 100 / 100 |
| Projects | 98 / 100 / 100 / 100 | 2.4 s | 100 / 100 / 100 / 100 |
| Christian Life Assembly | 100 / 100 / 100 / 100 | 1.8 s | 100 / 100 / 100 / 100 |
| Architectural Acoustics | 99 / 100 / 100 / 100 | 2.2 s | 100 / 100 / 100 / 100 |
| Acoustic Treatment vs Soundproofing | 99 / 100 / 100 / 100 | 2.0 s | 100 / 100 / 100 / 100 |
| Contact | 100 / 97 / 100 / 100 | 1.6 s | 100 / 97 / 100 / 100 |
| About | 86 / 100 / 100 / 100 | 4.1 s | 99 / 100 / 100 / 100 |
| Yamaha | 98 / 100 / 100 / 100 | 2.4 s | 100 / 100 / 100 / 100 |
| Brands | 94 / 100 / 100 / 100 | 3.1 s | 100 / 100 / 100 / 100 |

- Security headers A+; schema 0 errors / 0 warnings on product, project, guide and contact pages.
- Verified live: Warranty and After-Sale Services show fred@soundcreationsltd.com, +250 783 141 050 and the Kigali address; 22+ years wording; "exam announcements"; Acoustics links and 301; Become a Dealer page with dealer form; Analogue, CBR10 289 mm, VC520 Pro2 model, 22 frets, Yamaha loudspeaker card; carousel arrows.
- Only scored failure: Contact office list semantics, fixed in 2.3.13 (to upload). Duplicate project names on sector pages also fixed in 2.3.13.
- Next performance follow-up: About (Elementor page) mobile LCP 4.1 s and Brands mobile LCP 3.1 s. Unscored only: hero video captions track, generic "View details" / "Visit Website" link text.
- Hero video source loads (hero.mp4?ver=2.3.12); visual playback to be confirmed on a phone.

## 2.3.13 verified live (19 Oct 2026, 15:15 EAT)
- Child 2.3.13, parent 0.10.18 confirmed. Contact office list: no role="button". Hotel/bar sector page: each project name printed once.
- PageSpeed Contact: 100/100/100/100 mobile and desktop (was 97 accessibility). Homepage: 99/100/100/100 mobile, 100/100/100/100 desktop.
- Remaining (unscored only): hero video captions track. Next speed job: About page (Elementor) mobile LCP 4.1 s, Brands mobile 3.1 s.
