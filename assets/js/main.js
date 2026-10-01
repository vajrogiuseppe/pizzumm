/* Pizzumm — comportamenti di base (indipendenti da GSAP) */
(function () {
  'use strict';

  /* ---------- Menu mobile ---------- */
  var toggle = document.querySelector('.nav-toggle');
  var nav = document.getElementById('site-nav');

  function closeNav() {
    if (!toggle || !nav) return;
    toggle.setAttribute('aria-expanded', 'false');
    nav.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!open));
      nav.classList.toggle('is-open', !open);
      document.body.style.overflow = !open ? 'hidden' : '';
    });

    nav.addEventListener('click', function (e) { if (e.target.closest('a')) closeNav(); });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && nav.classList.contains('is-open')) { closeNav(); toggle.focus(); }
    });

    window.addEventListener('resize', function () { if (window.innerWidth > 960) closeNav(); });
  }

  /* ---------- Header allo scroll ---------- */
  var header = document.querySelector('.site-header');
  if (header) {
    var onScroll = function () { header.classList.toggle('is-scrolled', window.scrollY > 32); };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* ---------- Categoria attiva nella pagina Menu ---------- */
  var menuNav = document.querySelector('.menu-nav');
  if (menuNav && 'IntersectionObserver' in window) {
    var links = Array.prototype.slice.call(menuNav.querySelectorAll('a[href^="#"]'))
      .filter(function (a) { return a.getAttribute('href').length > 1; });
    var sections = links.map(function (a) {
      try { return document.querySelector(a.getAttribute('href')); } catch (e) { return null; }
    }).filter(Boolean);

    if (sections.length) {
      var spy = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          links.forEach(function (a) {
            var active = a.getAttribute('href') === '#' + entry.target.id;
            a.classList.toggle('is-active', active);
            if (active) a.setAttribute('aria-current', 'true');
            else a.removeAttribute('aria-current');
          });
        });
      }, { rootMargin: '-45% 0px -50% 0px' });
      sections.forEach(function (s) { spy.observe(s); });
    }
  }

  /* ---------- Video hero: pausa fuori vista e risparmio dati ---------- */
  var heroVideo = document.querySelector('.hero__bg video');
  if (heroVideo) {
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var saveData = navigator.connection && navigator.connection.saveData;

    if (reduce || saveData) {
      heroVideo.removeAttribute('autoplay');
      heroVideo.pause();
    } else if ('IntersectionObserver' in window) {
      var vio = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            var p = heroVideo.play();
            if (p && p.catch) p.catch(function () {});
          } else { heroVideo.pause(); }
        });
      }, { threshold: 0.15 });
      vio.observe(heroVideo);
    }
  }

  /* ---------- Mappa: caricamento solo su richiesta ---------- */
  var mapButton = document.querySelector('[data-map-load]');
  if (mapButton) {
    mapButton.addEventListener('click', function () {
      var box = mapButton.closest('[data-map-consent]');
      if (!box) return;

      var iframe = document.createElement('iframe');
      iframe.src = box.getAttribute('data-map-src');
      iframe.title = 'Mappa: dove siamo';
      iframe.loading = 'lazy';
      iframe.referrerPolicy = 'no-referrer-when-downgrade';
      iframe.setAttribute('allowfullscreen', '');
      box.replaceWith(iframe);
    });
  }

  /* ---------- Preferenze cookie ---------- */
  var prefsButton = document.querySelector('[data-cookie-prefs]');
  var prefsTarget = (window.pizzummData && window.pizzummData.cookiePrefs) || '';
  if (prefsButton && prefsTarget) {
    prefsButton.addEventListener('click', function () {
      var target = document.querySelector(prefsTarget);
      if (target) target.click();
    });
  }

  /* ---------- Caroselli "storie": barrette di avanzamento ---------- */
  var storyRails = document.querySelectorAll('[data-stories]');

  storyRails.forEach(function (rail) {
    var items = rail.children;
    if (items.length < 2) return;

    var bar = document.createElement('ul');
    bar.className = 'stories-bar';
    bar.setAttribute('aria-hidden', 'true');

    for (var i = 0; i < items.length; i++) {
      bar.insertAdjacentHTML('beforeend', '<li><span></span></li>');
    }

    rail.insertAdjacentElement('beforebegin', bar);

    var segments = bar.children;

    function update() {
      var center = rail.scrollLeft + rail.clientWidth / 2;
      var active = 0;
      var best = Infinity;

      for (var i = 0; i < items.length; i++) {
        var mid = items[i].offsetLeft + items[i].offsetWidth / 2;
        var d = Math.abs(mid - center);
        if (d < best) { best = d; active = i; }
      }

      for (var j = 0; j < segments.length; j++) {
        segments[j].classList.toggle('is-on', j <= active);
      }
    }

    rail.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
    update();
  });

  /* ---------- Anno corrente nel footer ---------- */
  var year = document.querySelector('[data-current-year]');
  if (year) year.textContent = String(new Date().getFullYear());
})();
