# Sound Creations Rwanda - SEO, AEO, GEO & AI Search Master Audit (31 Oct 2026)

Owner-supplied brief ("RWANDA SEO AEO GEO AND SCHEMAS.docx"), converted to text so every agent can read it. It supersedes nothing in AGENTS.md; it sets the programme. Progress is tracked in `docs/ROADMAP-2026-11.md`.

```text
Sound Creations Rwanda — SEO, AEO, GEO & AI Search Master Audit
Strong foundation
Important gaps remain
AI-agent implementation plan
Website: soundcreationsltd.rw
Source code: moselanto/soundcreationsltd.rw
Audit date: 31 October 2026
Prepared for: Sound Creations Ltd Rwanda
Implementation: Pimofy Digital
My assessment is that we have built a considerably more sophisticated SEO foundation than a typical company website. We should preserve that investment and now move from SEO implementation to a comprehensive search visibility, business-entity authority, product discovery and AI recommendation strategy.
The objective is not simply to rank for a few keywords.
We want Sound Creations to become one of the most recognisable and consistently recommended professional audio, audiovisual, acoustics and lighting businesses in Rwanda, with credible visibility across East Africa and international markets.
There is one important distinction: no agency or AI agent can guarantee first position or recommendations from ChatGPT, Gemini, Google AI Overviews or other AI platforms. What we can do is systematically improve the evidence, relevance, crawlability, authority and content that make those recommendations more likely.
1. Executive assessment
Current implementation assessment
Advanced
The project has moved beyond basic SEO. It has a custom WordPress SEO architecture, Rwanda-specific content, product information, business entity connections and an established technical handover process.
Strong
Technical SEO foundation
Strong
Local content targeting
Advanced
Custom schema architecture
Needs work
External authority & citations
Needs work
Product discovery at scale
Unverified
Actual AI recommendation share
Qualitative implementation assessment, not a measured Google ranking score or independent PageSpeed retest.
The repository documents an October 19 release with excellent Lighthouse results: homepage mobile 99, Products 99, projects 98, and SEO 100 on the tested pages. It also records 0 schema errors and 0 warnings in its previous validation. These are documented historical results, not a fresh October 31 test.
Our live search checks also show the site being returned for important searches including Yamaha distributor Rwanda, audio visual company Rwanda, professional audio equipment Rwanda, church sound systems Rwanda and acoustic solutions.
That is positive progress.
The main challenge now is not a lack of keywords. It is building a much stronger, independently verifiable web presence around the business, its products, its brands, its projects and its expertise.
2. What we have already achieved
I reviewed the repository's AGENTS.md, README.md, ARCHITECTURE.md, SEO audit, keyword strategy, project handover and the relevant SEO and schema modules.
We have implemented the following:
The most important implementation files include:
sound-creations-core/includes/seo-graph.php
soundcreations-rwanda/inc/schema-rw.php
soundcreations-rwanda/inc/schema-services.php
soundcreations-rwanda/inc/schema-tidy.php
soundcreations-rwanda/inc/seo-boost.php
soundcreations-rwanda/inc/seo-content.php
soundcreations-rwanda/inc/seo-ai.php
We also have a critical architectural decision that should remain intact: Rank Math owns the page's metadata, while our custom plugin builds one connected schema graph.
This prevents two SEO systems from publishing contradictory structured data.
I would not replace this architecture. I would improve and extend it.
3. The most important gaps we need to close
I identified several findings in the existing repository and compared them with what can currently be observed on the public website.
These are the areas that should determine our next development phase.
Gap 1: External authority and business identity
Critical priority
Google and AI search systems need to understand that the website, physical business, manufacturer relationships, directory listings and external references all describe the same organisation.
We have already established the Kigali business identity in the schema. However, the project's own October 19 handover identifies incomplete external work.
Particularly important: the official Yamaha listing.
The repository records an incorrect main telephone number of +250 734 016 816 in a Yamaha directory, compared with the website's main number, +250 783 141 050.
The handover also says the correction emails were prepared but not sent.
The business should correct this directly with Yamaha.
This is especially important because the Yamaha relationship is one of our strongest differentiators.
Gap 2: The old domain is a major entity-consolidation risk
Critical priority
The old address:
rwanda.soundcreationsltd.com
The new address:
https://soundcreationsltd.rw/
The project handover records that the old website was still live and indexed on October 19.
This must be investigated again and resolved.
The intended implementation is already prepared in:
server/htaccess-old-domain-redirect.txt
We need to ensure every old URL redirects to its equivalent new URL rather than redirecting every page indiscriminately to the homepage.
For example:
OLD:
rwanda.soundcreationsltd.com/yamaha-mixing-console/

NEW:
soundcreationsltd.rw/products/yamaha-dm3-standard/
The actual mapping must be checked against the existing content and URL history.
We should then check:
Old-domain homepage redirects.
Old product pages redirect.
Old brand pages redirect.
Old project pages redirect.
Old sitemap is no longer competing.
Google Search Console recognises the migration.
External backlinks are retained through equivalent destination URLs.
This work should be treated as a prerequisite for expanding the content strategy.
Gap 3: Product discovery needs to become much more powerful
Very high priority
The current catalogue is a good start, but we need to think differently about product discovery.
People do not simply search for professional audio equipment.
They search for specific products, model numbers, brands, applications and purchasing problems.
For example:
Yamaha DBR12 price in Rwanda.
Yamaha TF5 digital mixing console Kigali.
Yamaha PSR-SX920 keyboard Rwanda.
Shure SM58 microphone Rwanda.
Shure wireless microphone systems Kigali.
dB Technologies OPERA 15 Rwanda.
FANE loudspeaker drivers Rwanda.
Bose Professional ceiling speakers Rwanda.
Allen & Heath SQ7 mixer Rwanda.
Acoustic panels and soundproofing Kigali.
Conference microphone systems Rwanda.
Our catalogue should be able to serve all those search intents.
The website already has a product database, taxonomies, manufacturer specifications and approximately 60 products in the documented content model.
The next stage should include:
A. Complete product coverage
We need an audited inventory of:
Every active product.
Every model number.
Every brand.
Every product category.
Every manufacturer specification.
Every official manufacturer URL.
Every available image.
Every relevant application.
Every product's enquiry destination.
B. Product-specific SEO content
Each individual product needs:
C. Product comparison and recommendation pages
These can attract valuable searches that individual product descriptions cannot capture.
Examples:
Yamaha DBR12 vs Yamaha CBR12: which should you choose?
Which Yamaha mixer is best for a church in Rwanda?
Best professional speakers for a conference hall.
How to choose a wireless microphone system.
Active vs passive PA speakers.
How many speakers does a church need?
Choosing subwoofers for a live event venue.
These pages must be written from real product specifications and genuine technical knowledge.
Gap 4: Our brands need individual authority ecosystems
High priority
We have already developed a represented-brands page, but that should become the beginning of a much deeper strategy.
Current brands visible on the website include:
Yamaha Professional Audio Expands Global R&D Team | AVNetwork
Yamaha
Fane Logo PNG Vectors Free Download
FANE
Smarthouse and office LLC
dB Technologies
Shure Incorporated - AES
Shure
Bose Proプレスルーム - 新しく独立した企業として、 Bose Professional プロAV市場への取り組みと注力を強化 | Bose Professional
Bose Professional
Allen & Heath | Paxt Ltd
Allen & Heath
MIDAS – Hipercentro Electrónico
Midas
Uli Behringer: “Technology is free for anyone to use unless it is protected” | MusicRadar
Behringer
Event Production Services and Equipment Hire - Sound of Music Ltd
NEXO
Examples of major brands already represented in the group's product and brand ecosystem. Images are illustrative, not evidence of a specific partnership or distributorship.
We should develop a brand authority structure with the following hierarchy:
/brands/
    /yamaha/
    /fane/
    /db-technologies/
    /shure/
    /bose-professional/
    /allen-heath/
    /midas/
    /behringer/
    /nexo/
    /biamp/
    /chamsys/
    /asona/
    /rockfon/
    /barrisol/
Some URLs already exist in a different format, so we should retain existing canonical URLs and improve their contents instead of creating duplicates.
Each brand page should explain:
What the manufacturer makes.
Relevant product categories.
Products supplied by Sound Creations.
Actual distribution or partnership status.
Manufacturer-authorised support, where applicable.
Relevant installations and projects.
Technical resources and official datasheets.
Related products and applications.
How to request a quotation in Rwanda.
A critical accuracy rule: being an authorised Yamaha distributor or FANE Africa partner does not mean that every brand on the website has appointed Sound Creations as its exclusive distributor in Rwanda. We must distinguish authorised distributors, regional partners and brands that we supply.
FANE's August 31, 2026 announcement is particularly valuable: it explicitly names Sound Creations as a strategic East African partner, including Rwanda, and identifies the wider regional opportunity. 
fane-international.com+1
Gap 5: Our project portfolio needs to become a genuine authority source
High priority
We already have something that many competitors cannot easily replicate: actual installations and client relationships.
For example, the live website features:
Christian Life Assembly.
Ministry of Finance (MINECOFIN).
Intare Kivu Arena.
Ntare Louisenlund School.
Atelier du Vin.
Jalia Events Venue.
Romantic Garden.
These projects are useful for more than showing beautiful installations.
They demonstrate that Sound Creations has solved real technical problems.
Consider the Christian Life Assembly project. Its existing content identifies the dB Technologies K5 line array, KS20 subwoofers, FM15 monitors, Allen & Heath SQ7 mixer and Chauvet lighting. 
Church PA Kigali+1
We should expand the project portfolio into detailed engineering case studies.
A standard case study should contain:
We should aim to develop at least 15–20 substantial, approved Rwanda case studies initially, and then expand the portfolio as more evidence becomes available.
Do not invent acoustic improvement figures, certifications, project results or client testimonials.
Gap 6: Buyer guides and technical resources
High priority
The earlier handover confirms four buyer guides were published:
Sound System Quotation Checklist Rwanda.
Choosing a PA System for a Church Hall in Rwanda.
Acoustic Treatment vs Soundproofing.
Maintaining a Commercial Sound System.
That was an excellent move.
But we now need a much more systematic editorial programme.
Our resources should answer questions before a potential customer even contacts the sales team.
The new content should be grouped into knowledge hubs rather than published as disconnected blog posts.
For instance:
/knowledge-centre/
    /professional-audio/
    /church-sound/
    /conference-av/
    /acoustics/
    /stage-lighting/
    /musical-instruments/
    /studio-recording/
    /product-buying-guides/
We should not create duplicate category pages simply because a keyword exists. Each topic must have a clear purpose and a useful internal linking path.
4. Our biggest strategic opportunity: become the answer, not just a search result
This is where I would take the project further.
When someone asks:
Who installs professional sound systems in Rwanda?
Where can I buy genuine Yamaha speakers in Kigali?
Which company supplies Shure microphones in Rwanda?
Who installs church sound systems in Rwanda?
Which company provides acoustic treatment in Kigali?
Where can I find FANE loudspeaker drivers in East Africa?
Who designs professional conference room AV systems?
Which supplier can design a hotel sound system in Kigali?
We want Sound Creations to be a credible answer.
That means we need to optimise three connected areas.
SEO
Search Engine Optimisation
Help search engines find, crawl, understand, index and rank the website.
AEO
Answer Engine Optimisation
Make the content directly answer the questions customers ask, with clear, useful and verifiable answers.
GEO
Generative Engine Optimisation
Build trusted business entities, original expertise, manufacturer references, evidence and authoritative content that AI systems can retrieve and cite.
There is an important current development here.
Google's published guidance now explicitly says that established SEO fundamentals remain the foundation for AI Overviews and AI Mode. It also says that special AI markup and llms.txt are not requirements for Google AI visibility. 
developers.google.com+1
Therefore, our next investment should be in better content, real-world evidence, authoritative references, product data, crawlability and measurement—not in generating more artificial schema or AI-specific files.
Bing also explicitly connects its webmaster guidelines with Copilot grounding and citations. 
Bing Webmaster Tools
5. Schema.org: the next level of our structured-data architecture
We already have a custom schema engine. I would retain it and introduce a more rigorous entity and data-quality model.
The objective is not to produce the largest JSON-LD document possible. It is to produce the most accurate representation of the business and every important entity on the site.
5.1 Recommended schema architecture
Sound Creations Rwanda — Entity graph
Organization
Sound Creations Ltd Rwanda · #organization
LocalBusiness
Kigali showroom · #localbusiness
WebSite
Website identity and search
Brand entities
Yamaha, FANE, Shure, etc.
Product entities
Individual models and specifications
Service entities
Professional AV, integration, acoustics
CreativeWork
Projects, articles and case studies
WebPage · BreadcrumbList · ImageObject · ItemList · FAQPage
Relevant page-level relationships and supporting entities
5.2 Schema types we should support
5.3 Important schema improvements
A. Strengthen Organization and LocalBusiness
Review:
@id consistency.
Registered business name.
Alternate business names.
Logo and image references.
Address and contact information.
Business telephone numbers.
Correct opening hours.
Geographic coordinates, once verified.
Real social media profiles.
Manufacturer relationships.
Parent organisation relationships.
Authentic service areas.
knowsAbout expertise.
The group relationship should distinguish the Kigali operation from the Nairobi headquarters.
It should never accidentally make the Kenyan physical address the address of the Rwanda business.
B. Better Product schema
Our current policy is that prices are not displayed because prices vary by stock and shipment.
The repository correctly avoids inventing prices, availability, ratings or offers.
This is important.
Google's Product documentation has different requirements for product snippets and merchant listings. Merchant listings in particular need genuine purchase and pricing information. 
developers.google.com+2
My recommendation:
Keep truthful Product entities on individual enquiry-based product pages.
Include accurate name, image, description, SKU and genuine manufacturer.
Add manufacturer specification and product identifiers where verified.
Link each product to its canonical brand and category.
Do not fabricate an Offer or price.
Revisit Offer implementation only when the business decides to publish actual prices.
C. Manufacturer relationship graph
Where supported by evidence, establish relationships between:
Sound Creations Rwanda
       |
       | distributes / supplies
       |
     Yamaha
       |
       | manufactures
       |
   Yamaha DBR12
We should use genuine manufacturers and exact products, with canonical identifiers.
The graph must not state that we are the manufacturer, own the brand or have exclusive distribution rights unless that is demonstrably true.
D. Project and case-study schema
Every project should link to:
The organisation providing the service.
The project page.
The actual location.
Relevant brands.
Relevant products.
The industry or application.
Original project photographs.
Authored editorial content.
This connects the project portfolio to the product and solutions ecosystem.
E. Author and technical expertise
We need credible authorship.
Where a guide represents engineering recommendations, its author should be an actual engineer or qualified technical contributor who reviews and approves it.
The author profile should include genuine experience, relevant role and verifiable background.
We should not make up author credentials.
5.4 One important correction about FAQ schema
We have implemented FAQPage in several places.
That can be retained where it genuinely describes the visible questions and answers.
However, we should not assume FAQ markup will result in Google FAQ rich results. Google substantially restricts these appearances to well-known government and health websites. 
developers.google.com+1
The value of our FAQs is now primarily the actual answers, clarity and usefulness to people and answer systems.
6. Rwanda keyword and product coverage strategy
We should now move beyond general keywords towards a complete search-intent coverage programme.
I recommend organising our search opportunity around six main markets.
Waterproof Line Array Speakers | Outdoor IP65/IP67 PA System Guide 2026
1. Professional audio and PA systems
PA systems, speakers, amplifiers, line arrays, subwoofers, mixers and microphones.
Primary market
Projetos de Som Profissional para Cada Tipo de Ambiente. | Guarani Musical
2. Church and worship technology
Church sound systems, microphones, mixers, acoustic treatment, lighting and streaming.
Primary market
Gallery | ANORA Integrated Systems | Home Automation & AV Technology Solutions
3. Corporate and conference AV
Boardrooms, microphones, video conferencing, meeting rooms and auditoriums.
Acoustic Panels for Lecture Theatre Walls 2026
4. Architectural acoustics
Acoustic panels, ceiling systems, noise isolation, soundproofing and reverberation control.
Company | Music & Lights
5. Stage and event lighting
Moving heads, DMX, stage illumination and event technology.
Студия звукозаписи
6. Musical instruments and recording
Yamaha keyboards, digital pianos, guitars, studio monitors, recording and podcast equipment.
Geographic keyword expansion
The Rwanda strategy should cover real service destinations rather than producing hundreds of thin location pages.
We should also maintain a separate regional expansion programme:
East Africa: Kenya, Uganda, Tanzania, Rwanda, Burundi, Ethiopia and South Sudan.
DRC: Actual projects, distribution and delivery areas.
International: Manufacturer relationships, technical knowledge, specialist products and broader industry recognition.
We must not represent Rwanda-specific offers or local stock as available in every country.
7. Competitor and market intelligence
An initial public search indicates that competing businesses are already addressing professional audio, AV integration and installation demand in Rwanda.
Some relevant market references include:
Himax — identified in the existing repository's competitor research.
Green Means — also referenced in the previous audit.
Sound Creations — the parent group's regional business.
I would not claim we have established market share, competitor rankings or search-volume superiority from the searches conducted.
The next stage needs a proper competitive intelligence database.
For each competitor, our agent should record:
Our competitive advantage should be built around verifiable manufacturer relationships, original installation work, genuine technical expertise and the breadth of products and brands.
8. The AI SEO agent we should build
This is the biggest part of the next phase.
I recommend developing a dedicated Sound Creations Search Intelligence & Optimisation Agent.
It should not simply generate articles.
It should be able to inspect the website, audit the code, research search opportunities, identify content gaps, propose improvements, generate implementation changes, validate them and report results.
SCL Search Intelligence Agent
SEO · AEO · GEO · Schema · Products · Authority
Proposed architecture
Central AI Orchestrator
Task planning, prioritisation, approvals and reporting
SEO Intelligence
Keywords, rankings, indexing
AEO Intelligence
Questions and answers
GEO Intelligence
AI citations and entities
Schema Engineer
JSON-LD and validation
Product Intelligence
Catalogue and models
Authority Intelligence
Brands, projects, references
Technical Auditor
Links, performance, crawlability
Analytics
Progress and attribution
Human-reviewed implementation
GitHub branch → automated tests → pull request → approval → deployment
8.1 Agent 1 — SEO Intelligence
Responsibilities:
Crawl all indexable URLs.
Extract page titles, descriptions and canonical URLs.
Check robots directives.
Inspect sitemap coverage.
Detect missing or duplicate content.
Identify broken internal and external links.
Find orphan pages.
Identify keyword cannibalisation.
Compare rankings over time.
Recommend new page opportunities.
Track search intent by page.
It should know our protected keywords from docs/KEYWORD-STRATEGY.md.
Critical: It must never remove, weaken or retitle a page's existing successful keyword without human approval.
8.2 Agent 2 — AEO Intelligence
Responsibilities:
Research common buyer questions.
Identify unanswered questions on current pages.
Improve direct-answer sections.
Generate FAQ suggestions.
Create technical explanations.
Identify missing comparison content.
Check whether visible content actually supports the answers.
Example:
Customer asks:
"Which sound system is best for a church in Rwanda?"
The agent should determine:
Which type of church?
What size is the auditorium?
What is the expected audience?
Is the main requirement speech, worship music or both?
What equipment is appropriate?
Which models does Sound Creations actually supply?
Which completed project demonstrates similar work?
What should the customer do next?
This is considerably more valuable than simply repeating the phrase "church sound system Rwanda" 20 times.
8.3 Agent 3 — GEO and AI visibility
This agent should monitor and test the brand's visibility across:
Google AI Overviews and AI Mode.
ChatGPT Search.
Perplexity.
Microsoft Copilot.
Gemini.
It should use real, recorded searches and available platform sources.
Its tasks include:
Querying relevant product and service questions.
Recording whether Sound Creations is recommended.
Recording which sources are cited.
Identifying competitor recommendations.
Checking which Sound Creations URLs are cited.
Identifying incorrect information about the business.
Finding missing external references.
Creating an AI visibility report.
An important distinction: AI responses can change according to user location, wording, freshness and other contextual signals. We must report the actual tests, dates, prompts and citations rather than claim a universal AI ranking.
8.4 Agent 4 — Schema Engineer
This agent should:
Inspect JSON-LD output.
Validate schema types and properties.
Detect broken @id relationships.
Detect duplicated entities.
Compare structured data to visible page content.
Check Product, Brand, Service and LocalBusiness information.
Confirm that genuine prices are used before introducing Offer.
Validate the same business identity across the website.
Detect contradictory contact details and locations.
It should generate schema changes only through our existing sc_seo_graph filter architecture.
No second JSON-LD graph.
No accidental Rank Math schema duplication.
No invented product offers.
8.5 Agent 5 — Product Intelligence
This should become one of our most important modules.
It should examine the product catalogue and build an inventory of missing information.
Example report:
The agent should match manufacturer information to the actual catalogue rather than automatically importing every product from the internet.
It must not silently create products we cannot source.
8.6 Agent 6 — Authority and Reputation Intelligence
Responsibilities:
Identify verified manufacturer distributor listings.
Monitor company directory consistency.
Identify relevant industry publications.
Locate genuine supplier announcements.
Find suitable association and industry directories.
Monitor external links to the website.
Track approved testimonials.
Identify projects that need better supporting evidence.
It must never fabricate backlinks, reviews, brand relationships or testimonials.
8.7 Agent 7 — Technical SEO and Quality Assurance
This should run after every proposed code change.
Checks:
PHP syntax.
WordPress security and coding quality.
Sitemap and robots rules.
Canonicals and redirects.
Mobile rendering.
Page titles and metadata.
JSON-LD validation.
Broken images.
Internal links.
Accessibility.
Performance.
JavaScript console errors.
The system should refuse to ship when a critical test fails.
9. Recommended technology architecture
We already use WordPress, PHP and GitHub. I would avoid migrating the website to another platform.
Instead, build an independent intelligence and orchestration layer that communicates with WordPress and GitHub through controlled APIs.
Given the existing development environment, I recommend the following:
Suggested architecture
                  SCL WEBSITE
                soundcreationsltd.rw
                       |
              WordPress REST API
                       |
             SEARCH INTELLIGENCE
                       |
              AI ORCHESTRATOR
                       |
       +---------------+----------------+
       |               |                |
    SEO Agent       AEO Agent        GEO Agent
       |               |                |
       +---------------+----------------+
                       |
                 QA & VALIDATION
                       |
           GITHUB FEATURE BRANCH
                       |
                PULL REQUEST
                       |
             HUMAN APPROVAL
                       |
                DEPLOYMENT
                       |
             SEARCH MONITORING
                       |
                 REPORTING
Suggested database structure
The agent should not operate without retaining its observations and decisions.
I recommend these core tables:
We will also need to connect product model data to the existing WordPress data, so we have one authoritative catalogue rather than two independently maintained ones.
10. How the AI agent should actually work
The biggest risk in autonomous SEO systems is allowing an AI to modify production websites without proper controls.
I recommend four operating levels.
Level 1 — Discover
Automatic
The agent crawls the site, checks keywords, identifies gaps, records errors and prepares reports.
Level 2 — Recommend
Automatic
It prepares improved titles, descriptions, schema changes, content briefs and technical recommendations.
Level 3 — Develop
Controlled
It generates code and content on a separate GitHub branch, runs tests and creates a pull request.
Level 4 — Publish
Human approval
A human approves the changes, checks backups and deploys to the live WordPress website.
Suggested workflows
We can automate safe and reversible improvements after testing. Changes involving titles, URL structures, business identity, distribution claims and existing rankings should always require approval.
11. Where the agent gets its data
This is critical to producing useful recommendations.
A new opportunity as of October 31, 2026: Google's Search Console now has a dedicated generative AI performance report covering AI Overviews and AI Mode. Google announced that these insights were rolled out worldwide on August 31, 2026. This gives us an actual measurement source instead of relying solely on manual AI searches. 
developers.google.com+1
We should add this to the reporting system immediately.
12. The new master AI coding-agent instruction
We should give the coding agent a permanent project brief rather than continually creating new prompts.
The original prompt was intended to guide SEO, AEO and GEO implementation. The repository still recognises the master brief called SCL RWANDA SEO, AEO, GEO.
I recommend upgrading that operating brief into the following master instruction.
The agent should first read AGENTS.md, the October handover, the keyword strategy, SEO audit, architecture and changelog. It should not start writing code before completing that assessment.
Master AI agent prompt
Ready to use
Use this as the persistent instruction for Codex or another capable GitHub-connected coding agent. Copy master agent instructions
13. Specific fixes and repository actions we should create
I would organise implementation into the following releases.
The first release should address the old domain, Yamaha contact details, Google Business Profile access and external listing consistency.
The second should validate the actual deployed schema and resolve any difference between GitHub and the live WordPress installation.
The third should establish product completeness.
One particularly important operational detail: the repository documents that GitHub commits do not automatically deploy to the live hosting account. We must preserve that separation and only deploy approved, tested changes.
14. A realistic 90-day implementation programme
Proposed programme starting November 1, 2026
Days 1–14: Consolidation and verification
Complete old-domain 301 migration.
Correct Yamaha's external contact listing.
Confirm Google Business Profile access.
Recheck Search Console and sitemap.
Audit the live schema graph.
Establish ranking, traffic and enquiry baselines.
Audit all existing products and brands.
Foundation phase
Days 15–30: Product and entity authority
Strengthen product descriptions and specifications.
Improve brand pages and manufacturer links.
Review product schema.
Develop product comparison content.
Connect products to applicable projects and solutions.
Strengthen business entity consistency.
Discovery phase
Days 31–60: AEO, GEO and content expansion
Expand buyer guides.
Build technical knowledge hubs.
Publish approved detailed project case studies.
Improve internal linking.
Run documented AI recommendation tests.
Strengthen industry and manufacturer citations.
Authority phase
Days 61–90: Automation and measurement
Deploy the agent's controlled audit workflows.
Introduce weekly reporting.
Track Google AI performance.
Track qualified enquiries.
Compare competitor visibility.
Fix remaining technical and content gaps.
Prepare the next quarterly growth roadmap.
Intelligence phase
15. How we will measure whether we are actually winning
We need a dashboard that reports business outcomes rather than vanity SEO scores.
We should baseline these figures before setting numerical targets.
For example, I would rather see a 30% increase in qualified enquiries than a 100% increase in irrelevant website visits.
16. What I would do immediately
There are five actions I would prioritise before adding any new SEO features.
November 1 priority checklist
0 of 8 completed
0%
Inspect the currently deployed WordPress versions against GitHub.
Complete and verify the old Rwanda domain redirects.
Correct Yamaha's official Rwanda distributor contact listing.
Confirm Google Business Profile access and maintain its established reviews.
Export Search Console, GA4 and the new Google AI performance baselines.
Audit all product, brand, service and project URLs.
Validate the live schema graph and sitemap.
Create the AI agent's first feature branch and baseline report.
17. Important limitations in this audit
I inspected the GitHub project documentation and relevant implementation files, searched the live website and retrieved public pages. I also reviewed current Google AI search and structured-data guidance.
However, I have not independently logged into the website's WordPress administration, Google Search Console, GA4, Google Business Profile or hosting account. I have not run a full browser crawl, current Lighthouse tests or live rendered JSON-LD validation on every page.
Consequently, I have deliberately separated what the repository documents from what I could observe publicly.
I also have not yet committed code changes to GitHub. Changing the existing SEO architecture before completing the full live-versus-repository verification would introduce unnecessary risk.
18. My final recommendation
We should position this project as a much bigger initiative than a normal SEO maintenance exercise.
Sound Creations Rwanda should become a verified digital knowledge and product authority for professional audio, AV integration, acoustics, lighting and musical instruments.
The opportunity is to combine:
A properly structured website.
A comprehensive and accurate manufacturer/product catalogue.
Genuine installation evidence.
Strong local and regional business identity.
Expert-led technical content.
Trustworthy manufacturer and industry citations.
Continuous AI search monitoring.
A controlled AI engineering and optimisation agent.
That is how we build an enduring competitive advantage rather than chase temporary SEO tricks.
One additional strategic recommendation: once the Rwanda framework is established, we should make its underlying intelligence system reusable for the Kenya website, soundcreationsltd.com, while maintaining separate branch facts, telephone numbers, business profiles, products and local search strategies.
That could become a valuable proprietary search intelligence platform developed and maintained by Pimofy Digital for the entire Sound Creations group.
The immediate next deliverable should be the agent's first full implementation audit and a controlled GitHub pull request containing the verified fixes, starting with the migration and entity consistency work.

Area | What has been built | Assessment
Technical SEO | Rank Math integration, canonical metadata, redirects, sitemap management | Strong
Local SEO | Rwanda-specific titles, descriptions, business identity and location | Strong
AEO | Answer-first content, buyer FAQs, sector pages | Strong foundation
GEO | Entity relationships, expertise content, manufacturer references | Good foundation
Schema | Connected JSON-LD graph, business, products, brands, services and projects | Advanced
Product SEO | Individual catalogue entries, model information and specifications | Good foundation
Brand SEO | Yamaha, FANE, Shure, Bose Professional and other brand pages | Good foundation
Project SEO | Real project pages, equipment lists, locations and outcomes | Good foundation
Performance | Image optimisation, preload, local fonts, CSS and JavaScript optimisation | Strong, historically tested
Analytics | GA4 and Search Console integrations | Partially operational
AI accessibility | Optional llms.txt and crawler-policy controls | Available, not a priority
Task | Existing position | Required action
Google Business Profile | 4.8 stars, 15 reviews documented | Continue obtaining access and optimise
Yamaha distributor directory | Phone mismatch documented | Correct the official directory
Old Rwanda domain | Still documented as indexed | Complete permanent redirects
Main Kenya website | Group relationship exists | Strengthen reciprocal Rwanda link
Rwanda directories | Several outstanding | Create consistent business listings
Manufacturer citations | Some established | Expand verified manufacturer references
Client references | Many names published | Obtain permission and develop evidence
Field | Requirement
Product name | Exact official model
Manufacturer | Consistent brand entity
Model/SKU | Exact identifier
SEO title | Product + model + Rwanda
Meta description | Useful purchasing proposition
Description | Original, useful product explanation
Specifications | Accurate manufacturer data
Image | Optimised image with accurate alt text
Applications | Churches, studios, events, venues, etc.
Datasheet | Manufacturer specification source
Related products | Relevant complementary products
Enquiry CTA | Direct quotation or WhatsApp request
Section | Information
Client | Approved organisation name
Location | Actual Rwanda location
Sector | Church, government, education, hospitality, etc.
Problem | The original technical challenge
Design | Engineering and system design
Equipment | Exact manufacturer and model
Installation | Actual work carried out
Measurements | Verified acoustic or performance figures
Result | Documented improvement
Evidence | Actual installation photographs
Related content | Products, brands and solutions
Topic | Search opportunity
Best sound system for churches in Rwanda | Church purchasing
Yamaha keyboard prices in Rwanda | Instrument purchasing
Professional microphone selection | Product purchasing
Conference room AV installation | Corporate purchasing
Best PA speakers for event companies | Rental and event market
Acoustic treatment for auditoriums | Construction and architecture
Soundproofing a recording studio | Studio construction
LED screens and video walls | AV procurement
Digital mixer selection | Technical purchasing
Professional sound system maintenance | After-sales service
Schema | Where it applies | Purpose
Organization | Main business identity | Establish company entity
LocalBusiness | Kigali office | Establish local presence
WebSite | Website | Website identity and site search
WebPage | Individual pages | Page relationships
BreadcrumbList | Navigational pages | Page hierarchy
Product | Individual product pages | Product identification
Brand | Brand pages | Manufacturer identity
Service | Solutions and services | Service definitions
Offer | Only genuine priced offers | Commercial offer information
OfferCatalog | Business/service catalogue | Service organisation
ItemList | Product, project and brand listings | Collection structure
CreativeWork | Project case studies | Work and evidence
Article / BlogPosting | Buyer guides | Editorial ownership
Person | Verified authors | Expertise and authorship
ImageObject | Important images | Image relationships
VideoObject | Videos with accessible metadata | Video discovery
FAQPage | Appropriate FAQ pages | Question and answer structure
Geography | Examples of search targeting
Kigali | Sound system suppliers, Yamaha, AV installation, acoustics
Rubavu / Gisenyi | Conference venue audio, sound installation
Musanze | Church sound, hospitality AV
Huye | School and auditorium sound
Rusizi | Professional sound solutions
Nyagatare | School, church and institutional AV
Rwamagana | Conference and commercial AV
Rwanda-wide | Distributors, installation, maintenance and products
Metric | What we compare
Visibility | Ranking queries and landing pages
Products | Product models and brand coverage
Content | Articles, buying guides and technical material
Authority | Verified backlinks and citations
Local SEO | Business listings and reviews
Reputation | Real customer feedback
AI presence | Actual answer and citation observations
User experience | Mobile usability, page speed and conversion paths
Product | Brand | Gap
Yamaha DBR12 | Yamaha | Add missing related applications
Yamaha DM3 Standard | Yamaha | Verify current manufacturer specification
Shure SM58 | Shure | Improve comparison and buying guidance
FANE CD-140 | FANE | Verify product identifiers and datasheet
dB Technologies OPERA | dB Technologies | Expand model-specific comparisons
Component | Recommended technology | Purpose
AI orchestration | Python or Node.js | Manage agent tasks
AI models | OpenAI API | Reasoning, content and analysis
Workflow automation | n8n | Scheduled workflows and integrations
Database | PostgreSQL | Store audits and historical metrics
WordPress access | WordPress REST API | Read content and controlled changes
Search metrics | Google Search Console API | Queries, clicks and impressions
Analytics | GA4 Data API | Traffic and conversions
Bing | Bing Webmaster API and IndexNow | Indexing and search monitoring
Search research | Approved search APIs | Competitor and topic research
Code management | GitHub API | Create branches and pull requests
Browser testing | Playwright | Test rendered website pages
Schema validation | Schema.org validator and Rich Results Test | Structured-data quality
Reporting | Web dashboard | Performance and action tracking
Table | Contents
seo_audits | Overall website audit history
seo_pages | URL-level SEO information
seo_keywords | Keyword targets and historical position
search_queries | Search Console query information
ai_visibility_tests | AI prompt tests, answers and references
schema_audits | Structured-data findings
products | Catalogue inventory
brands | Manufacturer relationships
content_opportunities | Missing pages and content opportunities
backlink_monitor | External references and links
agent_tasks | Task state, owner and approval
change_log | Every proposed and deployed change
conversion_metrics | Leads, enquiries and conversion outcomes
Frequency | Workflow
Daily | Indexing, uptime, crawl and critical error monitoring
Weekly | Search queries, keyword opportunities and AI visibility samples
Weekly | Product, link and content gap reviews
Monthly | Competitor analysis and authority assessment
Monthly | Search and enquiry performance report
After every release | Schema, SEO, security and performance validation
Source | Information | Priority
Google Search Console | Search queries, pages, indexing and AI performance | Essential
GA4 | Traffic and conversions | Essential
Google Business Profile | Local performance, reviews and profile health | Essential
Bing Webmaster | Crawl and indexing | High
Manufacturer websites | Product facts and datasheets | Essential
Website catalogue | Product and brand inventory | Essential
GitHub | Source code and implementation history | Essential
Competitor websites | Public content and product coverage | High
Backlink data provider | External authority and referring domains | High
AI search testing | Recommendations and citations | High
Release | Workstream | Priority
R1 | External business identity and migration | Critical
R2 | Full technical and schema verification | Critical
R3 | Product catalogue completeness | Very high
R4 | Brand authority architecture | Very high
R5 | Project and case-study expansion | High
R6 | Buyer guide and AEO knowledge centre | High
R7 | AI visibility monitoring | High
R8 | Automated optimisation and reporting | High
KPI | What we want to improve
Indexed quality pages | Relevant pages successfully indexed
Organic impressions | More relevant discovery
Organic clicks | More qualified website visitors
Top 3 keyword coverage | Commercial search competitiveness
Product model visibility | Searches for exact products
Brand search coverage | Manufacturer and distributor searches
Local visibility | Maps and Kigali search discovery
AI visibility | Brand mentions and cited pages
External authority | Genuine relevant referring domains
Enquiry conversions | Phone, WhatsApp and consultation requests
Qualified leads | Actual business opportunities
Revenue attribution | Enquiries turning into business
```
