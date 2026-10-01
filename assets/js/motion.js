/* Pizzumm — motion (GSAP + ScrollTrigger + SplitText) */
(function () {
  'use strict';

  if (!window.gsap) return;

  var gsap = window.gsap;
  var ST = window.ScrollTrigger;
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (ST) gsap.registerPlugin(ST);
  if (window.SplitText) gsap.registerPlugin(window.SplitText);

  document.documentElement.classList.add('js-gsap');

  if (reduce) {
    gsap.set('[data-anim]', { opacity: 1, clearProps: 'all' });
    gsap.utils.toArray('[data-split]').forEach(function (el) { el.classList.add('is-split'); });
    return;
  }

  var EASE = 'power3.out';

  /* =======================================================
     1. TITOLI — rivelazione a maschera, riga per riga
     ======================================================= */
  function splitHeadings() {
    gsap.utils.toArray('[data-split]').forEach(function (el) {
      if (!window.SplitText) {
        el.classList.add('is-split');
        gsap.from(el, {
          opacity: 0, y: 26, duration: 0.8, ease: EASE,
          scrollTrigger: { trigger: el, start: 'top 88%', once: true }
        });
        return;
      }

      window.SplitText.create(el, {
        type: 'lines',
        linesClass: 'line',
        mask: 'lines',
        autoSplit: true,
        onSplit: function (self) {
          el.classList.add('is-split');
          return gsap.from(self.lines, {
            yPercent: 110,
            duration: 1,
            ease: 'expo.out',
            stagger: 0.09,
            scrollTrigger: { trigger: el, start: 'top 88%', once: true }
          });
        }
      });
    });
  }

  /* =======================================================
     2. Elementi generici
     ======================================================= */
  function revealElements() {
    gsap.utils.toArray('[data-anim]').forEach(function (el) {
      gsap.fromTo(el,
        { opacity: 0, y: 24 },
        {
          opacity: 1, y: 0, duration: 0.85, ease: EASE,
          delay: parseFloat(el.getAttribute('data-anim-delay') || 0),
          scrollTrigger: { trigger: el, start: 'top 90%', once: true }
        });
    });

    gsap.utils.toArray('[data-anim-group]').forEach(function (group) {
      if (!group.children.length) return;
      gsap.fromTo(group.children,
        { opacity: 0, y: 28 },
        {
          opacity: 1, y: 0, duration: 0.75, ease: EASE, stagger: 0.09,
          scrollTrigger: { trigger: group, start: 'top 88%', once: true }
        });
    });
  }

  /* =======================================================
     3. Hero — parallasse leggera
     ======================================================= */
  function heroParallax() {
    var bg = document.querySelector('.hero__bg');
    if (!bg || !ST) return;

    gsap.to(bg, {
      yPercent: 12, ease: 'none',
      scrollTrigger: { trigger: '.hero', start: 'top top', end: 'bottom top', scrub: true }
    });
  }

  /* =======================================================
     4. Card Menu / Glovo — ruotate in senso opposto.
        Allo hover quella puntata si raddrizza e sale.
     ======================================================= */
  function stackedCards() {
    gsap.utils.toArray('[data-stack]').forEach(function (stack) {
      var a = stack.querySelector('.stack__card--a');
      var b = stack.querySelector('.stack__card--b');
      if (!a || !b) return;

      var desktop = window.matchMedia('(min-width: 821px)');
      var REST = { a: { rotate: -7, yPercent: 2 }, b: { rotate: 7, yPercent: -2 } };

      function apply(front, instant) {
        if (!desktop.matches) {
          gsap.set([a, b], { clearProps: 'transform' });
          return;
        }

        var isB = front === 'b';
        var isA = front === 'a';
        var none = !isA && !isB;
        stack.classList.toggle('is-b-front', isB);

        var stateA = none
          ? { rotate: REST.a.rotate, yPercent: REST.a.yPercent, scale: 1 }
          : { rotate: isA ? 0 : REST.a.rotate - 3, yPercent: isA ? -4 : 6, scale: isA ? 1.03 : 0.96 };

        var stateB = none
          ? { rotate: REST.b.rotate, yPercent: REST.b.yPercent, scale: 1 }
          : { rotate: isB ? 0 : REST.b.rotate + 3, yPercent: isB ? -4 : 6, scale: isB ? 1.03 : 0.96 };

        if (instant) {
          gsap.set(a, stateA);
          gsap.set(b, stateB);
          return;
        }

        gsap.to(a, Object.assign({ duration: 0.55, ease: 'power3.out' }, stateA));
        gsap.to(b, Object.assign({ duration: 0.55, ease: 'power3.out' }, stateB));
      }

      apply('rest', true);

      ['mouseenter', 'focusin'].forEach(function (evt) {
        a.addEventListener(evt, function () { apply('a'); });
        b.addEventListener(evt, function () { apply('b'); });
      });
      stack.addEventListener('mouseleave', function () { apply('rest'); });

      desktop.addEventListener('change', function () { apply('rest', true); });

      if (ST) {
        gsap.fromTo([a, b],
          { opacity: 0 },
          {
            opacity: 1, duration: 0.8, ease: EASE, stagger: 0.14,
            scrollTrigger: { trigger: stack, start: 'top 84%', once: true }
          });
      }
    });
  }

  /* =======================================================
     5. Sezione pinnata con scorrimento laterale e indicatore
     ======================================================= */
  function lateralPin() {
    if (!ST) return;

    gsap.utils.toArray('[data-pin]').forEach(function (pin) {
      var viewport = pin.querySelector('.pin__viewport');
      var track = pin.querySelector('.pin__track');
      var bar = pin.querySelector('.pin__bar i');
      var count = pin.querySelector('.pin__count');
      if (!viewport || !track) return;

      var items = gsap.utils.toArray(track.children);
      var trigger = null;

      function build() {
        if (trigger) { trigger.kill(true); trigger = null; }
        gsap.set(track, { clearProps: 'transform' });
        pin.classList.remove('is-pinned');

        // Sotto i 900px si usa lo scorrimento nativo con snap.
        if (window.innerWidth < 900) {
          if (bar) gsap.set(bar, { scaleX: 0 });
          return;
        }

        var distance = track.scrollWidth - viewport.clientWidth;
        if (distance < 40) return;

        pin.classList.add('is-pinned');

        var tween = gsap.to(track, {
          x: -distance, ease: 'none',
          scrollTrigger: {
            trigger: pin,
            start: 'top top',
            end: function () { return '+=' + (distance + window.innerHeight * 0.5); },
            pin: true,
            scrub: 0.6,
            anticipatePin: 1,
            invalidateOnRefresh: true,
            onUpdate: function (self) {
              if (bar) gsap.set(bar, { scaleX: self.progress });
              if (count) {
                var current = Math.min(items.length, Math.round(self.progress * (items.length - 1)) + 1);
                count.textContent = String(current).padStart(2, '0') + ' / ' + String(items.length).padStart(2, '0');
              }
            }
          }
        });

        trigger = tween.scrollTrigger;
      }

      build();
      ST.addEventListener('refreshInit', build);
    });
  }

  /* =======================================================
     6. Pila di card ruotate — si sfogliano durante lo scroll
     ======================================================= */
  function cardStack() {
    if (!ST) return;

    gsap.utils.toArray('[data-cardstack]').forEach(function (stack) {
      var cards = gsap.utils.toArray(stack.querySelectorAll('.cardstack__card'));
      var bar = stack.querySelector('.pin__bar i');
      var count = stack.querySelector('.pin__count');
      if (cards.length < 2) return;

      var trigger = null;

      function build() {
        if (trigger) { trigger.kill(true); trigger = null; }
        gsap.set(cards, { clearProps: 'all' });
        stack.classList.remove('is-active');

        if (window.innerWidth < 900) {
          if (bar) gsap.set(bar, { scaleX: 0 });
          return;
        }

        stack.classList.add('is-active');

        // Stato iniziale: pila leggermente sfalsata e ruotata.
        cards.forEach(function (card, i) {
          gsap.set(card, {
            zIndex: cards.length - i,
            rotate: gsap.utils.wrap([-6, 4, -3, 7, -5, 3], i),
            xPercent: -50,
            yPercent: -50,
            x: i * 3,
            y: i * -14,
            scale: 1 - i * 0.02,
            opacity: i < 4 ? 1 : 0
          });
        });

        var section = stack.closest('.section') || stack;

        var tl = gsap.timeline({
          scrollTrigger: {
            trigger: section,
            start: 'top top',
            end: '+=' + (cards.length * 300),
            pin: section,
            scrub: 0.7,
            anticipatePin: 1,
            invalidateOnRefresh: true,
            onUpdate: function (self) {
              if (bar) gsap.set(bar, { scaleX: self.progress });
              if (count) {
                var current = Math.min(cards.length, Math.floor(self.progress * cards.length) + 1);
                count.textContent = String(current).padStart(2, '0') + ' / ' + String(cards.length).padStart(2, '0');
              }
            }
          }
        });

        // Ogni card esce di scena scoprendo la successiva.
        cards.forEach(function (card, i) {
          if (i === cards.length - 1) return;

          tl.to(card, {
            yPercent: -160,
            rotate: gsap.utils.wrap([-22, 18], i),
            opacity: 0,
            ease: 'power2.inOut'
          }, i);

          // Le card sotto risalgono di un gradino.
          tl.to(cards.slice(i + 1), {
            x: function (index) { return index * 3; },
            y: function (index) { return index * -14; },
            scale: function (index) { return 1 - index * 0.02; },
            opacity: 1,
            ease: 'power2.out'
          }, i);
        });

        trigger = tl.scrollTrigger;
      }

      build();
      ST.addEventListener('refreshInit', build);
    });
  }

  /* =======================================================
     7. Caroselli con frecce (dove non c'è il pin)
     ======================================================= */
  function rails() {
    gsap.utils.toArray('[data-rail]').forEach(function (rail) {
      var wrap = rail.closest('[data-rail-wrap]') || rail.parentElement;
      var prev = wrap ? wrap.querySelector('[data-rail-prev]') : null;
      var next = wrap ? wrap.querySelector('[data-rail-next]') : null;

      function step() {
        var first = rail.firstElementChild;
        return first ? first.getBoundingClientRect().width + 16 : rail.clientWidth * 0.8;
      }

      function update() {
        var max = rail.scrollWidth - rail.clientWidth;
        if (prev) prev.disabled = rail.scrollLeft < 8;
        if (next) next.disabled = rail.scrollLeft > max - 8;
      }

      if (prev) prev.addEventListener('click', function () { rail.scrollBy({ left: -step(), behavior: 'smooth' }); });
      if (next) next.addEventListener('click', function () { rail.scrollBy({ left: step(), behavior: 'smooth' }); });

      rail.addEventListener('scroll', update, { passive: true });
      window.addEventListener('resize', update);
      update();
    });
  }

  /* =======================================================
     8. Nastro — rallenta allo hover
     ======================================================= */
  function ticker() {
    var track = document.querySelector('.ticker__track');
    if (!track) return;
    var wrap = track.parentElement;
    wrap.addEventListener('mouseenter', function () { track.style.animationPlayState = 'paused'; });
    wrap.addEventListener('mouseleave', function () { track.style.animationPlayState = 'running'; });
  }

  function init() {
    heroParallax();
    splitHeadings();
    revealElements();
    ticker();
    if (ST) ST.refresh();
  }

  if (document.fonts && document.fonts.ready) {
    document.fonts.ready.then(init);
  } else {
    window.addEventListener('load', init);
  }
})();
