# Handover: work log 11-19 October 2026 (last updated 19 Oct 2026, 23:00 EAT)

Prepared by Pimofy Digital (Moses Munyi) with the AI assistant. Read with `AGENTS.md`, `CHANGELOG.md`, `SECURITY-AUDIT.md` and `docs/QUALITY-AUDIT-2026-10.md`.

## Current state (19 Oct 2026, 23:00 EAT) - READ THIS FIRST
- Live and verified: child theme **Sound Creations Rwanda 2.3.22**, parent **0.10.22**, Core **0.5.28**, Enquiries 0.2.1, Elementor 4.3.5, Rank Math. Nothing is committed-but-not-uploaded.
- Lighthouse (2.3.14): homepage mobile 99 / desktop 100; About mobile 97; Brands mobile 97; Accessibility/Best Practices/SEO 100. Security headers A+. Schema 0 errors / 0 warnings.
- Deploy method: owner uploads changed files by FTP/file manager, then clears the cache; no CI, no PHP runtime for the AI agent (code is never test-run before upload, so verify live after each upload). Theme File Editor is disabled (403). cPanel at d9742.usc1.stableserver.net does **not** host the .rw domain.
- Strict CSP is on (`inc/csp.php`); off switch Settings > General > "Strict script security".
- **SEO rule (owner, 19 Oct 2026): never remove or weaken a keyword the site already ranks for; only add.** Protected first-place keywords and where they live are in `docs/KEYWORD-STRATEGY.md` section 6 and `scrw_seo_keywords()` in `inc/seo-boost.php`.

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
| 2.3.22 | Homepage AV company H2 restored (labels no longer swapped by section-titles) |
| 2.3.21 | WhatsApp teaser auto-hides; homepage AV company section two-column redesign with editable service links |
| 2.3.20 | Settings > Our Clients (logos editable); project proof on Church/Conference; Intare Kivu Arena + brand titles |
| 2.3.19 | Homepage title keeps brand + Yamaha; 100-phrase keyword bank; KEYWORD-STRATEGY.md section 6 |
| 2.3.18 | Priority keyword titles; homepage AV company FAQ + FAQPage (Settings > Homepage FAQ) |
| 2.3.17 / parent 0.10.22 | Kenya X link removed; Rwanda fallbacks for all settings; "none" hides a field |
| 2.3.16 / parent 0.10.21 | Hero caption text hidden (track kept); About numbered 2x2 cards, two-column tick lists |
| 2.3.15 / parent 0.10.20 | About text below Our Clients restructured (cards, check lists, CTA, banner photo); captions track; brand-named links |
| 2.3.14 / parent 0.10.19 | jQuery to footer for visitors, Brands hero WebP + preload, lighter FANE hero, logo and client logos (About 87, Brands 94 before) |
| 2.3.13 / parent 0.10.18 | Contact office-list semantics; no duplicate project names on sector pages |
| 2.3.12 / parent 0.10.17 / Enquiries 0.2.1 | Full-site crawl fixes: broken links (acoustics, become-a-dealer page), one-time exact-text content corrections (`inc/content-fixes.php`), Yamaha loudspeaker label, no duplicate project names, UK spelling |
| 2.3.11 / parent 0.10.16 | Contrast on red text (dark theme #ff6b6b), underlined text links, heading order (products, yamaha), contact list semantics, 24 px dots, YouTube click-to-play on Architectural Acoustics |

## Changes made in WordPress admin (not in code)
- Sound Creations > Settings > Projects stats: `22+ | Years of Group Experience`, `4 | Regional Locations | Rwanda, Kenya, DRC Congo, UAE`, `850+ | Group Projects Completed`.
- Site Title: "Sound Creations Rwanda". About: "Sound Creations since 2004" (22+ years is the agreed figure; company founded 2004). FANE brand: "since 1958". Product renamed "AVer VC520 Pro2" (URL unchanged).
- Privacy Policy page replaced with a full policy under Rwanda Law No. 058/2021 (effective 19 Oct 2026; contact fred@soundcreationsltd.com). Copy in the Town library. Fred should review.
- Four Buyer Guides published with AI illustrations (captioned "AI-generated illustration"), Rank Math focus keywords set, Google indexing requested: quotation checklist, acoustic treatment vs soundproofing, PA system for church, commercial sound system maintenance.

- **Homepage Rank Math (page ID 10), set 19 Oct 2026 and verified live:** title "Sound Creations Rwanda | Audio Visual, Yamaha & Pro Audio Kigali"; description "Audio visual company in Kigali and authorised Yamaha distributor in Rwanda: sound system installation, acoustic solutions, stage lighting, PA and pro audio." This is typed in Rank Math, not in code; it must keep "Sound Creations Rwanda", Yamaha and Pro Audio.
- Settings > Homepage FAQ (`options-general.php?page=scrw-home-faq`): heading, intro, service links (one "URL | Label" per line) and 7 questions for the homepage "Audio visual company in Rwanda" section (2.3.18 / 2.3.21).
- Settings > Our Clients (`page=scrw-clients`, option `scrw_client_logos`, one "Image URL | Name" per line): pre-filled with 11 logos; edit or add via Media Library URLs (2.3.20).
- Theme settings: an empty box falls back to the Rwanda value (never Kenya); typing `none` hides that item (2.3.17). The Kenya X/Twitter link is gone; socials are Rwanda Facebook, Instagram, LinkedIn, WhatsApp.

## Search and listings
- Google Search Console: verified; old domain change of address submitted 18 Oct 2026 (keep the DNS TXT record and the 301s). Still to do by the owner: Request indexing for Home, About, Installation, Architectural Acoustics, Church.
- Bing: the 4 buyer guides were submitted via Rank Math > Instant Indexing (IndexNow) on 19 Oct 2026 ("Successfully submitted 4 URLs"): /maintaining-commercial-sound-system/, /choosing-pa-system-church-hall-rwanda/, /acoustic-treatment-vs-soundproofing/, /sound-system-quotation-checklist-rwanda/.
- Google Business Profile: correct (KN1 Rd, +250 783 141 050, 4.8 stars / 15 reviews) but owned by the original company account. Agnes requested access as soundcreationsrwanda@gmail.com; waiting for Fred (mutwarefred@gmail.com) to approve. Fallback: claim/verify after about a week. **Do not change the profile until access is confirmed.**
- Dealer listing correction emails (Yamaha, FANE) are drafted in Agnes's Gmail, not sent. Shure: handled by Kenya, do not ask Shure to change it.
- Directory listings still to create: RwandaYP, contact.page, Shakisha.

## Documents delivered (Town library, collection "Sound Creations Rwanda")
- Website User & Editing Guide v2, SEO/AEO/GEO Work Report v4 (spacing fixed), privacy policy HTML, guide photos.
- Email to Fred (fred@soundcreationsltd.com, Manager) with the work report: text prepared, not sent by the assistant.
- Client-facing "Sound Creations Rwanda - Website Handover.docx" (19 Oct 2026). Dashboard section names in it are best guesses, not yet confirmed by Agnes.

## Open items
| Item | Owner |
|---|---|
| Approve Google Business Profile access for soundcreationsrwanda@gmail.com | Fred |
| Confirm: Ntare Louisenlund vs Atelier du Vin equipment (identical lists), MINECOFIN wired MXC vs wireless MXCW (Conference page proof block lists MXCWAP), "Mulindi Hall" label, PGA48 SKU vs model, MG20XU dimensions, after-sales "one year free" vs manufacturer warranty | Fred / sales team |
| Send Yamaha and FANE dealer-listing correction emails (drafts ready) | Agnes |
| Create RwandaYP, contact.page and Shakisha listings | Agnes |
| Search Console Request indexing: Home, About, Installation, Acoustics, Church | Agnes |
| Two-step login for every administrator; staff as Editors | Sound Creations |
| Real project photos to replace AI images; client testimonials | Sound Creations |
| Hero video playback and the WhatsApp greeting auto-hide (shows once after 15 s, hides after 8 s or 120 px scroll): confirm on a real phone; also confirm the homepage AV section stacks to one column on mobile | Owner |
| Stale `advanced-cache.php` from inactive Seraphinite; LiteSpeed cache | Host |
| Progress check (Google/Bing) 1 Nov 2026; results comparison and AI-answer check mid-Nov 2026 against the report baseline | Pimofy |

## Rules learned
- Do not restructure code without a PHP runtime to test. Keep SCRW_VERSION in `functions.php` and `style.css` equal; add a CHANGELOG entry per release.
- Generated images must be copied to `vfs:///session/files/...` before the browser agent can upload them; it cannot read Content Library or session text files (paste text inline).
- The old VTECH number +254 728135246 is outdated (other client, not this site).
- `inc/section-titles.php` swaps every `sc-eyebrow` label into the big heading site-wide. In new sections use a different class for small labels (e.g. `scrw-hfaq__kicker`), or the keyword heading gets demoted (bug fixed in 2.3.22).
- `scrw-ui.min.css` is generated from `scrw-ui.css`; edit the source and regenerate both. JS has only `assets/js/scrw-ui.js` (no minified copy).
- Kenya and Rwanda use separate logins and email accounts; never use one for the other.

## 2.3.15 - 2.3.22 verified live (19 Oct 2026, evening)
- 2.3.17: no X/Twitter icon; Rwanda socials only.
- 2.3.18/2.3.19: priority-keyword titles live on About, Installation, Architectural Acoustics, Church, Professional Audio, Solutions (Conference keeps its manual Rank Math title); `/solutions/acoustics/` 301s to architectural-acoustics; homepage FAQ with 7 questions + FAQPage schema; no caption text over the hero video; About 2x2 numbered cards.
- 2.3.20: Our Clients logos from Settings; Church page project block (Christian Life Assembly + 13 other churches); Conference page MINECOFIN block (6 equipment lines); Intare Kivu Arena title "Intare Kivu Arena, Rubavu | Acoustics & Sound System Gisenyi"; brand page titles (shure, bose-professional, db-technologies, allen-heath).
- 2.3.21/2.3.22: homepage "Audio visual company in Rwanda" section is two columns on desktop (left sticky panel: "Who we are" label, H2, intro, 6 service links, Request a Consultation + Chat on WhatsApp; right: numbered 01-07 accordion). H2 text confirmed "Audio visual company in Rwanda"; labels are paragraphs; all links work; no overlap; no PHP errors. WhatsApp "Chat with us" pill does not cover content.


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

## 2.3.14 verified live (19 Oct 2026, 15:46 EAT)
- About mobile 87 -> **97** (LCP 4.0 s -> 2.4 s), desktop 99. Brands mobile 94 -> **97** (LCP 3.0 s -> 2.6 s), desktop 100. Homepage 99 mobile / 100 desktop.
- Accessibility, Best Practices and SEO 100 on all three; no console errors. About Elementor sections, images and 14 internal links checked; Brands banner displays.
- Unscored only: hero video captions track; generic "Visit Website" / "View details" link text.
