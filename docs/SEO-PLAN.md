# SEO and schema plan: Sound Creations Ltd Rwanda

Audit date: 11 October 2026. Child theme release: **1.2.0**.

## What the live audit found

| Area | Finding | Status in 1.2.0 |
| --- | --- | --- |
| LocalBusiness | Kigali location was published with the Kenya id `#branch-nairobi` and the name "Sound Creations Ltd Rwanda - Kigali" | Fixed: id `#localbusiness`, type `LocalBusiness` + `ElectronicsStore`, exact business name, `hasMap`, `parentOrganization` by @id |
| Service areaServed | Solution pages told Google the service covers Kenya and the UAE (inherited from Core) | Fixed: Rwanda, main Rwandan cities, DRC, East Africa |
| sameAs | Included a Google Maps search URL and the Kenya X account | Fixed: removed; map link moved to `hasMap` |
| contactPoint | Claimed KE and AE; second Kigali line missing | Fixed: RW/CD, two contact points with emails |
| knowsAbout / keywords | Filled with search phrases ("Yamaha dealer Kigali") | Fixed: real expertise topics; `keywords` dropped from business nodes |
| Org description | Bare tagline | Fixed: factual one-sentence description from published site copy |
| Yamaha / FANE pages | WebPage `about` Brand only | Added a `Service` node (authorised Yamaha distribution, FANE supply) |
| Projects | No `contentLocation` when location is stored as a field | Added Place in Rwanda from `_sc_location`; `provider` added |
| Products | No category | Added `category` from product category |
| Open Graph | No `og:image` on home, Yamaha, solutions, contact | Added theme-photo fallback via Rank Math; featured/Rank Math images still win |
| Front page | No `primaryImageOfPage` | Added |

Unchanged by design: no prices, ratings, review counts, stock or invented coordinates. Product pages will keep showing "missing offers" in the Rich Results Test; that is expected for an enquiry-only catalogue.

## Keyword map (one primary intent per page)

These are candidates to validate in Search Console and a keyword tool, not measured volumes.

| Priority | Primary intent | Landing page |
| --- | --- | --- |
| P1 | audio visual company Rwanda / AV company Kigali | `/` |
| P1 | sound system installation Rwanda | `/solutions/installation/` |
| P1 | Yamaha Rwanda / Yamaha distributor Kigali | `/yamaha/` |
| P1 | professional audio equipment Rwanda / PA systems Kigali | `/solutions/professional-audio/` |
| P1 | acoustic treatment Rwanda / acoustic panels Kigali | `/solutions/architectural-acoustics/` |
| P1 | conference room sound system Kigali | **gap**: new conferencing solution page, built on the MINECOFIN project |
| P2 | church sound system Rwanda | **gap**: worship page built on Christian Life Assembly |
| P2 | stage lighting Rwanda | `/solutions/lighting-solutions/` |
| P2 | DJ equipment Rwanda / studio equipment Kigali | `/solutions/dj-solutions/`, `/solutions/studio-solutions/` |
| P2 | Shure / dB Technologies / FANE Rwanda | brand pages |
| P3 | DRC / East Africa | only where delivery is real |

Avoid near-duplicate "sound system Kigali" vs "sound system Rwanda" pages (doorway risk).

## Release 1.3.0: sector landing pages (inc/seo-content.php)

Seeded once on the first wp-admin load after upload. Existing pages and FAQs are never overwritten.

| New page | Primary search intent | Proof on the page |
| --- | --- | --- |
| `/solutions/conference-room-sound-systems/` | conference room sound system Kigali, boardroom audio Rwanda | MINECOFIN Shure Microflex |
| `/solutions/church-sound-systems/` | church sound system Rwanda, church PA Kigali | Christian Life Assembly |
| `/solutions/school-and-auditorium-sound/` | school PA system Rwanda, auditorium sound | Ntare Louisenlund, Intare Kivu Arena |
| `/solutions/hotel-bar-restaurant-sound/` | hotel / bar sound system Kigali | Romantic Garden, Atelier du Vin |

Each page has: keyword title and meta description, 400 to 600 words of body copy, 4 to 5 visible FAQs (FAQPage schema), a related-projects strip, contextual links to Yamaha, acoustics, lighting, installation and service pages, and a Service node with `audience`.

Visible FAQs were also added to Architectural Acoustics, Lighting, DJ, Studio and Service & Backup (only where the FAQ box was empty). The footer Solutions column now links to the sector pages (only if the footer list is still the seeded default).

Titles deliberately avoid the words "audio", "acoustic", "install", "integ" and "visual", because the parent template switches layout on those words and hides body text for "audio".

## Release 1.6.0: live check of 13 October 2026

What was checked live: homepage head, JSON-LD, robots.txt, sitemaps, every solution URL, and the old Rwanda domain.

| Area | Finding | Fix in 1.6.0 |
| --- | --- | --- |
| Schema graph | One JSON-LD graph on the homepage: Organization + ProfessionalService, WebSite, LocalBusiness + ElectronicsStore (`#localbusiness`), WebPage, BreadcrumbList, ImageObject. No duplicate Rank Math graph. Canonical, robots and og:image all correct | Kept as is |
| XML sitemap | `robots.txt` points to `/sitemap_index.xml`, and `/wp-sitemap.xml` and `/sitemap.xml` 301 to it, but `/sitemap_index.xml` returned **404**. Google had no working sitemap | `inc/seo-health.php`: flushes rewrite rules once on the next wp-admin load, and repairs itself (at most every 12 h) if a sitemap URL ever 404s again |
| Sector pages | The four 1.3.0 sector pages (conference room, church, school/auditorium, hotel/bar) returned **404**. `inc/seo-content.php` was never loaded by `functions.php`, so they were never created | `functions.php` now loads `inc/seo-content.php`; the pages, their FAQs and the related-projects strips are created on the next wp-admin load |
| Group wording | Core's fallback meta descriptions for products, projects, services and categories said "across Africa and the Middle East" | Rewritten to Rwanda wording on this site only; descriptions typed in Rank Math are untouched |
| Old domain | `rwanda.soundcreationsltd.com` (old WordPress site) is still live with its own canonical, competing for the same searches | `server/htaccess-old-domain-redirect.txt`: page-by-page 301s to the matching new pages, everything else to the homepage. Must be pasted on the server that hosts that subdomain |

### Keyword clusters to page map (from the strategy brief)

| Cluster | Landing page |
| --- | --- |
| Professional sound systems, PA suppliers, speakers, mixers and microphones | `/solutions/professional-audio/`, `/products/` |
| Yamaha audio equipment Rwanda | `/yamaha/` |
| Sound system installation, commercial audio installation | `/solutions/installation/` |
| Conference room AV, conference microphone systems | `/solutions/conference-room-sound-systems/` |
| Church sound system installation | `/solutions/church-sound-systems/` |
| School auditorium sound, auditorium design | `/solutions/school-and-auditorium-sound/` |
| Hotel audiovisual solutions | `/solutions/hotel-bar-restaurant-sound/` |
| Acoustic treatment, acoustic solutions for auditoriums | `/solutions/architectural-acoustics/` |
| Stage lighting Kigali | `/solutions/lighting-solutions/` |
| DJ equipment, recording studio equipment | `/solutions/dj-solutions/`, `/solutions/studio-solutions/` |
| Servicing and technical support | `/solutions/service-and-backup/` |
| AV company / audio visual equipment supplier Rwanda | `/` |
| LED screens and displays Rwanda | **not built**: only add a Visual Solutions page once the Rwanda team confirms they supply and install LED screens/displays, with at least one real project |

### After deploying 1.6.0

1. Upload `soundcreations-rwanda/`, open wp-admin once, clear LiteSpeed cache.
2. Check `https://soundcreationsltd.rw/sitemap_index.xml` opens. If it still 404s: **Settings > Permalinks > Save**, and in Rank Math > Sitemap Settings confirm the sitemap module is on.
3. Check the four sector pages open and appear under `/solutions/`.
4. Paste `server/htaccess-old-domain-redirect.txt` into the `.htaccess` of rwanda.soundcreationsltd.com, then test `https://rwanda.soundcreationsltd.com/conferencing/` lands on the conference room page.
5. Search Console: add soundcreationsltd.rw as a Domain property, submit `/sitemap_index.xml`, request indexing for the home page, Yamaha and the four sector pages. Also verify rwanda.soundcreationsltd.com and use URL Inspection on a few old URLs to confirm Google sees the 301s.

## Release 1.7.0: Rwanda search demand

Full research, competitor list and keyword to page map: [KEYWORD-STRATEGY.md](KEYWORD-STRATEGY.md). In short, Rwandan buyers search "X price in Rwanda" far more than anything else, so product pages now target price searches (titles, a visible price box, optional RWF price with Offer schema, buyer FAQ), and the showroom is typed as a MusicStore too.

## Content still to add (needs the Rwanda team)

1. Expand each project to 300+ words: brief, equipment list, result, photo captions, client quote.
2. Real photos for the four sector pages (set a Featured Image on each).
3. Yamaha hub FAQ and model-level pages for the best-selling Yamaha mixers and speakers.
4. A blog or guide section answering buyer questions ("how to choose a church mixer", "PA system cost factors in Rwanda").
5. Reviews: ask clients to review the Google Business Profile. Reviews and GBP are the biggest local-ranking factors the website cannot control.

## Business facts to reconcile

- Yamaha's dealer listing for Sound Creations (RW) Ltd: check its phone/email/address match `+250 783 141 050`, KN1 Rd, Muhima, and ask Yamaha to update if not.
- Google Business Profile: same name, address and phone as the site (still pending).
- Kigali lat/long for `geo` (enter in Settings; left blank rather than guessed).
- Rwanda X/Twitter account, if one exists (Kenya account removed from sameAs).

## Deployment checklist

1. Back up the live `wp-content/themes/soundcreations-rwanda/`.
2. Upload the updated `soundcreations-rwanda/` folder (new file `inc/schema-rw.php`, changed `inc/seo-boost.php`, `functions.php`, `style.css`).
3. Clear LiteSpeed / page cache.
4. Test: home, `/yamaha/`, `/fane/`, `/solutions/professional-audio/`, one product, one project in the [Rich Results Test](https://search.google.com/test/rich-results) and the [Schema validator](https://validator.schema.org/). Expect one JSON-LD graph, `#localbusiness` present, no `#branch-nairobi`.
5. Share the homepage URL in the Facebook Sharing Debugger / WhatsApp to confirm the image.
6. Search Console: submit `/sitemap_index.xml`, request indexing for the pages above.
7. Rollback: re-upload the backup folder.


## Release 2.0.0: GEO and AEO (18 October 2026)

Goal: be the source AI assistants (ChatGPT search, Perplexity, Google AI Overviews, Copilot) quote when someone asks "who sells Yamaha in Rwanda" or "church sound system installers in Kigali".

| Area | Change | File |
| --- | --- | --- |
| llms.txt | Live, fact-only summary at `/llms.txt` with key pages | `inc/seo-ai.php` |
| AI crawlers | Explicit robots.txt rules allowing answer-engine bots; training bots switchable | `inc/seo-ai.php` |
| Answer markup | `speakable` (H1, FAQ Q/A) and `lastReviewed` on the WebPage node | `inc/seo-ai.php` |

### What still moves GEO/AEO most (content, needs the Rwanda team)
1. Answer-first copy: open each solution/sector page with a 40-60 word direct answer (who, what, where in Rwanda, how to get a quote).
2. Buyer guides with real numbers: "PA system cost factors in Rwanda", "how to choose a church mixer", "Yamaha keyboard price guide Rwanda".
3. Named, dated project write-ups with equipment lists and client quotes (citable evidence).
4. Consistent mentions off-site: Google Business Profile, Yamaha dealer locator, Rwandan directories, LinkedIn. AI engines cross-check these.
5. Test monthly: ask ChatGPT, Perplexity and Google "Yamaha distributor Rwanda", "sound system installation Kigali", "church sound system Rwanda" and log whether Sound Creations is named.

### After deploying 2.0.0
1. Upload `soundcreations-rwanda/`, clear LiteSpeed cache.
2. Open `/llms.txt` and `/robots.txt` and check the new sections.
3. Run the home page and one solution page through the Rich Results Test: still one graph, now with `speakable`.


## Release 2.1.0: post-implementation audit (18 October 2026)

2.0.0 was reviewed against the owner's brief before deployment and corrected (robots.txt group defect, llms.txt off by default, speakable removed). Full findings: [SEO-AUDIT-2026-10.md](SEO-AUDIT-2026-10.md). Top three actions are outside the code: fix the Yamaha directory phone, 301 the old rwanda.soundcreationsltd.com site, finish Google Business Profile and Search Console.


## Release 2.2.0: buyer guides (18 October 2026)

Four answer-first guides (quotation checklist, acoustic treatment vs soundproofing, choosing a church/hall PA, maintaining a commercial sound system) seeded as drafts. Each opens with a direct answer, gives practical criteria, links to the matching solution and project pages, ends with visible FAQs and a Kigali contact. Publish only after a Sound Creations engineer reviews them and adds real photos or project examples. Then request indexing in Search Console and add them to the AI citation log in SEO-AUDIT-2026-10.md.
