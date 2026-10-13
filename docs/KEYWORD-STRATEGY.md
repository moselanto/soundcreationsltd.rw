# Rwanda keyword strategy: Sound Creations Ltd Rwanda

Researched 13 October 2026. Sources: Google autocomplete for Rwanda (`gl=rw`, 50 seed searches), live Google-style web results for the main buying searches, and a crawl of soundcreationsltd.rw.

Autocomplete tells us **what people in Rwanda actually type**, but it does not give search volumes. Confirm volumes and positions in Google Search Console (Performance report) about 4 to 6 weeks after deployment.

## 1. What Rwandan buyers actually search

### The big pattern: "price in Rwanda"

The modifier that comes up most is **"price in rwanda"**. Buyers want a number before they call.

| Seed | What Google suggests in Rwanda |
| --- | --- |
| keyboard price in rwanda | piano price in rwanda, piano keyboard price in rwanda, **yamaha piano price in rwanda**, midi keyboard price in rwanda |
| speaker price in rwanda | jbl speaker price, bluetooth speaker price, marshall speaker price in rwanda |
| microphone price in rwanda | **wireless microphone price in rwanda**, studio microphone price, podcast microphone price, rode microphone price |
| mixer rwanda | **mixer price in rwanda** |
| guitar price in rwanda | acoustic guitar price, **yamaha guitar price in rwanda**, bass guitar price in rwanda |
| shure rwanda | **shure sm7b price rwanda** |
| projector rwanda | projector price in rwanda |
| headphones rwanda | headphones price in rwanda |

### Brand and shop searches

- **sound creations rwanda / sound creations kigali / sound creations ltd**: people already search for the company by name. Protect this with a consistent name, address and phone everywhere.
- **yamaha rwanda / yamaha kigali**: autocomplete mixes in *Yamaha motorcycles* ("yamaha rwanda motorcycles", "yamaha motor rwanda", "yamaha rwanda contact number"). The Yamaha page must say clearly that this is Yamaha **music and pro audio**, and the phone number must be easy to find.
- **music shop kigali**, **electronics shop kigali**: local "near me" shop searches. These are won on Google Maps (Business Profile), not by a page.
- **bose rwanda**, **jbl rwanda** (consumer Bluetooth speakers, mostly competitors' stock).

### Searches with little or no measurable demand

No autocomplete at all for: *audio visual rwanda, av company rwanda, acoustic panels rwanda, sound proofing rwanda, stage lighting rwanda, line array rwanda, dB Technologies rwanda, dj equipment rwanda, studio equipment rwanda, led screen rwanda, digital piano rwanda*.

That does not mean these are worthless. They are **low-volume, high-value B2B searches** (one church or ministry contract is worth hundreds of keyboard sales). The site already covers them with solution and sector pages, so keep them, but most of the traffic will come from product, price and brand searches.

## 2. Who ranks today, and why

| Search | Who ranks | Why they rank |
| --- | --- | --- |
| Yamaha keyboard / piano price Rwanda | Roots Rwanda, Safe Home Rwanda, Ubuy Rwanda, Zimcompass (classifieds) | Model-level product pages with an **RWF price** in the title and on the page |
| Speakers price Rwanda | Roots Rwanda, EasyBuy, Home Point, Kigali Discount, Gizmocean | Consumer Bluetooth/home speakers with prices and "delivered in Kigali" |
| Shure Rwanda, dB Technologies Rwanda | **the old site rwanda.soundcreationsltd.com** (/shure/, /db-technologies/) | Years of age and links. The 301s in `server/htaccess-old-domain-redirect.txt` hand this value to the new brand pages |
| Pro audio shop Rwanda | soundrwanda.store | Says it stocks Yamaha, dB Technologies, Shure and Behringer: the closest direct competitor |
| Sound Creations Kigali | Kisimenti and other directory listings, Yamaha's distributor page, IGIHE article | Third-party listings and press |

What Sound Creations has that competitors do not: the **official Yamaha distributorship** (listed on mea.yamaha.com), official dB Technologies, Shure, Bose Professional and FANE relationships, real installation projects (MINECOFIN, Embassy of Sweden, Intare Kivu Arena, Christian Life Assembly), and press coverage (IGIHE, Feb 2026). The strategy is to turn that authority into pages that also answer the **price** question.

## 3. Keyword to page map

| Keyword cluster | Page | Status |
| --- | --- | --- |
| sound creations rwanda / kigali | `/` | Ranking target, title already correct |
| yamaha rwanda, yamaha kigali, yamaha distributor | `/yamaha/` | Exists. Add "music and pro audio" wording and an FAQ (see actions) |
| keyboard price in rwanda, yamaha piano price in rwanda | `/products/yamaha-psr-e473/`, `psr-sx600/700/720/920`, `yamaha-p-145`, `yamaha-ydp-105` | **1.7.0**: titles now "... Price in Rwanda", visible price box, FAQ. Add RWF prices to win |
| guitar price in rwanda, yamaha guitar price | `/products/yamaha-c40/`, `/products/yamaha-f310/` | Same as above |
| mixer price in rwanda | Yamaha MG, MGP, TF, DM3 product pages | Same as above |
| microphone price in rwanda, wireless microphone price, shure sm7b price rwanda | Shure SM58, SM57, SM7B, BLX, PGA product pages | Same as above |
| speakers in rwanda, speaker price in rwanda, PA system | dB Technologies OPERA, B-Hype, Yamaha DBR12/CBR10, DSPPA | Same. Pro PA, not Bluetooth: compete on "professional speakers" and "sound system" |
| shure rwanda | `/brands/shure/` | Exists. Gains old-site value once 301s are live |
| db technologies rwanda | `/brands/db-technologies/` | Exists. Gains old-site value once 301s are live |
| bose rwanda | `/brands/bose-professional/` | Exists |
| sound system rwanda / kigali | `/solutions/professional-audio/` + `/solutions/installation/` | Exist |
| church sound system | `/solutions/church-sound-systems/` | Created by 1.6.0 on next wp-admin load |
| conference / boardroom | `/solutions/conference-room-sound-systems/` | Created by 1.6.0 |
| acoustic treatment / sound proofing | `/solutions/architectural-acoustics/` | Exists |
| stage / event lighting | `/solutions/lighting-solutions/` | Exists |
| projector / LED screen rwanda | none | Only build when the Rwanda team confirms they sell and install it |

## 4. What release 1.7.0 changes in code

- **Product titles** follow the way people search: `Yamaha PSR-E473 Price in Rwanda | Buy in Kigali`. Meta descriptions start with "... price in Rwanda: get today's price in RWF". A title typed by hand in Rank Math still takes priority.
- **Price box** at the top of every product: shows the RWF price when one is entered, otherwise "Ask for today's price in RWF", with a one-tap WhatsApp message ("what is today's price in Rwanda for the ...?").
- **New product fields** (sidebar of the product editor): *Price (RWF, VAT included)* and *In stock in Kigali*.
- **Offer schema** (price, RWF, new condition, seller) is sent to Google **only when a real price is entered**. No price means no offer: nothing is invented.
- **Buyer FAQ** on every product without its own FAQ: "How much is the X in Rwanda?", "Is it genuine, with warranty?", "Where can I buy it in Kigali?", with matching FAQPage schema.
- **LocalBusiness** type now `LocalBusiness + ElectronicsStore + MusicStore`, so the showroom matches "music shop Kigali" as well as electronics searches.

## 5. Actions only the business can take (highest impact first)

1. **Enter RWF prices** on the keyboard, piano, guitar, mixer, microphone and speaker products. This is the single biggest win: every competitor that ranks shows a price, and Google only shows price results when there is one.
2. **Google Business Profile** (Maps): category "Audio visual equipment supplier", extra categories "Music store" and "Electronics store"; name *Sound Creations Ltd Rwanda*; website `https://soundcreationsltd.rw/` (it currently points to the old subdomain); weekly photos and posts; reply to every review.
3. **Reviews**: ask every buyer and installation client for a Google review (send the review link on WhatsApp after the sale).
4. **Fix the Yamaha distributor listing**: mea.yamaha.com shows +250 734 016 816 and +250 782 739 889; the site uses +250 783 141 050. Ask Yamaha to list the same main number and link to soundcreationsltd.rw. Do the same with dB Technologies, Shure and Bose Professional "where to buy" pages.
5. **Directories**: update Kisimenti, shops1000 and similar listings to the new website and the same phone number.
6. **301 the old site** (`server/htaccess-old-domain-redirect.txt`): it still owns the Shure and dB Technologies rankings.
7. **Search Console**: submit `/sitemap_index.xml`, then review the Performance report monthly and add FAQs or copy for searches that show impressions but few clicks.
8. **Content**: short buying guides on the searches above, e.g. "Best Yamaha keyboard for church in Rwanda", "Wireless microphone price guide Rwanda", "Choosing a mixer for a church in Kigali". Link each guide to the matching products.
