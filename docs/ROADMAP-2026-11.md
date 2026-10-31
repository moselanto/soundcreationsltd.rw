# Roadmap and tracker: Master Audit 31 Oct 2026

Source: `docs/MASTER-AUDIT-2026-10-31.md`. Update this file in the same commit as any work on it. Status: DONE / IN PROGRESS / BLOCKED (needs business or login) / TODO.

## Live verification, 31 Oct 2026 (crawl of all 122 sitemap URLs, observed with curl)
| Check | Result |
|---|---|
| Deployed version | Child 2.3.23 live (hero.mp4?ver=2.3.23), Enquiries 0.2.1. Parent asset ver = file mtime (not readable as version). |
| Old domain rwanda.soundcreationsltd.com | **DONE / live**: homepage 301 to soundcreationsltd.rw; deep URLs map page-by-page (about-us -> /about/, shure -> /brands/shure/, contacts -> /contact/, yamaha-* -> /yamaha/, projects -> /projects/). Handover note "still live and indexed" is outdated. Keep the 301s and the Search Console change of address. |
| Status codes | 122/122 return 200. |
| H1 | Exactly one H1 on every URL. |
| Images | No <img> without alt. |
| JSON-LD | One graph per page except the homepage (second FAQPage block) - fixed in 2.3.24. |
| Sitemap | /brands/fane/ and /brands/yamaha/ listed although they 301 - removed in 2.3.24. |
| Sample content | WordPress "Hello world!" post published at /hello-world/ - unpublished once in 2.3.24. |
| Meta descriptions | warranty ("Last updated..."), privacy-policy ("Effective date..."), cookie-policy ("Cookie policy.") - fixed in 2.3.24 (Rank Math box still wins if filled). Two price guides over 165 chars (keyboard-piano-price-rwanda 174, speaker-price-rwanda 166) - TODO, protected keywords, trim only with approval. |
| Thin pages (<250 words) | Brand pages: db-technologies, shure, midas, behringer, rockfon, barrisol, sommer-cable, bose-professional, aid, asona, allen-heath; request-a-quote; cookie-policy. -> R4 brand authority. |
| Claims to confirm | "Certified exclusive dealers for leading global brands" (homepage service card, /service/distribution-dealership/; parent theme + core seed, shared with Kenya). Brief: do not claim exclusivity unless proven. BLOCKED: needs Fred to confirm which brands are exclusive/authorised. |
| Contact consistency | Site-wide email is stefic@soundcreationsltd.com (+ fred@ in contact block); sign-in page help uses info@soundcreationsltd.com. BLOCKED: confirm the public email. |
| Nairobi / Dubai mentions | /about/ and /service/distribution-dealership/ (group context). Review wording so Kenya address is never presented as the Rwanda business. TODO |

## Live check after 2.3.24 upload (31 Oct 2026, 21:30 EAT)
| Check | Result |
|---|---|
| Version | 2.3.24 live (hero.mp4?ver=2.3.24) |
| Homepage JSON-LD | PASS: 1 block; graph has WebSite, WebPage, Organization, LocalBusiness, BreadcrumbList, ImageObject, FAQPage (#faq, 7 questions) |
| /hello-world/ | PASS: 404 |
| Warranty / cookie descriptions | PASS: new descriptions live |
| /brands/fane/, /brands/yamaha/ | Redirect PASS (301). Sitemap FAIL: still listed (Rank Math sitemap cache). Fix: 2.3.25 clears the cache once; or Rank Math > Sitemap Settings > Save Changes. Re-check after upload. |

## Starting baselines (pulled 31 Oct 2026 by Agnes's signed-in session; read only)
Search Console and GA4 use different windows; do not add them together.

### Search Console (property https://soundcreationsltd.rw/, Web, chart data 12-29 Oct 2026)
| Metric | Value |
|---|---|
| Clicks | 58 |
| Impressions | 1,201 |
| CTR | 4.8% |
| Average position | 6.5 |
| Indexed / not indexed | 81 / 70 |
| Breadcrumbs | 64 valid, 0 invalid |
| Core Web Vitals | no field data yet |
| Sitemap | sitemap_index.xml, submitted 14 Oct, last read 30 Oct, Success, 122 URLs |

Top queries (clicks / impressions): sound creations rwanda 2/41; piano price in rwanda 2/11; guitar price in rwanda 1/11; sound creations ltd 1/6; acoustic guitar near me 1/3; sound creations 1/2; in rwanda 1/2; audio 0/55; sound design 0/18; yamaha rwanda 0/17.

### Google generative AI features report (Beta; impressions only; 12-29 Oct 2026)
Total 91 impressions. Top pages: / 39; /yamaha/ 12; /projects/christian-life-assembly-church/ 10; /keyboard-piano-price-rwanda/ 5; /projects/jalia-events-venue/ 4; /products/yamaha-psr-e473/ 3; then 17 pages with 1-2 each (guides, price pages, solutions, Shure and Yamaha products, MINECOFIN). Countries: Rwanda 69, France 3, Uganda 3, others 1-2. Devices: mobile 53, desktop 38. Daily trend rising: 0 on 12 Oct to 18 on 29 Oct.

### GA4 (account 411970986, property 558970445, 3-30 Oct 2026)
| Metric | Value |
|---|---|
| Users / new users | 268 / 268 (21 returning) |
| Sessions / engaged | 311 / 142 (45.66%) |
| Avg engagement per user | 1m 15s |
| Events | 2,341 (7.53 per session) |
| Key events | **0** (conversion tracking not set up; not evidence of no leads) |

Channels (sessions, engagement rate): Direct 153 (22.9%); Organic Search 80 (77.5%); **AI Assistant 54 (59.3%, 17.4% of sessions; chatgpt.com 33 sessions in 24-30 Oct)**; Referral 19 (63.2%); Organic Social 3; Unassigned 2. Search Console is linked in GA4.

**Action:** set up GA4 key events (enquiry form submit, WhatsApp click, tel: click, mailto: click) so leads can be measured. TODO.

## Search Console page indexing (report dated 22 Oct 2026, read 31 Oct)
| Reason | Pages | Cause | Action |
|---|---:|---|---|
| Page with redirect (email 26 Oct) | 1 | /brands/fane/ 301 to /fane/ but was in the sitemap | Intentional redirect. Removed from sitemap (2.3.24/2.3.25). After upload: Validate fix. |
| Not found (404) | 6 | /wp-content/*, /wp-content/plugins/*, /wp-content/themes/soundcreations/*, /* = patterns from WordPress speculative-loading script, not pages; /sample-page/ = deleted WP sample page; /solutions/acoustics/ now 301s (fixed 2.3.19) | 2.3.26 turns the speculative-loading script off. Rest correct. Validate fix. |
| Alternative page with proper canonical | 15 | /request-a-quote/?product=... and a ?utm_ link | Correct behaviour, no action. |
| Blocked by robots.txt | 3 | /solutions/feed/, /wp-admin/*, /?s={search_term_string} | Correct, no action. |
| Excluded by noindex | 2 | /blog/acoustic-treatment-vs-soundproofing/ (now 301 to the guide), /feed/ | Correct, no action. |
| Blocked (403) | 1 | /wp-*.php pattern | Correct (security), no action. |
| Crawled - currently not indexed | 1 | /service/integration/ (346 words, overlaps /solutions/installation/) | Content: expand or merge. TODO (needs approval). |
| Discovered - currently not indexed | 41 | 7 brand pages, 32 products, /become-a-dealer/, /solutions/led-screens/ (first seen 20 Oct, never crawled yet) | Normal for a new domain. Thicken brand pages (R4), Request indexing for top products in batches (about 10/day), internal links from guides. |
| Indexed, though blocked by robots.txt | 1 | Company profile PDF | 2.3.26 allows that PDF in robots.txt. |

## Releases
| Release | Workstream | Status |
|---|---|---|
| R1 | External identity and migration: old-domain 301 (DONE), Yamaha directory phone (BLOCKED: send drafted email), GBP access (BLOCKED: Fred), directory listings RwandaYP / contact.page / Shakisha (TODO, Agnes) | IN PROGRESS |
| R2 | Technical + schema verification: crawl (DONE 31 Oct), homepage single graph (2.3.24, verified live), sitemap final URLs (2.3.25 pending upload), Rich Results / validator re-test after upload (TODO) | IN PROGRESS |
| R3 | Product catalogue completeness: 61 products; inventory of model, brand, category, specs, datasheet URL, image, applications (TODO) | TODO |
| R4 | Brand authority pages: expand 11 thin brand pages with verified relationship status (authorised distributor / partner / supplied) | TODO, needs relationship facts |
| R5 | Project case studies (target 15-20, approved facts only) | TODO, needs project facts/photos |
| R6 | Knowledge centre and buyer guides hubs | TODO |
| R7 | AI visibility tests (dated prompts + citations) and Search Console AI report baseline | IN PROGRESS: baselines recorded 31 Oct |
| R8 | Automated audit agent and reporting | TODO |

## Needs login or the business
- WordPress admin (wp-login.php): Rank Math fields, menu/content edits, cache purge.
- Google Search Console + GA4: baselines DONE 31 Oct; still to do: Validate fix, Request indexing, GA4 key events.
- Google Business Profile: waiting on Fred.
- Fred / sales: exclusive-dealer wording, brand relationship status, public email, project facts.
