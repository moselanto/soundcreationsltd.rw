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
