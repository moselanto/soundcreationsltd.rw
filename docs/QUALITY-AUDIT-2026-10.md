# Quality audit, soundcreationsltd.rw (19 October 2026)

Prepared by Pimofy Digital. Standards used: Google Search Essentials and Lighthouse 13.5 (performance, accessibility, best practices, SEO), WCAG 2.2 level AA, OWASP secure headers (securityheaders.com), schema.org validation.

## Baseline before 2.3.6
| Check | Result |
|---|---|
| Security headers (securityheaders.com) | **A+** (HSTS, CSP, X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy) |
| Lighthouse mobile | Performance 81, Accessibility 96, Best Practices 96, SEO 100 |
| Lighthouse desktop | Performance 77, Accessibility 96, Best Practices 96, SEO 100 |
| Core metrics (mobile lab) | LCP 4.2 s, CLS 0, TBT 50 ms |
| Structured data | 0 errors, 0 warnings (validator.schema.org) |
| Redirects | http, www and the old rwanda.soundcreationsltd.com all end on https://soundcreationsltd.rw/ |
| Exposure | user list hidden, author enumeration 404, xmlrpc.php 403 |

## Fixed in code (2.3.6)
See CHANGELOG 2.3.6. Covers: contrast failure, console error from the failing hero video, Google tag blocking time, render-blocking/minified CSS, oversized images, duplicate brand pages, missing guides index, guide author/date, brand page product lists, empty project sections, wording and grammar, contradictory availability text, Kigali-only office listing.

## Owner / content actions (not code)
| # | Item | Who |
|---|---|---|
| 1 | Privacy policy is still the WordPress sample text ("Suggested text:"). Replace with a real policy (Rwanda Law No. 058/2021). | Sound Creations, Pimofy draft |
| 2 | Years of experience: "22+ years" vs "since 1989" (37 years). Choose one and update Settings and About. | Sound Creations |
| 3 | FANE brand card says "since 1954"; other pages say 1958. Edit the FANE brand entry. | Pimofy |
| 4 | Site title capitalisation "Sound creations Rwanda" (Settings > General). | Pimofy |
| 5 | Project data: Ntare Louisenlund and Atelier du Vin have identical equipment lists; confirm Ntare's equipment and district; Intare Conference Arena vs Intare Kivu Arena naming. | Sound Creations |
| 6 | Product data: AVer VC520 Pro is now VC520 Pro2; some spec lines lack labels (e.g. "61", "8 ohm"). | Pimofy with sales team |
| 7 | Server: block readme.html and license.txt; set long cache lifetimes for JS; remove stale advanced-cache.php or reactivate cache plugin. | Host |
| 8 | Two-step login for administrators; staff as Editors. | Sound Creations |
| 9 | Replace AI illustrations in guides with real project photos; add client testimonials. | Sound Creations |
| 10 | Optional hardening: HSTS preload, stricter CSP with script-src (test first, GTM and inline scripts). | Pimofy, later |

## Re-test after upload
Run PageSpeed Insights (mobile and desktop), securityheaders.com and validator.schema.org on the homepage, a product, a project and a guide, and record the scores here.

## Results after 2.3.6 (re-test 19 October 2026, 12:41 EAT)
| Check | Before | After |
|---|---|---|
| Lighthouse mobile | Perf 81 / A11y 96 / BP 96 / SEO 100 | **Perf 99 / A11y 100 / BP 100 / SEO 100** |
| Lighthouse desktop | Perf 77 / A11y 96 / BP 96 / SEO 100 | **Perf 99 / A11y 100 / BP 100 / SEO 100** |
| Mobile LCP / TBT / CLS | 4.2 s / 50 ms / 0 | **2.0 s / 0 ms / 0** |
| Security headers | A+ | **A+** |
| Schema (home, SM57) | 0 errors, 0 warnings | **0 errors, 0 warnings** |
| Duplicate brand pages | /brands/yamaha/, /brands/fane/ | 301 to /yamaha/, /fane/ |
| Guides hub | 404 | /guides/ 301 to Buyer Guides list |
| Privacy policy | WordPress sample text | Full policy under Law No. 058/2021, effective 19 Oct 2026 |
| Contact offices | Nairobi, Kigali, Kinshasa, Dubai | Kigali only |

Also fixed in WordPress on 19 Oct 2026: site title "Sound Creations Rwanda"; About "Sound Creations since 2004"; FANE "since 1958"; product renamed AVer VC520 Pro2.

Remaining (unscored or minor): CSP without script-src and no Trusted Types, HSTS without preload (optional hardening); JS files without cache lifetime (host); large photos in the WordPress media library (re-upload smaller versions); Brands page eyebrow setting "What we offer" and breadcrumb "Home > Products".

## Final results after 2.3.7 (re-test 19 October 2026, 12:56 EAT)
| Check | Mobile | Desktop |
|---|---|---|
| Lighthouse Performance / Accessibility / Best Practices / SEO | **99 / 100 / 100 / 100** | **100 / 100 / 100 / 100** |
| LCP / TBT / CLS | 2.0 s / 0 ms / 0 | 0.4 s / 0 ms / 0 |

- Security headers **A+**; HSTS `max-age=31536000; includeSubDomains; preload`.
- Schema (homepage): 0 errors, 0 warnings.
- "Use efficient cache lifetimes": now passed (server rules written).
- readme.html: 403. license.txt: still readable at re-test (likely cached copy; re-check after cache purge, otherwise host to add the rule).
- Remaining, unscored: CSP script-src and Trusted Types (planned separate release). Remaining performance hints (responsive srcset for theme images, render-blocking CSS, unused CSS) do not affect the scores above.
