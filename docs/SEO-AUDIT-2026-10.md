# Post-implementation SEO / AEO / GEO audit: soundcreationsltd.rw

Date: 18 October 2026. Release audited: 2.0.0 (repo) against the live site. Prepared against the brief "SCL RWANDA SEO, AEO, GEO" (master prompt).

Labels used: **[Observed]** seen on the live web today, **[Repo]** read in this repository, **[Inference]** reasoned, not measured, **[Blocked]** needs access we do not have (Search Console, GA4, PageSpeed, raw HTML/JSON-LD, AI platform tests).

## 1. What is working

| Item | Evidence |
| --- | --- |
| Location-targeted titles on key pages | [Observed] Home "Sound Creations Rwanda \| Yamaha Distributor & Pro Audio Kigali"; Yamaha "Yamaha Music Rwanda \| Official Keyboard, Piano & Guitar Distributor"; sector pages load with keyword titles |
| Sector pages (1.3.0) live | [Observed] `/solutions/church-sound-systems/` and `/solutions/conference-room-sound-systems/` load, open with a direct answer, cite a real project (Christian Life Assembly, MINECOFIN) and carry 5 visible FAQs |
| Answer-first copy | [Observed] Sector pages and Yamaha page already answer "who / what / where" in the first paragraph. AEO structure is largely in place on these pages |
| Yamaha vs motorcycles disambiguation | [Observed] Yamaha page says "Pro audio and musical instruments" up front |
| Project evidence | [Observed] Case studies with Challenge / Solution / Technology (named models) / Result, e.g. Christian Life Assembly (dB Technologies K5, KS20, FM15, Allen & Heath SQ7, Chauvet), Ntare Louisenlund (Opera 15/10, Sub 615, Yamaha DXS18, MG16XU) |
| Search visibility | [Observed] soundcreationsltd.rw pages are returned for "audio visual company Rwanda sound system installation Kigali", "Yamaha distributor Rwanda" and acoustic/church/conference searches in our web-search sample (not a Google rank measurement) |
| robots.txt hygiene | [Observed] Core rules live; sitemap line points to `/sitemap_index.xml` |
| One JSON-LD graph, Rank Math owns head | [Repo] unchanged; [Blocked] rendered JSON-LD not re-validated today (fetch tool returns text only) |

## 2. Gaps and risks, ranked

| # | Finding | Type | Impact | Effort | Risk | Status |
| --- | --- | --- | --- | --- | --- | --- |
| 1 | **Phone mismatch on the official Yamaha distributor directory** (mea.yamaha.com and asia-oceania.yamaha.com): lists +250 734 016 816 and +250 782 739 889; site main line is +250 783 141 050. Yamaha is the strongest external authority signal the business has | Entity / GEO | High | Low (email to Yamaha) | None | **Action for business** |
| 2 | **Old site rwanda.soundcreationsltd.com still live and indexed** (home and product pages such as /yamaha-mixing-console/ appear in search), with phone +250 782 739 889 and sales@ email. Two websites compete for the same entity | Technical / entity | High | Low (paste `server/htaccess-old-domain-redirect.txt`) | Low | **Action for server admin** (file ready since 1.6.0) |
| 3 | Homepage "What we do" said we deliver "across Rwanda, Africa and the Middle East", contradicting schema areaServed (Rwanda, DRC, East Africa) | Content / entity | Medium | Low | Low | **Fixed in 2.1.0** (`inc/wording-rw.php`) |
| 4 | 2.0.0 robots.txt groups for named AI bots would have made those bots ignore the core hygiene rules (RFC 9309: a bot obeys only its most specific group) | Technical | Medium | Low | - | **Fixed in 2.1.0** (never deployed) |
| 5 | 2.0.0 added `/llms.txt` and `speakable` without an authoritative use case (brief section 9.4; speakable is a Google beta for US-English news only) | Schema / GEO hygiene | Low | Low | - | **Fixed in 2.1.0**: llms.txt off by default, speakable removed |
| 6 | Only 7 written case studies, while the projects page lists ~70 named clients (churches, MINECOFIN, Embassy of Sweden, Goethe-Institut, Serena Hotel...) with no detail | AEO / GEO evidence | High | Medium (needs the team + client permission) | Low | Content plan, section 6 |
| 7 | No buyer-guide / resource content. Competitors (Himax, Green Means) publish process pages; nobody in Rwanda publishes technical guides | AEO / topical authority | High | Medium | Low | Content plan, section 6 |
| 8 | Group site soundcreationsltd.com home says "Kenya and Africa"; whether it links to soundcreationsltd.rw not confirmed | Entity | Medium | Low | Low | **Action for group web team** |
| 9 | Google Business Profile, Search Console, GA4 still pending (1.6.0 checklist) | Local / measurement | High | Low | None | **Action for business** |
| 10 | Sitemap index not verifiable by our fetch tool (XML) | Technical | - | - | - | [Blocked] confirm in Search Console > Sitemaps |
| 11 | Competitor Green Means Ltd lists "30 KN 1 Road, Muhima" (same street) and sells sound installation | Local SEO | Medium | - | - | [Inference] GBP pin, photos and categories matter more to avoid map confusion |

## 3. Competitor matrix (observed 18 Oct 2026, public pages and search results only)

No traffic, ranking position, backlink or revenue figures are given because none were measured.

| Company | URL | Model | Overlap with Sound Creations | Observed strengths | Observed weaknesses / opening |
| --- | --- | --- | --- | --- | --- |
| Himax Services | himaxservices.com | AV integration, video walls (CHIC Building, Kigali) | Direct: conference/corporate AV | Clear process page (discover, assess, design, install, train); keyword titles "Audio Visual Solutions Rwanda" | Video/display focus; no audio or acoustic engineering depth seen |
| Alpha Computer Ltd | alphacomputer.rw | AV integrator; sole Peoplelink distributor, reseller for Absen, Televic, Sennheiser, Fohhn, LG | Direct: conferencing, government/corporate | Named brand partnerships, since 2004 | IT-led; little acoustics, worship or venue content |
| Green Means Ltd | greenmeans.rw | Sound and audio supply and installation, KN 1 Road, Muhima | Direct: small/medium installs | Clean service page with "What's included" | Generic copy, no named brands or projects seen |
| TECO Ltd | teco.rw | Engineering contractor with AV installation | Indirect: tenders, institutional | Infrastructure credibility | AV is one line of many; generic text |
| Glad Ltd | glad-ltd.com | Systems design and integration | Possible direct (verify Rwanda presence) | Prime contractor positioning | Not verified for Rwanda |
| AVD Apex Ltd | avdapex.rw | AV and event management agency (founded 2020 by a former Sound Creations engineer, KCT Plaza) | Indirect: event AV, conference support, LED screens | Event + AV bundle | 1-10 staff; events rather than permanent installs |
| Big Sounds, Activ8, GoodAV, Planet Events, Deals on Events, Alpha Entertainment | various | Event rental and production | Indirect (several are Sound Creations customers: "Alfa Entertainment" is on the clients list) | Event visibility | Not permanent integration; treat as partners, not targets |
| Yamaha distributor directory | mea.yamaha.com | Manufacturer listing | Reference source | Highest-trust citation for "Yamaha Rwanda" | Wrong main phone (gap 1) |

**Where Sound Creations can win:** the only Rwandan site combining (a) official Yamaha distribution, (b) named engineering case studies with model-level equipment lists, (c) acoustics as an engineering discipline (RT60, speech intelligibility) and (d) worship, government, hospitality and education references. Competitors are either display/IT-led or event-led.

## 4. Question-to-page map (AEO)

Demand evidence is [Unverified] until Search Console data exists; questions come from the brief and observed competitor/SERP language.

| Question cluster | Stage | Best existing URL | Action | Conversion |
| --- | --- | --- | --- | --- |
| Who supplies Yamaha pro audio in Rwanda? | Procurement | `/yamaha/` | Good. Add a short FAQ block (warranty, spare parts, which models in stock) | Call / WhatsApp |
| Who installs sound systems in churches in Rwanda? | Procurement | `/solutions/church-sound-systems/` | Good. Link more of the 14 named churches once permission is given | Site consultation |
| What equipment does a conference room need? / hybrid meetings | Specification | `/solutions/conference-room-sound-systems/` | Good. Add a simple "room size to system" table using real configurations | Quote |
| Acoustic treatment vs soundproofing? Why does our hall echo? | Early research | `/solutions/architectural-acoustics/` | Add a direct-answer paragraph comparing the two, and the measure-design-treat-verify process with a real example (Ntare Louisenlund) | Acoustic survey |
| How do I choose a PA for a large hall / auditorium? | Specification | `/solutions/school-and-auditorium-sound/`, `/solutions/professional-audio/` | New guide (section 6) | Consultation |
| What should a sound installation quotation include? | Procurement | none | New guide: procurement checklist for schools, ministries, churches | Request a quote |
| How should a sound system be maintained / serviced? | After-sales | `/solutions/service-and-backup/` | Add maintenance schedule answer | Service contract |
| Equipment vs complete AV system (commissioning)? | Procurement | `/solutions/installation/` | Add direct answer near top | Consultation |
| X price in Rwanda (mixer, keyboard, wireless mic) | Purchase | product pages (1.7.0 price box) | Enter real RWF prices on best sellers | WhatsApp |

## 5. AI citation baseline (GEO)

**Not yet run.** Town did not test ChatGPT, Perplexity, Copilot or Google AI Overviews in this session, so there is no baseline to report. Use this log monthly; record only what you see.

| Date | Platform | Exact question | Sound Creations cited (link) / mentioned / absent | Cited URL | Other sources named | Follow-up |
| --- | --- | --- | --- | --- | --- | --- |
| | | Which companies provide professional AV integration in Rwanda? | | | | |
| | | Who supplies Yamaha professional audio equipment in Rwanda? | | | | |
| | | Which companies install sound systems in churches and auditoriums in Rwanda? | | | | |
| | | Where can businesses get conference-room AV in Kigali? | | | | |
| | | Which providers do acoustic treatment in Rwanda? | | | | |
| | | Where can I buy professional audio equipment in Kigali? | | | | |

AI crawler access (2.1.0): search and user-triggered crawlers (Googlebot, Bingbot, OAI-SearchBot, ChatGPT-User, PerplexityBot, Claude-SearchBot) are allowed by the `User-agent: *` group. Training crawlers are allowed by default; set `scrw_ai_training_policy` to `block` if the business prefers. [Blocked] CDN/firewall (LiteSpeed/hosting) bot rules not inspected.

## 6. Content plan (needs the Rwanda team, real evidence only)

1. **Fix entity facts first**: Yamaha listing phone, old-domain 301s, Google Business Profile (same name, KN1 Rd Muhima, +250 783 141 050), group site link to .rw.
2. **Five more case studies** from the clients list (suggest: Embassy of Sweden, Goethe-Institut, Serena Hotel, Intare Conference Arena, one Zion Temple), each with permission, model-level equipment, challenge and result. No invented decibel or RT60 figures.
3. **Four buyer guides** (Article schema already supported by core for posts): "What a sound system quotation should include (Rwanda procurement checklist)", "Acoustic treatment vs soundproofing", "Choosing a PA system for a church or hall in Rwanda", "Maintaining a commercial sound system". Written or reviewed by the Kigali engineers.
4. **External references, small and real**: Yamaha (fix phone), dB Technologies / Shure / FANE dealer locators if they list Rwanda partners, Rwanda Development Board company registry, LinkedIn company page for the Rwanda entity, client partner pages (with permission).

## 7. International / regional

[Inference] Rwanda stays primary. DRC is the only secondary market with stated group activity (since 2022) and it is French-speaking. Do **not** add country pages or hreflang until there are real DRC projects, delivery and support terms to publish; when there are, one French DRC page with real content is the right first step. No hreflang between .rw and .com: they are different entities, not language variants.

## 8. Still blocked (needs access)

Search Console, GA4, PageSpeed/CrUX, rendered JSON-LD validation (Rich Results Test), sitemap XML check, CDN/firewall rules, AI platform tests, keyword volumes. Connecting GitHub lets Town push releases; the rest needs the site owner's Google accounts.


## 9. Addendum: wider search and competitor research (18 Oct 2026, same day)

Observed in one search engine's results (not Google positions). Titles only; meta and schema of competitor sites not visible to the fetch tool.

**Search picture**
- soundcreationsltd.rw appeared in 9 of 11 commercial searches and came first for: sound system installation Rwanda, acoustic treatment Rwanda, stage lighting Kigali, Yamaha distributor Rwanda, PA system Kigali.
- Missing for **"audio visual company Rwanda"** (event producers GoodAV, Big Sounds, Alpha Entertainment lead) and **"conference room AV Rwanda"** (Himax, Techcare, IBMS, GoodAV, KCC). The conference page already exists, so this is a page-strength issue: add "conference room AV / video conferencing Rwanda" wording to its title and intro, link to it from the homepage and the MINECOFIN project, and add the hybrid-meeting gear actually supplied.
- **Old subdomain still ranks**: rwanda.soundcreationsltd.com came first for "professional audio supplier Rwanda" and "Shure microphones Rwanda", and appears for church and Yamaha searches. This raises the priority of the 301 redirect file to number 1.

**Manufacturer listings**
- Yamaha (mea + asia-oceania): +250 734 016 816 and +250 782 739 889, no website field. Ask for +250 783 141 050 and soundcreationsltd.rw.
- Shure "Shure in Rwanda" (shure.com/en-IN/about-us/contact-us/rwanda) lists Sound Creations with the **Nairobi** address and soundcreationsltd.com. Shure announced Hub Media as an authorised African distributor (8 Jul 2026); confirm Sound Creations' Rwanda status with Shure before relying on "authorised Shure" wording.
- FANE dealer locator shows only Credible Sounds (Nairobi) for Africa, although FANE's 31 Aug 2026 news names Sound Creations as East Africa partner. Request a listing.
- dB Technologies network lists Rwanda; listed company not confirmed.

**Additional competitors**
- SoundRwanda.store: sells Yamaha, dB Technologies, Shure, Behringer online with RWF prices (e.g. dB Technologies B-Hype 10 at 850,000 RWF), 1-hour Kigali delivery, no address or authorisation claim. Direct for product/price searches.
- Insulation Rwanda (insulationrwanda.rw): acoustic panels (claims AMF/Armstrong). Direct for acoustic-material searches.
- GLAD Ltd: Bosch DCN conference installs at ministries; site's latest news 2016.
- GoodAV: event AV and video; strong proof content (named events, testimonials, FAQs). Indirect but wins generic "AV company" searches.
- Others: IBMS Africa, Himax, Techcare, ELV.rw, LED Concept, Big Sounds, Activ8, EMS Rwanda. Avientek claims d&b audiotechnik distribution for Rwanda (unverified). GALA AVL installed DAS Audio at Amahoro Stadium.
- Correction to section 3: Deals on Events is an event production company (not a directory) and an indirect competitor / possible customer.

**Directories (free, use identical name, KN1 Rd Muhima, +250 783 141 050, soundcreationsltd.rw)**: RwandaYP, rwanda.contact.page, Shakisha. Earned media: IGIHE article on Sound Creations (26 Feb 2026), ask for a link to the .rw site. Group site hosts a Rwandan project (/projects/rpf-rubavu-hall/) that ranks for acoustic treatment Rwanda; mirror it on the .rw site with a canonical decision agreed with the group web team.
