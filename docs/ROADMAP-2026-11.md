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

## Releases
| Release | Workstream | Status |
|---|---|---|
| R1 | External identity and migration: old-domain 301 (DONE), Yamaha directory phone (BLOCKED: send drafted email), GBP access (BLOCKED: Fred), directory listings RwandaYP / contact.page / Shakisha (TODO, Agnes) | IN PROGRESS |
| R2 | Technical + schema verification: crawl (DONE 31 Oct), homepage single graph (2.3.24, verified live), sitemap final URLs (2.3.25 pending upload), Rich Results / validator re-test after upload (TODO) | IN PROGRESS |
| R3 | Product catalogue completeness: 61 products; inventory of model, brand, category, specs, datasheet URL, image, applications (TODO) | TODO |
| R4 | Brand authority pages: expand 11 thin brand pages with verified relationship status (authorised distributor / partner / supplied) | TODO, needs relationship facts |
| R5 | Project case studies (target 15-20, approved facts only) | TODO, needs project facts/photos |
| R6 | Knowledge centre and buyer guides hubs | TODO |
| R7 | AI visibility tests (dated prompts + citations) and Search Console AI report baseline | BLOCKED: Search Console login |
| R8 | Automated audit agent and reporting | TODO |

## Needs login or the business
- WordPress admin (wp-login.php): Rank Math fields, menu/content edits, cache purge.
- Google Search Console + GA4: baselines, AI performance report, indexing requests.
- Google Business Profile: waiting on Fred.
- Fred / sales: exclusive-dealer wording, brand relationship status, public email, project facts.
