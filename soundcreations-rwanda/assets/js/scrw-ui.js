/* Sound Creations Rwanda UI (1.9.2): mobile menu accordion, menu-toggle sync, WhatsApp chat card (ported from soundcreationsltd.com). */
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

	/* ---------- WhatsApp chat card ---------- */
	function initWhatsApp() {
		var root = document.querySelector( '[data-sc-wa]' );
		if ( root === null ) { return; }
		document.documentElement.classList.add( 'sc-wa-js' );
		var fab = root.querySelector( '.sc-wa__fab' ),
			panel = root.querySelector( '.sc-wa__panel' ),
			form = root.querySelector( '.sc-wa__form' ),
			text = root.querySelector( 'textarea' ),
			teaser = root.querySelector( '.sc-wa__teaser' ),
			scrim = root.querySelector( '.sc-wa__scrim' ),
			phone = window.matchMedia( '(max-width: 782px)' ),
			number = root.getAttribute( 'data-number' );
		var store = {
			get: function ( k ) { try { return window.sessionStorage.getItem( k ); } catch ( e ) { return null; } },
			set: function ( k, v ) { try { window.sessionStorage.setItem( k, v ); } catch ( e ) {} }
		};

		// Online status from the Kigali showroom hours in Settings.
		try {
			var hours = JSON.parse( root.getAttribute( 'data-hours' ) || '{}' );
			var parts = new Intl.DateTimeFormat( 'en-GB', { weekday: 'short', hour: 'numeric', minute: '2-digit', hourCycle: 'h23', timeZone: 'Africa/Kigali' } ).formatToParts( new Date() );
			var p = {};
			parts.forEach( function ( x ) { p[ x.type ] = x.value; } );
			var mins = parseInt( p.hour, 10 ) * 60 + parseInt( p.minute, 10 );
			var today = hours[ p.weekday ];
			var online = Array.isArray( today ) && mins >= today[0] && mins < today[1];
			root.classList.toggle( 'is-online', online );
			var st = root.querySelector( '.sc-wa__status' );
			if ( st ) { st.textContent = root.getAttribute( online ? 'data-open-text' : 'data-closed-text' ) || st.textContent; }
			var tm = root.querySelector( '.sc-wa__time' );
			if ( tm ) { tm.textContent = p.hour + ':' + p.minute; }
		} catch ( e ) {}

		function grow() { text.style.height = 'auto'; text.style.height = Math.min( text.scrollHeight, 120 ) + 'px'; }
		function hideTeaser() { if ( teaser ) { teaser.hidden = true; } store.set( 'scWaTeaser', '1' ); }
		function open() {
			hideTeaser();
			panel.hidden = false;
			root.classList.add( 'is-open' );
			// Phones: bottom sheet with a dimmed backdrop and the page held still.
			if ( phone.matches ) {
				if ( scrim ) { scrim.hidden = false; }
				document.documentElement.classList.add( 'sc-wa-sheet-open' );
			}
			fab.setAttribute( 'aria-expanded', 'true' );
			if ( window.matchMedia( '(min-width: 783px)' ).matches ) { text.focus(); }
			else { var c = root.querySelector( '.sc-wa__close' ); if ( c ) { c.focus(); } }
		}
		function close( refocus ) {
			panel.hidden = true;
			root.classList.remove( 'is-open' );
			if ( scrim ) { scrim.hidden = true; }
			document.documentElement.classList.remove( 'sc-wa-sheet-open' );
			fab.setAttribute( 'aria-expanded', 'false' );
			if ( refocus ) { fab.focus(); }
		}

		fab.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			if ( panel.hidden ) { open(); } else { close( true ); }
		} );
		// The mobile quick-action bar's WhatsApp button opens the same card.
		document.querySelectorAll( '[data-sc-wa-open]' ).forEach( function ( btn ) {
			btn.addEventListener( 'click', function ( e ) { e.preventDefault(); e.stopPropagation(); open(); } );
		} );
		root.querySelector( '.sc-wa__close' ).addEventListener( 'click', function () { close( true ); } );
		if ( scrim ) { scrim.addEventListener( 'click', function () { close( true ); } ); }
		document.addEventListener( 'keydown', function ( e ) { if ( e.key === 'Escape' && panel.hidden === false ) { close( true ); } } );
		document.addEventListener( 'click', function ( e ) {
			if ( panel.hidden === false && ( root.contains( e.target ) === false || e.target === scrim ) && ( e.target.closest && e.target.closest( '[data-sc-wa-open]' ) ) === null ) { close( false ); }
		} );

		root.querySelectorAll( '.sc-wa__chip' ).forEach( function ( chip ) {
			chip.addEventListener( 'click', function () {
				root.querySelectorAll( '.sc-wa__chip' ).forEach( function ( c ) { c.classList.remove( 'is-picked' ); } );
				chip.classList.add( 'is-picked' );
				text.value = chip.textContent.trim() + '. ';
				grow();
				text.focus();
				try { text.setSelectionRange( text.value.length, text.value.length ); } catch ( e ) {}
			} );
		} );
		text.addEventListener( 'input', grow );
		text.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Enter' && e.shiftKey === false && window.matchMedia( '(min-width: 783px)' ).matches ) {
				e.preventDefault();
				if ( form.requestSubmit ) { form.requestSubmit(); } else { form.dispatchEvent( new Event( 'submit', { cancelable: true } ) ); }
			}
		} );
		form.addEventListener( 'submit', function ( e ) {
			e.preventDefault();
			var msg = text.value.trim();
			var picked = root.querySelector( '.sc-wa__chip.is-picked' );
			var url = 'https://wa.me/' + number + ( msg ? '?text=' + encodeURIComponent( msg ) : '' );
			// window.open with "noopener" returns null, which used to open WhatsApp twice.
			var w = window.open( url, '_blank' );
			if ( w ) { try { w.opener = null; } catch ( err ) {} } else { window.location.href = url; }
			text.value = '';
			grow();
			close( false );
		} );

		// One friendly nudge per visit, after 15 seconds, not on contact or quote pages.
		if ( teaser && store.get( 'scWaTeaser' ) === null && /\/(contact|request-a-quote|request-a-consultation)\/?$/.test( window.location.pathname ) === false ) {
			window.setTimeout( function () { if ( panel.hidden && store.get( 'scWaTeaser' ) === null ) { teaser.hidden = false; } }, 15000 );
			teaser.addEventListener( 'click', function ( e ) {
				if ( e.target.closest( '.sc-wa__teaser-close' ) ) { hideTeaser(); } else { open(); }
			} );
		}
	}

	function init() { syncToggles(); initWhatsApp(); } // 1.9.4: sub-menus always open on phones, no accordion.
	if (doc.readyState === 'loading') { doc.addEventListener('DOMContentLoaded', init); } else { init(); }
})();

/* 2.3.10: Featured Projects carousel controls.
   Arrows on desktop (they had no script before) and, on phones, a control bar
   under the cards: previous / next buttons, dots and a "1 / 6" counter, plus
   swipe. Built with DOM methods only (no innerHTML), so it works with strict
   script security. */
(function () {
	var roots = document.querySelectorAll('[data-sc-carousel]');
	Array.prototype.forEach.call(roots, function (root) {
		var track = root.querySelector('.sc-carousel__track');
		if (!track) { return; }
		var cards = Array.prototype.filter.call(track.children, function (el) { return el.nodeType === 1; });
		if (cards.length < 2) { return; }
		var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		function step() {
			var gap = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap) || 0;
			return cards[0].getBoundingClientRect().width + gap;
		}
		function current() {
			return Math.max(0, Math.min(cards.length - 1, Math.round(track.scrollLeft / step())));
		}
		function go(i) {
			i = Math.max(0, Math.min(cards.length - 1, i));
			track.scrollTo({ left: i * step(), behavior: reduce ? 'auto' : 'smooth' });
		}

		/* Phone control bar. */
		var nav = document.createElement('div');
		nav.className = 'scrw-carnav';
		nav.setAttribute('role', 'group');
		nav.setAttribute('aria-label', 'Browse projects');

		function mkBtn(cls, label, path) {
			var b = document.createElement('button');
			b.type = 'button';
			b.className = 'scrw-carnav__btn ' + cls;
			b.setAttribute('aria-label', label);
			var svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
			svg.setAttribute('viewBox', '0 0 24 24');
			svg.setAttribute('width', '20');
			svg.setAttribute('height', '20');
			svg.setAttribute('aria-hidden', 'true');
			var p = document.createElementNS('http://www.w3.org/2000/svg', 'path');
			p.setAttribute('d', path);
			p.setAttribute('fill', 'none');
			p.setAttribute('stroke', 'currentColor');
			p.setAttribute('stroke-width', '2.2');
			p.setAttribute('stroke-linecap', 'round');
			p.setAttribute('stroke-linejoin', 'round');
			svg.appendChild(p);
			b.appendChild(svg);
			return b;
		}
		var prev = mkBtn('scrw-carnav__btn--prev', 'Previous project', 'M15 18l-6-6 6-6');
		var next = mkBtn('scrw-carnav__btn--next', 'Next project', 'M9 18l6-6-6-6');

		var mid = document.createElement('div');
		mid.className = 'scrw-carnav__mid';
		var dots = document.createElement('div');
		dots.className = 'scrw-carnav__dots';
		var dotEls = cards.map(function (c, i) {
			var d = document.createElement('button');
			d.type = 'button';
			d.className = 'scrw-carnav__dot';
			d.setAttribute('aria-label', 'Go to project ' + (i + 1) + ' of ' + cards.length);
			d.addEventListener('click', function () { go(i); });
			dots.appendChild(d);
			return d;
		});
		var count = document.createElement('span');
		count.className = 'scrw-carnav__count';
		count.setAttribute('aria-live', 'polite');
		var hint = document.createElement('span');
		hint.className = 'scrw-carnav__hint';
		hint.textContent = 'Swipe to see more';
		mid.appendChild(dots);
		mid.appendChild(count);
		mid.appendChild(hint);

		nav.appendChild(prev);
		nav.appendChild(mid);
		nav.appendChild(next);
		root.parentNode.insertBefore(nav, root.nextSibling);

		/* Existing desktop arrows. */
		var dPrev = root.querySelector('[data-sc-prev]');
		var dNext = root.querySelector('[data-sc-next]');

		function update() {
			var i = current();
			var atStart = track.scrollLeft <= 2;
			var atEnd = track.scrollLeft + track.clientWidth >= track.scrollWidth - 2;
			[prev, dPrev].forEach(function (b) { if (b) { b.disabled = atStart; } });
			[next, dNext].forEach(function (b) { if (b) { b.disabled = atEnd; } });
			dotEls.forEach(function (d, k) {
				var on = k === i;
				d.classList.toggle('is-active', on);
				if (on) { d.setAttribute('aria-current', 'true'); } else { d.removeAttribute('aria-current'); }
			});
			count.textContent = (i + 1) + ' / ' + cards.length;
			if (track.scrollLeft > 10) { nav.classList.add('is-used'); }
		}

		[prev, dPrev].forEach(function (b) { if (b) { b.addEventListener('click', function () { go(current() - 1); }); } });
		[next, dNext].forEach(function (b) { if (b) { b.addEventListener('click', function () { go(current() + 1); }); } });

		var t = null;
		track.addEventListener('scroll', function () {
			if (t) { cancelAnimationFrame(t); }
			t = requestAnimationFrame(update);
		}, { passive: true });
		window.addEventListener('resize', update);

		/* Keyboard: arrow keys when the track has focus. */
		if (!track.hasAttribute('tabindex')) { track.setAttribute('tabindex', '0'); }
		track.setAttribute('aria-label', 'Featured projects, scroll sideways');
		track.addEventListener('keydown', function (e) {
			if (e.key === 'ArrowRight') { e.preventDefault(); go(current() + 1); }
			if (e.key === 'ArrowLeft') { e.preventDefault(); go(current() - 1); }
		});

		update();
	});
})();
