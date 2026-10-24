/* KI Packaging theme: interaction layer. Vanilla JS, no dependencies. */
(function () {
	'use strict';

	var doc = document;
	var root = doc.documentElement;
	var body = doc.body;
	var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var fine = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
	var $ = function (s, c) { return (c || doc).querySelector(s); };
	var $$ = function (s, c) { return Array.prototype.slice.call((c || doc).querySelectorAll(s)); };

	/* ---------- Loader ---------- */
	function hideLoader() {
		var l = $('.loader');
		if (!l) return;
		l.classList.add('is-done');
		try { sessionStorage.setItem('ki_seen', '1'); } catch (e) { /* storage unavailable */ }
		setTimeout(function () { if (l.parentNode) l.parentNode.removeChild(l); }, 900);
	}
	if (doc.readyState === 'complete') { setTimeout(hideLoader, 300); } else { window.addEventListener('load', function () { setTimeout(hideLoader, 300); }); }
	setTimeout(hideLoader, 2600);

	/* ---------- Scroll state: header, progress bar, back-to-top ---------- */
	var header = $('[data-header]');
	var bar = $('.scroll-progress span');
	var toTop = $('[data-to-top]');
	var ticking = false;
	function onScroll() {
		var y = window.pageYOffset || root.scrollTop;
		if (header) header.classList.toggle('is-scrolled', y > 40);
		if (bar) {
			var max = root.scrollHeight - window.innerHeight;
			bar.style.transform = 'scaleX(' + (max > 0 ? Math.min(y / max, 1) : 0) + ')';
		}
		if (toTop) toTop.classList.toggle('is-visible', y > 700);
		if (!reduce) parallax(y);
		ticking = false;
	}
	window.addEventListener('scroll', function () { if (!ticking) { ticking = true; requestAnimationFrame(onScroll); } }, { passive: true });
	if (toTop) toTop.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' }); });

	/* ---------- Parallax (page hero backgrounds) ---------- */
	var pxEls = $$('[data-parallax]');
	function parallax(y) {
		pxEls.forEach(function (el) {
			var host = el.parentNode;
			var h = host.offsetHeight;
			if (y > h + 200) return;
			var speed = parseFloat(el.getAttribute('data-parallax')) || 0.2;
			el.style.transform = 'translate3d(0,' + Math.min(y * speed, h * 0.1).toFixed(1) + 'px,0)';
		});
	}

	/* ---------- Mobile navigation ---------- */
	var toggle = $('[data-nav-toggle]');
	function setNav(open) {
		body.classList.toggle('nav-open', open);
		if (toggle) {
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
		}
	}
	if (toggle) {
		toggle.addEventListener('click', function () { setNav(!body.classList.contains('nav-open')); });
		$$('[data-nav] a').forEach(function (a) { a.addEventListener('click', function () { setNav(false); }); });
		window.addEventListener('resize', function () { if (window.innerWidth > 1120) setNav(false); });
	}

	/* ---------- Elementor-friendly helpers ---------- */
	var inEditor = body.classList.contains('elementor-editor-active');

	// Headline word animation: wraps words of any .split element so editors only type plain text.
	function splitWords(el) {
		var i = 0;
		(function walk(node) {
			Array.prototype.slice.call(node.childNodes).forEach(function (n) {
				if (n.nodeType === 3) {
					var frag = doc.createDocumentFragment();
					n.nodeValue.split(/(\s+)/).forEach(function (part) {
						if (!part) return;
						if (/^\s+$/.test(part)) { frag.appendChild(doc.createTextNode(' ')); return; }
						var w = doc.createElement('span'); w.className = 'w';
						var inner = doc.createElement('span'); inner.style.setProperty('--i', i++); inner.textContent = part;
						w.appendChild(inner); frag.appendChild(w);
					});
					node.replaceChild(frag, n);
				} else if (n.nodeType === 1 && n.tagName !== 'BR') { walk(n); }
			});
		})(el);
		el.classList.add('is-split');
	}
	if (!inEditor) {
		$$('.split').forEach(function (el) { if (!$('.w', el)) splitWords(el); else el.classList.add('is-split'); });
	}

	// Seamless marquee from a normal Elementor icon list.
	$$('.marquee-list').forEach(function (w) {
		var ul = $('ul', w);
		if (!ul || $('.marquee-track', w)) return;
		var track = doc.createElement('div'); track.className = 'marquee-track';
		var clone = ul.cloneNode(true); clone.setAttribute('aria-hidden', 'true');
		ul.parentNode.insertBefore(track, ul); track.appendChild(ul); track.appendChild(clone);
	});

	/* ---------- Scroll reveal ---------- */
	var reveals = $$('[data-reveal],.rv,.rv-left,.rv-img');
	if (inEditor) { reveals.forEach(function (el) { el.classList.add('is-in', 'is-done'); }); reveals = []; }
	function revealNow(el) {
		el.classList.add('is-in');
		setTimeout(function () { el.classList.add('is-done'); }, 1500);
	}
	if ('IntersectionObserver' in window && !reduce) {
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (e) {
				if (e.isIntersecting) { revealNow(e.target); io.unobserve(e.target); }
			});
		}, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });
		reveals.forEach(function (el) { io.observe(el); });
	} else {
		reveals.forEach(function (el) { el.classList.add('is-in', 'is-done'); });
	}

	/* ---------- Counters ---------- */
	$$('.ki-count').forEach(function (el) {
		var m = el.textContent.trim().match(/^(\D*)(\d+(?:\.\d+)?)(\D*)$/);
		if (m) { el.setAttribute('data-prefix', m[1]); el.setAttribute('data-count', m[2]); el.setAttribute('data-suffix', m[3]); }
	});
	var counters = inEditor ? [] : $$('[data-count]');
	function runCounter(el) {
		var target = parseFloat(el.getAttribute('data-count')) || 0;
		var pre = el.getAttribute('data-prefix') || '';
		var suf = el.getAttribute('data-suffix') || '';
		if (reduce) { el.textContent = pre + target + suf; return; }
		var t0 = null, dur = 1700;
		function step(t) {
			if (t0 === null) t0 = t;
			var p = Math.min((t - t0) / dur, 1);
			var eased = 1 - Math.pow(1 - p, 4);
			el.textContent = pre + Math.round(target * eased) + suf;
			if (p < 1) requestAnimationFrame(step);
		}
		requestAnimationFrame(step);
	}
	if ('IntersectionObserver' in window) {
		var co = new IntersectionObserver(function (entries) {
			entries.forEach(function (e) { if (e.isIntersecting) { runCounter(e.target); co.unobserve(e.target); } });
		}, { threshold: 0.6 });
		counters.forEach(function (el) { co.observe(el); });
	}

	/* ---------- Hero slideshow ---------- */
	var slides = $$('.hero-slide');
	if (slides.length > 1 && !reduce) {
		var idx = 0;
		setInterval(function () {
			if (doc.hidden) return;
			slides[idx].classList.remove('is-active');
			idx = (idx + 1) % slides.length;
			var next = slides[idx];
			var img = $('img', next);
			if (img) { img.style.animation = 'none'; void img.offsetWidth; img.style.animation = ''; }
			next.classList.add('is-active');
		}, 6800);
	}

	/* ---------- Tilt & magnetic (fine pointers only) ---------- */
	if (fine && !reduce) {
		$$('[data-tilt],.tilt').forEach(function (el) {
			var max = parseFloat(el.getAttribute('data-tilt')) || (el.classList.contains('tilt-lg') ? 6 : 4);
			el.addEventListener('pointermove', function (e) {
				var r = el.getBoundingClientRect();
				var x = (e.clientX - r.left) / r.width - 0.5;
				var y = (e.clientY - r.top) / r.height - 0.5;
				el.style.transform = 'perspective(900px) rotateX(' + (-y * max).toFixed(2) + 'deg) rotateY(' + (x * max).toFixed(2) + 'deg) translateY(-6px)';
			});
			el.addEventListener('pointerleave', function () { el.style.transform = ''; });
		});
		$$('[data-magnetic],.magnetic').forEach(function (el) {
			el.addEventListener('pointermove', function (e) {
				var r = el.getBoundingClientRect();
				var x = (e.clientX - r.left - r.width / 2) * 0.22;
				var y = (e.clientY - r.top - r.height / 2) * 0.3;
				el.style.transform = 'translate(' + x.toFixed(1) + 'px,' + y.toFixed(1) + 'px)';
			});
			el.addEventListener('pointerleave', function () { el.style.transform = ''; });
		});
	}

	/* ---------- Quotation modal ---------- */
	var modal = $('#quote-modal');
	var lastFocus = null;
	var modalMain = modal ? $('.modal-main', modal) : null;
	var pristine = modalMain ? modalMain.innerHTML : '';
	var onPageForm = $('#quote .kiq-form');

	function prefill(form, product) {
		if (!form || !product) return;
		var f = form.querySelector('[name="product"]');
		if (f) f.value = product;
	}
	function openModal(product) {
		if (!modal) return;
		if (modalMain && $('.kiq-success', modalMain)) {
			modalMain.innerHTML = pristine;
			if (window.kiqInit) window.kiqInit();
		}
		prefill($('.kiq-form', modal), product);
		lastFocus = doc.activeElement;
		modal.hidden = false;
		body.classList.add('modal-open');
		setNav(false);
		var first = $('input:not([type=hidden]):not([tabindex="-1"])', modal);
		setTimeout(function () { if (first) first.focus(); }, 80);
	}
	function closeModal() {
		if (!modal || modal.hidden) return;
		modal.hidden = true;
		body.classList.remove('modal-open');
		if (lastFocus && lastFocus.focus) lastFocus.focus();
	}
	doc.addEventListener('click', function (e) {
		var q = e.target.closest('[data-quote],.ki-quote');
		if (q) {
			var product = q.getAttribute('data-product') || '';
			if (q.classList.contains('ki-quote')) { var a = q.tagName === 'A' ? q : $('a', q); if (a && !a.getAttribute('data-quote')) { a.setAttribute('data-quote', ''); } }
			if (onPageForm) {
				prefill(onPageForm, product);
				setNav(false);
				if (q.getAttribute('href') && q.getAttribute('href').indexOf('#quote') > -1) {
					e.preventDefault();
					$('#quote').scrollIntoView({ behavior: reduce ? 'auto' : 'smooth', block: 'start' });
				}
				return;
			}
			if (modal) { e.preventDefault(); openModal(product); }
			return;
		}
		if (e.target.closest('[data-modal-close]')) closeModal();
	});
	doc.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') { closeModal(); setNav(false); closeLightbox(); }
		if (e.key === 'Tab' && modal && !modal.hidden) {
			var f = $$('a[href], button:not([disabled]), input:not([type=hidden]):not([tabindex="-1"]), select, textarea', modal).filter(function (el) { return el.offsetParent !== null; });
			if (!f.length) return;
			var a = f[0], z = f[f.length - 1];
			if (e.shiftKey && doc.activeElement === a) { e.preventDefault(); z.focus(); }
			else if (!e.shiftKey && doc.activeElement === z) { e.preventDefault(); a.focus(); }
		}
	});
	// Contact page: item passed in the URL (?item=Name).
	try {
		var qp = new URLSearchParams(window.location.search).get('item');
		if (qp && onPageForm) prefill(onPageForm, qp);
	} catch (e) { /* old browser */ }

	/* ---------- Gallery filter & lightbox ---------- */
	var gallery = $('[data-gallery]');
	var lb = $('[data-lightbox]');
	var lbImg = lb ? $('img', lb) : null;
	var lbCap = lb ? $('figcaption', lb) : null;
	var visible = [];
	var cur = 0;

	if (gallery) {
		var items = $$('.g-item', gallery);
		var fb = $('[data-filter-bar]');
		if (fb) {
			fb.addEventListener('click', function (e) {
				var b = e.target.closest('[data-filter]');
				if (!b) return;
				$$('.pill', fb).forEach(function (p) { p.classList.toggle('is-active', p === b); });
				var f = b.getAttribute('data-filter');
				items.forEach(function (it) {
					var show = f === '*' || (' ' + it.getAttribute('data-cats') + ' ').indexOf(' ' + f + ' ') > -1;
					it.hidden = !show;
					if (show) { it.classList.add('is-in', 'is-done'); }
				});
			});
		}
		gallery.addEventListener('click', function (e) {
			var a = e.target.closest('.g-item');
			if (!a || !lb) return;
			e.preventDefault();
			visible = items.filter(function (it) { return !it.hidden; });
			openLightbox(visible.indexOf(a));
		});
	}
	function showLb(i) {
		if (!visible.length) return;
		cur = (i + visible.length) % visible.length;
		var a = visible[cur];
		lbImg.src = a.getAttribute('href');
		lbImg.alt = a.getAttribute('data-title') || '';
		lbCap.textContent = a.getAttribute('data-title') || '';
		var nxt = visible[(cur + 1) % visible.length];
		if (nxt) { var pre = new Image(); pre.src = nxt.getAttribute('href'); }
	}
	function openLightbox(i) {
		lastFocus = doc.activeElement;
		lb.hidden = false;
		body.classList.add('modal-open');
		showLb(i);
		var c = $('[data-lb-close]', lb);
		if (c) c.focus();
	}
	function closeLightbox() {
		if (!lb || lb.hidden) return;
		lb.hidden = true;
		body.classList.remove('modal-open');
		if (lastFocus && lastFocus.focus) lastFocus.focus();
	}
	if (lb) {
		$('[data-lb-close]', lb).addEventListener('click', closeLightbox);
		$('[data-lb-prev]', lb).addEventListener('click', function () { showLb(cur - 1); });
		$('[data-lb-next]', lb).addEventListener('click', function () { showLb(cur + 1); });
		lb.addEventListener('click', function (e) { if (e.target === lb) closeLightbox(); });
		doc.addEventListener('keydown', function (e) {
			if (lb.hidden) return;
			if (e.key === 'ArrowLeft') showLb(cur - 1);
			if (e.key === 'ArrowRight') showLb(cur + 1);
		});
		var sx = 0;
		lb.addEventListener('touchstart', function (e) { sx = e.changedTouches[0].clientX; }, { passive: true });
		lb.addEventListener('touchend', function (e) {
			var dx = e.changedTouches[0].clientX - sx;
			if (Math.abs(dx) > 50) showLb(cur + (dx < 0 ? 1 : -1));
		}, { passive: true });
	}

	onScroll();
})();
