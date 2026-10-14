/* Sound Creations Rwanda UI (1.9.0): mobile menu accordion, dock sync, WhatsApp chat. */
(function () {
	'use strict';
	var doc = document, body = doc.body;
	doc.documentElement.classList.add('js-scrw-ui');

	var mq = window.matchMedia('(max-width: 782px)');

	/* ---------- Mobile menu: accordion sub-menus ---------- */
	function initAccordion() {
		var items = doc.querySelectorAll('.sc-nav .sc-nav__list > li.menu-item-has-children');
		Array.prototype.forEach.call(items, function (li, i) {
			var sub = li.querySelector(':scope > .sub-menu');
			var link = li.querySelector(':scope > a');
			if (!sub || li.querySelector(':scope > .scrw-subtoggle')) { return; }
			if (!sub.id) { sub.id = 'scrw-sub-' + i; }
			var btn = doc.createElement('button');
			btn.type = 'button';
			btn.className = 'scrw-subtoggle';
			btn.setAttribute('aria-controls', sub.id);
			btn.setAttribute('aria-expanded', 'false');
			btn.setAttribute('aria-label', 'Show ' + (link ? link.textContent.trim() : '') + ' links');
			btn.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
			li.insertBefore(btn, sub);
			// Open the section that holds the current page.
			if (li.classList.contains('current-menu-ancestor') || li.classList.contains('current-menu-parent')) {
				setOpen(li, btn, true);
			}
			btn.addEventListener('click', function (e) {
				e.preventDefault();
				e.stopPropagation();
				var willOpen = !li.classList.contains('is-sub-open');
				// One section open at a time keeps the drawer short.
				Array.prototype.forEach.call(items, function (other) {
					if (other !== li) {
						var b = other.querySelector(':scope > .scrw-subtoggle');
						if (b) { setOpen(other, b, false); }
					}
				});
				setOpen(li, btn, willOpen);
			});
		});
	}
	function setOpen(li, btn, open) {
		li.classList.toggle('is-sub-open', open);
		btn.setAttribute('aria-expanded', open ? 'true' : 'false');
	}

	/* ---------- Keep every menu toggle's aria-expanded in sync ---------- */
	function syncToggles() {
		var open = body.classList.contains('sc-nav-open');
		Array.prototype.forEach.call(doc.querySelectorAll('[data-sc-menu-toggle]'), function (t) {
			t.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	}
	if (window.MutationObserver) {
		new MutationObserver(syncToggles).observe(body, { attributes: true, attributeFilter: ['class'] });
	}
	// Close the drawer if the screen grows to desktop size while it is open.
	var onMq = function () { if (!mq.matches) { body.classList.remove('sc-nav-open'); } };
	if (mq.addEventListener) { mq.addEventListener('change', onMq); } else if (mq.addListener) { mq.addListener(onMq); }

	/* ---------- WhatsApp chat ---------- */
	function initWhatsApp() {
		var root = doc.querySelector('[data-scrw-wa]');
		if (!root) { return; }
		var fab = root.querySelector('.scrw-wa__fab'), panel = root.querySelector('.scrw-wa__panel'), form = root.querySelector('.scrw-wa__form');
		var text = root.querySelector('textarea'), teaser = root.querySelector('.scrw-wa__teaser'), number = root.getAttribute('data-number');
		var store = {
			get: function (k) { try { return window.sessionStorage.getItem(k); } catch (e) { return null; } },
			set: function (k, v) { try { window.sessionStorage.setItem(k, v); } catch (e) {} }
		};

		// Online status from Kigali showroom hours.
		function mins(s) { var m = /(\d{1,2}):(\d{2})/.exec(s || ''); return m ? parseInt(m[1], 10) * 60 + parseInt(m[2], 10) : -1; }
		function range(s) { var p = (s || '').split('-'); return p.length === 2 ? [mins(p[0]), mins(p[1])] : null; }
		try {
			var parts = new Intl.DateTimeFormat('en-GB', { weekday: 'short', hour: 'numeric', minute: '2-digit', hourCycle: 'h23', timeZone: root.getAttribute('data-tz') || 'Africa/Kigali' }).formatToParts(new Date());
			var p = {}; parts.forEach(function (x) { p[x.type] = x.value; });
			var now = parseInt(p.hour, 10) * 60 + parseInt(p.minute, 10);
			var r = p.weekday === 'Sun' ? null : (p.weekday === 'Sat' ? range(root.getAttribute('data-sat')) : range(root.getAttribute('data-week')));
			var online = !!(r && now >= r[0] && now < r[1]);
			root.classList.toggle('is-online', online);
			var st = root.querySelector('.scrw-wa__status');
			if (st) { st.textContent = root.getAttribute(online ? 'data-open-text' : 'data-closed-text') || st.textContent; }
			var tm = root.querySelector('.scrw-wa__time');
			if (tm) { tm.textContent = p.hour + ':' + p.minute; }
		} catch (e) {}

		function grow() { text.style.height = 'auto'; text.style.height = Math.min(text.scrollHeight, 120) + 'px'; }
		function hideTeaser() { if (teaser) { teaser.hidden = true; } store.set('scrwWaTeaser', '1'); }
		function open() {
			hideTeaser();
			panel.hidden = false; root.classList.add('is-open'); fab.setAttribute('aria-expanded', 'true');
			if (window.matchMedia('(min-width: 783px)').matches) { text.focus(); } else { var c = root.querySelector('.scrw-wa__close'); if (c) { c.focus(); } }
		}
		function close(refocus) {
			panel.hidden = true; root.classList.remove('is-open'); fab.setAttribute('aria-expanded', 'false');
			if (refocus) { fab.focus(); }
		}
		fab.addEventListener('click', function () { if (panel.hidden) { open(); } else { close(true); } });
		root.querySelector('.scrw-wa__close').addEventListener('click', function () { close(true); });
		doc.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !panel.hidden) { close(true); } });
		doc.addEventListener('click', function (e) { if (!panel.hidden && !root.contains(e.target)) { close(false); } });

		Array.prototype.forEach.call(root.querySelectorAll('.scrw-wa__chip'), function (chip) {
			chip.addEventListener('click', function () {
				Array.prototype.forEach.call(root.querySelectorAll('.scrw-wa__chip'), function (c) { c.classList.remove('is-picked'); });
				chip.classList.add('is-picked');
				text.value = 'Hello Sound Creations Rwanda. ' + chip.textContent.trim() + '. ';
				grow(); text.focus();
				try { text.setSelectionRange(text.value.length, text.value.length); } catch (e) {}
			});
		});
		text.addEventListener('input', grow);
		text.addEventListener('keydown', function (e) {
			if (e.key === 'Enter' && !e.shiftKey && window.matchMedia('(min-width: 783px)').matches) {
				e.preventDefault();
				if (form.requestSubmit) { form.requestSubmit(); } else { form.dispatchEvent(new Event('submit', { cancelable: true })); }
			}
		});
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var msg = text.value.trim() || root.getAttribute('data-prefill') || '';
			var url = 'https://wa.me/' + number + (msg ? '?text=' + encodeURIComponent(msg) : '');
			// window.open with "noopener" returns null, which would open WhatsApp twice.
			var w = window.open(url, '_blank');
			if (w) { try { w.opener = null; } catch (err) {} } else { window.location.href = url; }
			text.value = ''; grow(); close(false);
		});

		// Friendly nudge once per visit after 15 seconds, not on contact/consultation pages.
		if (teaser && !store.get('scrwWaTeaser') && !/\/(contact|request-a-consultation)\/?$/.test(window.location.pathname)) {
			window.setTimeout(function () {
				if (panel.hidden && !store.get('scrwWaTeaser') && !body.classList.contains('sc-nav-open')) { teaser.hidden = false; }
			}, 15000);
			teaser.addEventListener('click', function (e) {
				if (e.target.closest('.scrw-wa__teaser-close')) { hideTeaser(); } else { open(); }
			});
		}
	}

	function init() { initAccordion(); syncToggles(); initWhatsApp(); }
	if (doc.readyState === 'loading') { doc.addEventListener('DOMContentLoaded', init); } else { init(); }
})();
