(function () {
  'use strict';
  var root = document.documentElement;
  var body = document.body;

  function currentTheme() {
    return root.getAttribute('data-theme') === 'light' ? 'light' : 'dark';
  }
  function setTheme(t) {
    root.setAttribute('data-theme', t);
    try { localStorage.setItem('sc-theme', t); } catch (e) {}
  }

  function toggleEl() {
    return document.querySelector('[data-sc-menu-toggle]');
  }
  function openMenu() {
    body.classList.add('sc-nav-open');
    var t = toggleEl();
    if (t) { t.setAttribute('aria-expanded', 'true'); }
  }
  function closeMenu() {
    body.classList.remove('sc-nav-open');
    var t = toggleEl();
    if (t) { t.setAttribute('aria-expanded', 'false'); }
  }

  document.addEventListener('click', function (e) {
    var themeBtn = e.target.closest('[data-sc-theme-toggle]');
    if (themeBtn) {
      setTheme(currentTheme() === 'dark' ? 'light' : 'dark');
      return;
    }
    var toggle = e.target.closest('[data-sc-menu-toggle]');
    if (toggle) {
      if (body.classList.contains('sc-nav-open')) { closeMenu(); } else { openMenu(); }
      return;
    }
    if (e.target.closest('[data-sc-menu-close]')) {
      closeMenu();
      return;
    }
    var link = e.target.closest('.sc-nav a');
    if (link) {
      closeMenu();
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && body.classList.contains('sc-nav-open')) {
      closeMenu();
    }
  });

  var header = document.querySelector('.sc-header');
  if (header) {
    var onScroll = function () {
      header.classList.toggle('is-compact', window.scrollY > 24);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }
})();

/* FANE product carousel + filters */
(function () {
	function scroller(btn) {
		var wrap = btn.closest('.sc-prod-carousel');
		if (wrap === null) { return; }
		var track = wrap.querySelector('[data-sc-scroller]');
		if (track === null) { return; }
		var dir = btn.getAttribute('data-sc-scroll') === 'next' ? 1 : -1;
		var amount = Math.max(240, Math.round(track.clientWidth * 0.8));
		track.scrollBy({ left: dir * amount, behavior: 'smooth' });
	}
	function inRange(power, val) {
		if (val === 'all') { return true; }
		var parts = val.split('-');
		var lo = parseInt(parts[0], 10);
		var hi = parseInt(parts[1], 10);
		var p = parseInt(power, 10);
		return p >= lo && p <= hi;
	}
	function initFilters(root) {
		var selects = root.querySelectorAll('select[data-f]');
		var carousel = root.parentNode.querySelector('.sc-prod-carousel');
		var track = carousel === null ? null : carousel.querySelector('[data-sc-scroller]');
		if (track === null) { return; }
		var cards = track.querySelectorAll('.sc-prod-card');
		var empty = track.querySelector('[data-sc-noresults]');
		function apply() {
			var f = {};
			for (var i = 0; i < selects.length; i++) { f[selects[i].getAttribute('data-f')] = selects[i].value; }
			var shown = 0;
			for (var j = 0; j < cards.length; j++) {
				var c = cards[j];
				var app = c.getAttribute('data-app') || '';
				var okApp = f.app === 'all' || app.indexOf(f.app) > -1;
				var okType = f.type === 'all' || c.getAttribute('data-type') === f.type;
				var okSize = f.size === 'all' || c.getAttribute('data-size') === f.size;
				var okPower = inRange(c.getAttribute('data-power'), f.power);
				var vis = okApp && okType && okSize && okPower;
				c.style.display = vis ? '' : 'none';
				if (vis) { shown = shown + 1; }
			}
			if (empty) { empty.hidden = shown > 0; }
		}
		for (var k = 0; k < selects.length; k++) { selects[k].addEventListener('change', apply); }
	}
	document.addEventListener('DOMContentLoaded', function () {
		var navs = document.querySelectorAll('[data-sc-scroll]');
		for (var i = 0; i < navs.length; i++) {
			navs[i].addEventListener('click', function (e) { scroller(e.currentTarget); });
		}
		var filters = document.querySelectorAll('[data-sc-prodfilter]');
		for (var m2 = 0; m2 < filters.length; m2++) { initFilters(filters[m2]); }
	});
})();

/* Brands category tabs active-toggle */
(function () {
	document.addEventListener('DOMContentLoaded', function () {
		var cards = document.querySelectorAll('.sc-cat-card');
		for (var i = 0; i < cards.length; i++) {
			cards[i].addEventListener('click', function () {
				var row = this.parentNode;
				var all = row.querySelectorAll('.sc-cat-card');
				for (var j = 0; j < all.length; j++) { all[j].classList.remove('is-active'); }
				this.classList.add('is-active');
			});
		}
	});
})();

/* Projects archive: category pills + solution/location selects + search */
(function () {
	function initProjFilter(root) {
		var pills = root.querySelectorAll('[data-proj-cat]');
		var solSel = root.querySelector('[data-proj-sol]');
		var locSel = root.querySelector('[data-proj-loc]');
		var search = root.querySelector('[data-proj-search]');
		var cards = root.querySelectorAll('[data-card]');
		var empty = root.querySelector('[data-proj-empty]');
		var state = { cat: 'all', sol: 'all', loc: 'all', q: '' };
		function apply() {
			var shown = 0;
			for (var i = 0; i < cards.length; i++) {
				var c = cards[i];
				var okCat = state.cat === 'all' || (' ' + (c.getAttribute('data-category') || '') + ' ').indexOf(' ' + state.cat + ' ') > -1;
				var okSol = state.sol === 'all' || c.getAttribute('data-solution') === state.sol;
				var okLoc = state.loc === 'all' || c.getAttribute('data-location') === state.loc;
				var okQ = state.q === '' || (c.getAttribute('data-text') || '').indexOf(state.q) > -1;
				var vis = okCat && okSol && okLoc && okQ;
				c.style.display = vis ? '' : 'none';
				if (vis) { shown = shown + 1; }
			}
			if (empty) { empty.hidden = shown > 0; }
		}
		for (var pi = 0; pi < pills.length; pi++) {
			pills[pi].addEventListener('click', function () {
				for (var q = 0; q < pills.length; q++) { pills[q].classList.remove('is-active'); }
				this.classList.add('is-active');
				state.cat = this.getAttribute('data-proj-cat') || 'all';
				apply();
			});
		}
		if (solSel) { solSel.addEventListener('change', function () { state.sol = this.value; apply(); }); }
		if (locSel) { locSel.addEventListener('change', function () { state.loc = this.value; apply(); }); }
		if (search) { search.addEventListener('input', function () { state.q = (this.value || '').toLowerCase().trim(); apply(); }); }
	}
	document.addEventListener('DOMContentLoaded', function () {
		var roots = document.querySelectorAll('[data-sc-projfilter]');
		for (var i = 0; i < roots.length; i++) { initProjFilter(roots[i]); }
	});
})();


/* IIFE5: click-to-play YouTube facade for the About "Our Story" card */
(function () {
	function ytFrame(id) {
		var f = document.createElement('iframe');
		f.className = 'sc-story__frame';
		f.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
		f.setAttribute('allowfullscreen', '');
		f.setAttribute('title', 'Story video');
		f.src = 'https://www.youtube.com/embed/' + id + '?autoplay=1&rel=0&modestbranding=1';
		return f;
	}
	function play(el) {
		var id = el.getAttribute('data-sc-youtube');
		if (id === null || id === '' || el.classList.contains('is-playing')) { return; }
		el.classList.add('is-playing');
		el.appendChild(ytFrame(id));
	}
	function findStory(t) {
		return (t && t.closest) ? t.closest('.sc-story[data-sc-youtube]') : null;
	}
	document.addEventListener('click', function (e) {
		var el = findStory(e.target);
		if (el) { play(el); }
	});
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Enter' || e.key === ' ') {
			var el = findStory(e.target);
			if (el) { e.preventDefault(); play(el); }
		}
	});
})();

/* Contact page: clicking an office swaps the "Reach us directly" phone / email / hours */
(function () {
	function initContacts(map) {
		var data;
		try { data = JSON.parse(map.getAttribute('data-sc-contacts') || '{}'); } catch (e) { data = {}; }
		var info = map.closest('.sc-teamcard') || document;
		var phoneEl = info.querySelector('[data-sc-phone]');
		var emailEl = info.querySelector('[data-sc-email]');
		var hoursEl = info.querySelector('[data-sc-hours]');
		function setLoc(loc) {
			var c = data[loc];
			if (c) {
				if (phoneEl) { phoneEl.textContent = c.phone; phoneEl.setAttribute('href', 'tel:' + c.phone_link); }
				if (emailEl) { emailEl.textContent = c.email; emailEl.setAttribute('href', 'mailto:' + c.email); }
				if (hoursEl) {
					while (hoursEl.firstChild) { hoursEl.removeChild(hoursEl.firstChild); }
					var lines = (c.hours || '').split('|');
					for (var j = 0; j < lines.length; j++) {
						if (j > 0) { hoursEl.appendChild(document.createElement('br')); }
						hoursEl.appendChild(document.createTextNode(lines[j].trim()));
					}
				}
			}
		}
		var trig = map.querySelectorAll('[data-loc]');
		for (var i = 0; i < trig.length; i++) {
			trig[i].addEventListener('click', function () { setLoc(this.getAttribute('data-loc')); });
			trig[i].addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { setLoc(this.getAttribute('data-loc')); } });
		}
	}
	document.addEventListener('DOMContentLoaded', function () {
		var maps = document.querySelectorAll('[data-sc-locmap][data-sc-contacts]');
		for (var i = 0; i < maps.length; i++) { initContacts(maps[i]); }
	});
})();

/* Homepage hero video: deferred load.

   The markup deliberately ships no <source> and no autoplay (see front-page.php).
   An autoplaying video with a src is fetched immediately and in full, and this
   file is ~23MB, so it was saturating the connection while the stylesheet, fonts
   and the LCP poster were still in flight.

   The poster is painted as the hero section's CSS background, so the hero is
   complete and correct before this runs -- attaching the video late changes
   nothing visually except that motion begins a moment later.

   Held back entirely when the visitor has Data Saver on, is on a 2g-class
   connection, or has asked for reduced motion: in those cases the poster is the
   better experience and 23MB would be actively hostile. */
(function () {
	function attach(v) {
		if (v.getAttribute('data-sc-loaded') === '1') { return; }
		var src = v.getAttribute('data-sc-hero-video');
		if (!src) { return; }
		v.setAttribute('data-sc-loaded', '1');
		var s = document.createElement('source');
		s.setAttribute('src', src);
		s.setAttribute('type', 'video/mp4');
		v.appendChild(s);
		v.preload = 'auto';
		v.load();
		var p = v.play();
		// Autoplay can still be refused (power saving, platform policy). The poster
		// stays put, so a rejection is a non-event -- swallow it rather than logging.
		if (p && typeof p.catch === 'function') { p.catch(function () {}); }
	}

	function init() {
		var v = document.querySelector('[data-sc-hero-video]');
		if (!v) { return; }

		// Bandwidth guards. These are deliberately CONSERVATIVE: the hero video is
		// a brand asset and should play for essentially everyone, including on
		// mobile. A previous revision also skipped 3g and every viewport under
		// 900px, which silently killed the video on all phones -- do not
		// reintroduce that. The correct fix for the file's weight is to re-encode
		// it (~22MB is far too large), not to hide it from mobile visitors.
		//
		// Data Saver and 2g remain excluded: at that point the poster genuinely is
		// the better experience. Both bail-outs leave the poster in place.
		var c = navigator.connection || navigator.webkitConnection || {};
		if (c.saveData === true) { return; }
		if (typeof c.effectiveType === 'string' && c.effectiveType.indexOf('2g') !== -1) { return; }
		if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) { return; }

		if (typeof window.IntersectionObserver === 'function') {
			var io = new IntersectionObserver(function (entries) {
				for (var i = 0; i < entries.length; i++) {
					if (entries[i].isIntersecting) {
						io.disconnect();
						attach(v);
						return;
					}
				}
			}, { rootMargin: '200px' });
			io.observe(v);
		} else {
			attach(v);
		}
	}

	// Wait for load, not DOMContentLoaded: everything that makes the page usable
	// should have its bandwidth first.
	if (document.readyState === 'complete') {
		init();
	} else {
		window.addEventListener('load', init);
	}
})();
