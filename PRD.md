# PRD.md: Product Requirements, soundcreationsltd.rw

Owner: Sound Creations Ltd Rwanda. Delivery partner: Pimofy Digital. Last updated: 18 October 2026.

## 1. Purpose

The website is Sound Creations Rwanda's main sales and credibility channel. It must make the Kigali company easy to find on Google and in AI assistants, prove its expertise with real projects, and turn visitors into qualified enquiries for professional audio, acoustics, lighting and AV systems.

## 2. Users

| User | Need |
| --- | --- |
| Churches, schools, hotels, government, corporates, event venues in Rwanda | Find a trustworthy local integrator, see proof, request a consultation or quote |
| Musicians, studios, DJs, rental companies | Find genuine Yamaha, Shure, dB Technologies, FANE products, ask for price and availability |
| Consultants, architects, procurement teams | Technical depth, brands, process, documentation |
| Sound Creations staff | Update content without a developer; receive enquiries reliably |

## 3. Goals and success measures

| Goal | Measure |
| --- | --- |
| Visibility | Search Console clicks/impressions for Rwanda service, brand and sector queries; Bing AI citations; monthly AI-answer checks |
| Credibility | Number of published case studies and buyer guides; consistent business details across listings |
| Conversion | Enquiries, WhatsApp and phone clicks (GA4) |
| Reliability | 0 schema errors, 0 PHP errors, pages indexed, forms delivering |

## 4. Functional requirements

1. Pages: Home, About, Contact, Yamaha, FANE, Solutions (12 incl. 4 sector pages), Services (4), Products (60), Brands (13), Projects, Videos, price guides, Request a Quote, Request a Consultation, policies.
2. Content types and fields as in `ARCHITECTURE.md` section 3, editable by staff.
3. Enquiry forms with routing per form type, anti-spam, rate limiting, optional attachment, private storage.
4. WhatsApp contact button and "ask for today's price" on products.
5. Central settings for business details, homepage and page text.
6. Bilingual readiness (French demand observed) only when real French content is available.

## 5. Non-functional requirements

| Area | Requirement |
| --- | --- |
| SEO | Rank Math owns head; one JSON-LD graph; Rwanda-first wording; no doorway pages |
| Data integrity | No invented prices, ratings, reviews or results; no prices displayed (owner decision) |
| Security | Controls in `SECURITY-AUDIT.md` section 4 |
| Performance | WebP images, preloaded hero, deferred JS, caching configured correctly |
| Accessibility | WCAG 2.1 AA intent: one H1, alt text, keyboard navigation, readable contrast |
| Maintainability | One concern per file, idempotent seeders, documented in AGENTS.md and CHANGELOG.md |

## 6. Out of scope

Online checkout and payments, displayed prices, user accounts for customers, country landing pages without real local capability.

## 7. Roadmap

1. Publish buyer guides (engineer review) and five more case studies.
2. Google Business Profile transfer and alignment; Yamaha and FANE listing updates.
3. Monthly AI and Google visibility check.
4. Optional French page for DRC/francophone demand once content exists.
5. Design pass on parent-theme CTA components.
