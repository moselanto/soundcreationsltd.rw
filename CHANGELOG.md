# Changelog

Newest first. Every change must add an entry here (see AGENTS.md).

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
