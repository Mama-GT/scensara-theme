/* Scensara UI behaviour: FAQ accordion, prompt tabs, hero video, sticky bag bar. Cart logic is WooCommerce's own. */
(function () {
  'use strict';

  function ready(fn) {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }

  ready(function () {
    // FAQ accordion
    document.querySelectorAll('.faq-question').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var item = btn.closest('.faq-item');
        if (!item) return;
        var open = item.classList.toggle('open');
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
      });
    });

    // Prompt tabs
    var tabs = document.querySelectorAll('.prompt-tab');
    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        var idx = tab.getAttribute('data-prompt');
        tabs.forEach(function (t) {
          var on = t === tab;
          t.classList.toggle('active', on);
          t.setAttribute('aria-selected', on ? 'true' : 'false');
        });
        document.querySelectorAll('[data-prompt-panel]').forEach(function (p) {
          p.hidden = p.getAttribute('data-prompt-panel') !== idx;
        });
      });
    });

    // Optional hero video: skip for reduced motion / data saver, otherwise fade in once playing.
    var video = document.querySelector('.hero-video');
    if (video) {
      var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      var saver = navigator.connection && navigator.connection.saveData;
      if (reduce || saver) {
        video.parentNode.removeChild(video);
      } else {
        video.src = video.getAttribute('data-src');
        video.addEventListener('playing', function () { video.classList.add('is-playing'); });
        var p = video.play();
        if (p && p.catch) p.catch(function () {});
      }
    }

    // Sticky add-to-bag bar: show once the main button has scrolled out of view.
    var bar = document.getElementById('sticky-bag');
    var target = document.querySelector('form.cart .single_add_to_cart_button, .product .single_add_to_cart_button');
    if (bar && target && 'IntersectionObserver' in window) {
      var link = bar.querySelector('a.btn');
      new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
          var show = !e.isIntersecting && e.boundingClientRect.top < 0;
          bar.classList.toggle('is-visible', show);
          bar.setAttribute('aria-hidden', show ? 'false' : 'true');
          if (link) link.tabIndex = show ? 0 : -1;
        });
      }).observe(target);
    }
  });
})();
