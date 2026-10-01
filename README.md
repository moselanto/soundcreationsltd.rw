# Sound Creations Ltd Rwanda - Website

Source for <https://rwanda.soundcreationsltd.com/>.

Independent website for **Sound Creations Ltd Rwanda** at <https://rwanda.soundcreationsltd.com/>,
built to the scope in *Sound Creations Rwanda - Website and SEO Proposal* (30 Sep 2026).

## Architecture decision

The Rwanda office asked for an **independent** site, not a section of the Kenya site. It is
a separate WordPress install on the Rwanda domain and hosting, with its own database, admin,
content, enquiries and analytics. It runs a **copy of the group code** from soundcreationsltd.com
(github.com/moselanto/soundcreationsltd.com) so the two read as one brand (proposal section A).
This repository is the Rwanda site's own source; fixes made to the Kenya parent theme or plugins
must be ported here deliberately, and vice versa:

```
soundcreations/              Parent theme   (copied from the Kenya repo)
sound-creations-core/        Plugin         (copied from the Kenya repo)
sound-creations-enquiries/   Plugin         (copied from the Kenya repo)
soundcreations-rwanda/       Child theme    (Rwanda only)  <- activate this on the Rwanda site
```

The Kenya child theme (`soundcreations-child/`) is deliberately not included here.

## What the Rwanda child theme does

| File | Purpose |
| --- | --- |
| `inc/settings-seed.php` | Writes Rwanda phone, email, address, hours, WhatsApp, map link, footer and homepage/About copy into Settings. Only fills fields that are empty or still hold the Kenya default, so editor changes are never overwritten. Routes all enquiry and quote forms to sales@soundcreationsltd.com. |
| `inc/content-seed.php` | Creates the four Rwanda service pages with full copy and calls to action (Lighting Solutions, Studio Solutions, Architectural Acoustics, Service and Backup); drafts the overlapping generic starter solutions; replaces the Kenya-law Privacy and Terms pages with Rwanda versions; stops the Core plugin seeding Kenya case studies. |
| `inc/seo.php` | Retargets page titles from Kenya to Kigali/Rwanda, corrects LocalBusiness/Organization schema to a Kigali RW address, links the entity to the group site (`parentOrganization`), adds Kigali geo meta. |
| `inc/analytics.php` | GA4 and Search Console verification fields under **Settings -> General**. |
| `functions.php` | Loads the above, adds a footer band linking to the group site. |

Already provided by the shared theme and active once Settings are filled: header with phone,
email and hours; WhatsApp floating button; mobile click-to-call / WhatsApp / consultation bar;
enquiry, quote and consultation forms with anti-spam; brands archive; projects archive;
WordPress XML sitemap at `/wp-sitemap.xml`.

## Deployment (Rwanda hosting)

1. Back up the current Rwanda site (files and database).
2. Install a clean WordPress (6.4+, PHP 8.0+, HTTPS) on rwanda.soundcreationsltd.com.
3. Upload `soundcreations/` and `soundcreations-rwanda/` to `wp-content/themes/`.
4. Upload `sound-creations-core/` and `sound-creations-enquiries/` to `wp-content/plugins/`.
5. Activate **Sound Creations Core**, then **Sound Creations Enquiries**.
6. Activate the **Sound Creations Rwanda** theme. Settings, service pages and legal pages seed automatically; reload wp-admin once.
7. **Settings -> Permalinks** -> Save.
8. **Settings -> General**: Site Title `Sound Creations Ltd Rwanda`, timezone Kigali, then GA4 ID and Search Console code.
9. Upload the site icon (Appearance -> Customize -> Site Identity); schema uses it as the logo.

## Still needed from the Rwanda team (proposal section 4)

- Photographs and short descriptions of completed Rwanda projects -> add as **Projects** (set location e.g. `Kigali, Rwanda`).
- Key client names/logos cleared for publication.
- Any Rwanda-specific brand/partner logos -> **Brands**.
- Confirm Saturday hours (seeded as Closed) and whether +250 782 739 889 should also appear in the header.
- Rwanda social media links if separate from the group accounts (otherwise the group links show).
- Kigali office latitude/longitude for map schema (left blank rather than guessed).

## SEO checklist

Target terms: audio visual Rwanda, sound systems Kigali, PA system Kigali, acoustic treatment Rwanda,
studio equipment Rwanda, stage lighting Kigali, church sound system Rwanda, conference room AV Kigali.

- [x] Location-targeted titles, service copy and schema (this theme)
- [x] Internal links to the group site
- [ ] Google Business Profile for KN1 Rd, Muhima - same name, address and phone as the site
- [ ] Search Console: verify, submit `/wp-sitemap.xml`
- [ ] GA4 property and baseline report
- [ ] Add a link from soundcreationsltd.com (Kigali branch / contact page) to the Rwanda site
- [ ] Compress and upload project photos (WebP, under 200 KB each, descriptive alt text)
- [ ] Legal review of the Rwanda Privacy and Terms pages
