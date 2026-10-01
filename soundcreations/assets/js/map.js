(function () {
	function activate(root, id) {
		var els = root.querySelectorAll('[data-loc]');
		for (var i = 0; i < els.length; i++) {
			var el = els[i];
			if (el.getAttribute('data-loc') === id) {
				el.classList.add('is-active');
			} else {
				el.classList.remove('is-active');
			}
		}
	}
	function wire(root) {
		var els = root.querySelectorAll('[data-loc]');
		for (var i = 0; i < els.length; i++) {
			(function (el) {
				var id = el.getAttribute('data-loc');
				el.addEventListener('click', function () { activate(root, id); });
				el.addEventListener('mouseenter', function () { activate(root, id); });
				el.addEventListener('focus', function () { activate(root, id); });
			})(els[i]);
		}
		var first = root.querySelector('.sc-map__item, .sc-office');
		if (first) { activate(root, first.getAttribute('data-loc')); }
	}
	document.addEventListener('DOMContentLoaded', function () {
		var maps = document.querySelectorAll('.sc-map, [data-sc-locmap]');
		for (var i = 0; i < maps.length; i++) { wire(maps[i]); }
	});
})();
