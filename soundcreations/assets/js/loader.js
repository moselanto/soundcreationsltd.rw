/* ============================================================
   Sound Creations activity orb.

   A small dotted "wavefront" indicator for async states: an enquiry form
   submitting, a filter recalculating. The visual idea is the one thing this
   company actually does -- a driver emitting sound -- so the dots sit on
   concentric rings and a pulse of energy travels outward through them.

   Deliberately NOT used for the full-page loader. That one is pure CSS (see
   assets/css/loader.css) so it needs no JavaScript at all and cannot delay
   first paint. This file is loaded deferred and only upgrades inline
   indicators.

   Technique notes, following the constraints that keep this cheap:
     - plain 2D canvas arcs only: no ctx.filter, no SVG filters, no WebGL,
       so it renders identically in Chrome, Safari and Firefox and stays
       cheap on low-end devices
     - devicePixelRatio capped at 2
     - one shared clock for every instance, so they animate in phase and we
       schedule a single rAF for the whole page rather than one per orb
     - instances pause when scrolled offscreen (IntersectionObserver) or
       when the tab is hidden, and resume in phase
     - prefers-reduced-motion renders one static representative frame

   Usage:
       <span class="sc-orb" data-sc-orb></span>
       <span class="sc-orb sc-orb--lg" data-sc-orb="lg"></span>

   Colour is read from the computed --sc-purple-300 / --sc-accent tokens, so
   it follows the active theme automatically with no JS theme logic.
   ============================================================ */

(function () {
	'use strict';

	var orbs = [];
	var running = false;
	var reduced = window.matchMedia
		? window.matchMedia('(prefers-reduced-motion: reduce)').matches
		: false;

	function token(name, fallback) {
		try {
			var v = getComputedStyle(document.documentElement).getPropertyValue(name);
			return v && v.trim() ? v.trim() : fallback;
		} catch (e) {
			return fallback;
		}
	}

	function Orb(el) {
		this.el = el;
		this.canvas = document.createElement('canvas');
		this.ctx = this.canvas.getContext('2d');
		this.visible = true;
		el.appendChild(this.canvas);

		// Two tuned presets rather than one scaled design: a 20px orb needs
		// fewer, larger dots to stay legible, not the same dots shrunk.
		var lg = el.getAttribute('data-sc-orb') === 'lg';
		this.rings = lg ? 4 : 3;
		this.dot = lg ? 1.7 : 1.25;
		this.perRing = lg ? 14 : 9;

		this.resize();
	}

	Orb.prototype.resize = function () {
		var dpr = Math.min(window.devicePixelRatio || 1, 2);
		var r = this.el.getBoundingClientRect();
		var w = Math.max(1, Math.round(r.width));
		var h = Math.max(1, Math.round(r.height));
		this.w = w;
		this.h = h;
		this.canvas.width = Math.round(w * dpr);
		this.canvas.height = Math.round(h * dpr);
		this.ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
	};

	Orb.prototype.draw = function (t) {
		var ctx = this.ctx;
		var w = this.w;
		var h = this.h;
		var cx = w / 2;
		var cy = h / 2;
		var maxR = Math.min(w, h) / 2 - this.dot - 1;

		ctx.clearRect(0, 0, w, h);

		var ink = token('--sc-purple-300', '#A98BD6');
		var hot = token('--sc-accent', '#BA0B0B');

		// The travelling pulse. Period is 1.6s; the wavefront sweeps from the
		// core out to the rim, and a dot brightens as the front passes it.
		var period = 1600;
		var phase = (t % period) / period;

		for (var ri = 0; ri < this.rings; ri++) {
			var rNorm = (ri + 1) / this.rings;
			var radius = maxR * rNorm;
			var count = Math.round(this.perRing * rNorm) + 3;

			// Distance from this ring to the current wavefront, wrapped so the
			// animation loops seamlessly.
			var d = rNorm - phase;
			if (d < -0.5) { d += 1; }
			if (d > 0.5) { d -= 1; }

			// Gaussian falloff: a soft front rather than a hard edge.
			var energy = Math.exp(-(d * d) / 0.012);

			for (var i = 0; i < count; i++) {
				var a = (i / count) * Math.PI * 2 - Math.PI / 2;
				var x = cx + Math.cos(a) * radius;
				var y = cy + Math.sin(a) * radius;

				var size = this.dot * (0.72 + energy * 0.95);
				ctx.globalAlpha = 0.2 + energy * 0.7;
				ctx.fillStyle = ink;
				ctx.beginPath();
				ctx.arc(x, y, size, 0, Math.PI * 2);
				ctx.fill();
			}
		}

		// The driver at the centre, firing as each wavefront leaves.
		var fire = Math.exp(-(phase * phase) / 0.006);
		ctx.globalAlpha = 0.65 + fire * 0.35;
		ctx.fillStyle = hot;
		ctx.beginPath();
		ctx.arc(cx, cy, this.dot * (1.25 + fire * 0.75), 0, Math.PI * 2);
		ctx.fill();

		ctx.globalAlpha = 1;
	};

	function frame(now) {
		var any = false;
		for (var i = 0; i < orbs.length; i++) {
			if (orbs[i].visible) {
				orbs[i].draw(now);
				any = true;
			}
		}
		if (any && document.visibilityState !== 'hidden') {
			window.requestAnimationFrame(frame);
		} else {
			running = false;
		}
	}

	function start() {
		if (running || reduced || document.visibilityState === 'hidden') { return; }
		running = true;
		window.requestAnimationFrame(frame);
	}

	function register(el) {
		if (el.getAttribute('data-sc-orb-ready') === '1') { return; }
		el.setAttribute('data-sc-orb-ready', '1');
		el.setAttribute('role', 'status');
		if (el.getAttribute('aria-label') === null) {
			el.setAttribute('aria-label', 'Working');
		}

		var orb = new Orb(el);
		orbs.push(orb);

		if (reduced) {
			// One representative frame, no animation. Picked at the moment a
			// wavefront is mid-flight so the shape still reads correctly.
			orb.draw(400);
			return;
		}

		if (typeof window.IntersectionObserver === 'function') {
			var io = new IntersectionObserver(function (entries) {
				for (var i = 0; i < entries.length; i++) {
					orb.visible = entries[i].isIntersecting;
					if (orb.visible) { start(); }
				}
			}, { rootMargin: '64px' });
			io.observe(el);
		}

		start();
	}

	function scan(scope) {
		var found = (scope || document).querySelectorAll('[data-sc-orb]');
		for (var i = 0; i < found.length; i++) { register(found[i]); }
	}

	// Public hook so other scripts can put a button into a busy state without
	// duplicating markup or knowing how the orb is built.
	window.scOrb = {
		scan: scan,
		busy: function (btn, on) {
			if (on === false) {
				btn.removeAttribute('data-sc-busy');
				btn.removeAttribute('aria-busy');
				var old = btn.querySelector('[data-sc-orb]');
				if (old) { old.parentNode.removeChild(old); }
				return;
			}
			if (btn.getAttribute('data-sc-busy') === '1') { return; }
			btn.setAttribute('data-sc-busy', '1');
			btn.setAttribute('aria-busy', 'true');
			var s = document.createElement('span');
			s.className = 'sc-orb';
			s.setAttribute('data-sc-orb', '');
			btn.appendChild(s);
			register(s);
		}
	};

	document.addEventListener('visibilitychange', function () {
		if (document.visibilityState !== 'hidden') { start(); }
	});

	var resizeTimer = null;
	window.addEventListener('resize', function () {
		if (resizeTimer) { window.clearTimeout(resizeTimer); }
		resizeTimer = window.setTimeout(function () {
			for (var i = 0; i < orbs.length; i++) { orbs[i].resize(); }
			start();
		}, 150);
	}, { passive: true });

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { scan(); });
	} else {
		scan();
	}
})();
