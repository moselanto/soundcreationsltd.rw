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
