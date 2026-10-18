# SECURITY-AUDIT.md

Security review of the soundcreationsltd.rw codebase (repository `moselanto/soundcreationsltd.rw`).
Reviewed: 18 October 2026, release 2.3.2 (child theme) / Sound Creations Core 0.5.27 / Sound Creations Enquiries 0.2.0.
Reviewer: Ray (Town assistant) acting as security engineer for Pimofy Digital.

## 1. Scope and method

| Item | Covered |
| --- | --- |
| Code | `soundcreations/` (parent theme), `soundcreations-rwanda/` (child theme), `sound-creations-core/` (plugin), `sound-creations-enquiries/` (plugin), `server/` snippets |
| Method | Manual source review of every request entry point (forms, admin-post, AJAX, REST, query vars, `$_SERVER`), output escaping, nonces and capabilities, file upload path, database access, redirects, headers, cookies and secrets. Pattern search across all PHP for superglobals, `$wpdb`, dangerous functions (`eval`, `exec`, `unserialize`, `base64_decode`, `extract`), unescaped output and hardcoded keys |
| Live checks | Headers, robots.txt, error pages and admin access observed on the live site during the 18 Oct 2026 checks |
| Not covered | Server/OS, PHP version and hosting panel of the .rw host (not accessible), WordPress core and third-party plugins (Elementor, Rank Math), penetration testing. No PHP runtime was available, so changes were kept minimal and reviewed by hand |

## 2. Summary

The codebase was already built defensively. No SQL injection, stored or reflected XSS, CSRF, privilege-escalation or unsafe-upload vulnerability was found in the custom code. One medium-low issue was fixed (Host-header influence on a redirect). Remaining items are operational (deployment, caching, updates) rather than code defects.

| Severity | Count | Status |
| --- | --- | --- |
| Critical | 0 | - |
| High | 0 | - |
| Medium | 0 | - |
| Low | 1 | Fixed in core 0.5.27 |
| Operational | 5 | Listed in section 6 for the site owner / host |

## 3. Findings fixed in this release

### S-01 (Low) Redirect built from the client-supplied Host header

- **Where:** `sound-creations-core/includes/post-types.php`, `sc_core_redirect_resources_to_videos()`.
- **Issue:** the 301 from `/resources/...` to `/videos/...` concatenated `$_SERVER['HTTP_HOST']` (unsanitised, attacker-controlled) into the redirect URL. `wp_safe_redirect()` limited exploitation to allowed hosts, so it was not an open redirect, but trusting the Host header is unsafe practice (cache poisoning, host-header attacks if the allowed-hosts list ever widens).
- **Fix:** the destination is now built from `home_url()` plus the parsed path and query string. The Host header is no longer read. Behaviour for real visitors is unchanged.

## 4. Requested controls: status and evidence

| Control | Status | Where / how |
| --- | --- | --- |
| Rate limiting | In place | Enquiries plugin: pre-emptive per-IP rate limit using a hashed IP (no raw IP stored), checked before any storage, mail or upload (`sound-creations-enquiries/includes/security.php`, `handler.php`). Forwarded-for headers are trusted only from declared proxies (`sc_enq_trusted_proxies`), so bots cannot rotate a header to bypass limits |
| Input sanitisation | In place | Every `$_POST`/`$_GET` read is unslashed and sanitised with the matching WordPress function (`sanitize_key`, `sanitize_text_field`, `sanitize_textarea_field`, `sanitize_email`, `absint`, `esc_url_raw`). Price field strips non-digits. Meta saves are whitelisted per field |
| Output escaping (XSS) | In place | Output uses `esc_html`, `esc_attr`, `esc_url`, `esc_js`, `wp_kses_post`. The few `phpcs:ignore` lines print trusted inline SVG or pre-escaped renderer output. JSON-LD is printed through `wp_json_encode` |
| CSRF | In place | Nonces on every state-changing action: enquiry form (`sc_enquiry_submit`, refreshed by an uncached AJAX token so cached pages still work), all meta boxes (`sc_core_fields_nonce`, `sc_seo_nonce`, `sc_faq_nonce`, `scrw_price_nonce`), brand reorder AJAX, setup wizard, starter setup and sample catalog (`check_admin_referer`) |
| Broken access control | In place | Admin pages require `manage_options`; brand reorder requires `edit_others_posts`; meta saves check `current_user_can( 'edit_post', $id )`; REST user, media and comment routes removed; author enumeration blocked; user registration forced off; application passwords disabled |
| SQL injection | In place | The only raw query (`seed-catalog.php`) uses `$wpdb->prepare()`. All other data access uses WordPress APIs (`get_posts`, `WP_Query`, `update_post_meta`) which parameterise queries. No NoSQL store is used |
| Insecure file upload | In place | Enquiry attachments: 5 MB cap, whitelist of document/image MIME types, `wp_check_filetype_and_ext` plus real MIME check, rejection of double extensions and PHP-like tails, random server-side filename, stored through `wp_handle_upload` in a dedicated folder, daily purge job |
| Password hashing | WordPress core | Passwords are hashed by WordPress core (bcrypt from WordPress 6.8; phpass on older cores). The custom code never stores or handles plaintext passwords. Policy added in `security-plus.php`: minimum 12 characters, not equal to username or email |
| Session expiry | In place | `security-plus.php` shortens auth cookies to 12 hours (7 days with "Remember me"); cookies are HttpOnly and Secure (WordPress core on HTTPS) |
| Session hijacking | In place | HTTPS-only, Secure + HttpOnly cookies, HSTS header, login errors that do not reveal whether a username exists |
| HTTPS everywhere | In place | `security-plus.php` 301s plain-HTTP front-end requests to HTTPS (proxy-aware), HSTS sent, CSP `upgrade-insecure-requests` |
| Security headers | In place | X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy, Content-Security-Policy (frame-ancestors, form-action 'self', upgrade-insecure-requests), Strict-Transport-Security; verified live on 30 Sep 2026 |
| CORS | In place | REST responses only echo `Access-Control-Allow-Origin` for this site's own origin (`security-plus.php`). No wildcard origins |
| Environment variables / no hardcoded keys | In place | No API keys, passwords or secrets in the repository. GA4 ID and Search Console code are public identifiers stored in WordPress options. SMTP or other credentials, if added, must go in `wp-config.php` constants or host environment variables, never in the repo |
| API vulnerabilities | In place | Only public endpoints are `admin-post.php?action=sc_enquiry` (nonce + token + rate limit + spam scoring) and `admin-ajax.php?action=sc_enq_token` (returns a nonce and signed token only, no data). REST user listing is disabled |
| Data exposure | In place | Enquiries are a private post type (not public, not in REST, not in sitemaps); IPs are stored only as hashes; error display is forced off in production; XML-RPC disabled; no .env/.git/debug.log exposed (live probe) |
| Regular dependency updates | Process | No Composer/npm dependencies in the repo. Dependencies are WordPress core, Elementor and Rank Math: update monthly after a backup (Elementor 4.3.5 update was pending on 18 Oct 2026). See section 6 |

## 5. Secure coding rules for future changes

1. Sanitise on input, escape on output, every time, with the WordPress function that matches the context.
2. Every state-changing request needs a nonce and a capability check.
3. Use WordPress APIs or `$wpdb->prepare()`; never concatenate user input into SQL.
4. Never trust `$_SERVER['HTTP_HOST']`, `X-Forwarded-For` or similar headers; use `home_url()` and the trusted-proxy filters.
5. No secrets in the repository. Use `wp-config.php` constants or environment variables.
6. New uploads must reuse the enquiries upload pipeline (whitelist, real MIME check, random name).
7. Record every security-relevant change in `CHANGELOG.md` and this file.

## 6. Operational actions (owner / host)

| # | Action | Why |
| --- | --- | --- |
| O-1 | Upload the full `soundcreations-rwanda/` and `sound-creations-core/` folders from `main` | The 2.3.0 schema tidy-up is not running on the server; partial uploads leave code and docs out of step |
| O-2 | Resolve the stale cache: WP_CACHE is enabled and the `advanced-cache.php` drop-in from the inactive Seraphinite Accelerator is still installed. Either reactivate Seraphinite and purge, or remove the drop-in and set WP_CACHE to false | Stale cached pages can serve outdated code and nonces |
| O-3 | Update WordPress core, Elementor and Rank Math monthly after a full backup; remove unused plugins and themes | Most WordPress compromises come from outdated plugins |
| O-4 | Enforce two-factor authentication for administrator accounts (host or a reputable 2FA plugin) and give staff Editor, not Administrator | Strongest protection against stolen passwords |
| O-5 | Keep `DISALLOW_FILE_EDIT` on (Theme File Editor already returns 403), keep daily off-site backups, and confirm PHP 8.1+ on the .rw host | Limits damage if an admin account is compromised |

## 7. Change log of this audit

- 18 Oct 2026: S-01 fixed (core 0.5.27). Document created.
