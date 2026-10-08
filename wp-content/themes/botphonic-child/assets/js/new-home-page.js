(function () {
  var root = document.getElementById('bp-new-home');
  if (!root) { return; }

  /* capability tabs */
  var tabs = root.querySelectorAll('.tabs [role=tab]'), panels = root.querySelectorAll('.panel');
  tabs.forEach(function (t, i) {
    t.addEventListener('click', function () { activateTab(i); });
    t.addEventListener('keydown', function (e) {
      var endIdx = tabs.length - 1, next = null;
      if (e.key === 'ArrowRight' || e.key === 'ArrowDown') { next = i === endIdx ? 0 : i + 1; }
      else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') { next = i === 0 ? endIdx : i - 1; }
      else if (e.key === 'Home') { next = 0; }
      else if (e.key === 'End') { next = endIdx; }
      if (next === null) { return; }
      e.preventDefault();
      activateTab(next);
      tabs[next].focus();
    });
  });
  function activateTab(i) {
    tabs.forEach(function (x, idx) { x.setAttribute('aria-selected', idx === i ? 'true' : 'false'); x.tabIndex = idx === i ? 0 : -1; });
    panels.forEach(function (p) { p.classList.remove('is-active'); p.hidden = true; });
    var p = root.querySelector('#' + tabs[i].getAttribute('aria-controls'));
    if (p) { p.hidden = false; p.classList.add('is-active'); }
  }
  tabs.forEach(function (t, i) { t.tabIndex = t.getAttribute('aria-selected') === 'true' ? 0 : -1; });

  var players = root.querySelectorAll('#listen audio');
  players.forEach(function (a) {
    a.addEventListener('play', function () {
      players.forEach(function (b) { if (b !== a && !b.paused) { b.pause(); } });
    });
    a.addEventListener('error', function () {
      var card = a.closest('.audio');
      if (!card || card.querySelector('.audio-error')) { return; }
      var msg = document.createElement('p');
      msg.className = 'audio-error';
      msg.setAttribute('role', 'status');
      msg.textContent = 'This recording could not be loaded. Please try again later.';
      a.insertAdjacentElement('afterend', msg);
    }, true);
  });

  /* trust strip: seamless client-logo marquee */
  initLogoMarquee(root.querySelector('.strip [data-logo-marquee]'));

  function initLogoMarquee(box) {
    if (!box) { return; }
    var track = box.querySelector('.logos');
    if (!track) { return; }
    var originals = Array.prototype.slice.call(track.children);
    if (originals.length < 2) { return; }

    var speed = Math.max(10, parseFloat(box.getAttribute('data-speed')) || 55); /* px per second */
    var motion = window.matchMedia ? window.matchMedia('(prefers-reduced-motion: reduce)') : null;
    var lastWidth = -1, frame = 0;

    function dropClones() {
      var clones = track.querySelectorAll('[data-logo-clone]');
      for (var i = 0; i < clones.length; i++) { track.removeChild(clones[i]); }
    }

    /* One cycle = first logo's left edge to the next copy's left edge. */
    function measureCycle() {
      var first = originals[0].getBoundingClientRect();
      var last = originals[originals.length - 1].getBoundingClientRect();
      var gap = parseFloat(window.getComputedStyle(track).columnGap);
      return (last.right - first.left) + (isFinite(gap) ? gap : 0);
    }

    function addCopies(count) {
      var frag = document.createDocumentFragment();
      for (var c = 0; c < count; c++) {
        for (var i = 0; i < originals.length; i++) {
          var li = originals[i].cloneNode(true);
          li.setAttribute('data-logo-clone', '');
          li.setAttribute('aria-hidden', 'true'); /* duplicates stay out of the a11y tree */
          var img = li.querySelector('img');
          if (img) { img.setAttribute('loading', 'eager'); } /* already cached, avoids gaps */
          frag.appendChild(li);
        }
      }
      track.appendChild(frag);
    }

    function restart() {
      track.style.animationName = 'none';
      void track.offsetWidth;
      track.style.removeProperty('animation-name');
    }

    function stop() {
      box.classList.remove('is-ready');
      dropClones();
      track.style.removeProperty('--bp-logo-shift');
      track.style.removeProperty('--bp-logo-dur');
      lastWidth = -1;
    }

    function build() {
      if (motion && motion.matches) { stop(); return; }
      dropClones();
      box.classList.add('is-ready'); /* switches the row to nowrap before measuring */
      var cycle = measureCycle(), width = box.clientWidth;
      if (!(cycle > 0) || !(width > 0)) { stop(); return; }
      addCopies(Math.max(2, Math.ceil(width / cycle) + 1) - 1);
      track.style.setProperty('--bp-logo-shift', '-' + cycle.toFixed(2) + 'px');
      track.style.setProperty('--bp-logo-dur', Math.max(12, cycle / speed).toFixed(2) + 's');
      restart();
      lastWidth = width;
    }

    function schedule() {
      if (frame) { cancelAnimationFrame(frame); }
      frame = requestAnimationFrame(function () { frame = 0; build(); });
    }

    originals.forEach(function (li) {
      var img = li.querySelector('img');
      if (!img || img.complete) { return; }
      img.addEventListener('load', schedule);
      img.addEventListener('error', schedule);
    });

    if ('ResizeObserver' in window) {
      new ResizeObserver(function () {
        if (Math.abs(box.clientWidth - lastWidth) >= 1) { schedule(); }
      }).observe(box);
    } else {
      window.addEventListener('resize', schedule);
    }

    /* idle while off-screen */
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) { box.classList.toggle('is-paused', !entry.isIntersecting); });
      }).observe(box);
    }

    if (motion && motion.addEventListener) { motion.addEventListener('change', schedule); }
    else if (motion && motion.addListener) { motion.addListener(schedule); }

    schedule();
  }

  /* live call: load only when it scrolls into view */
  var callBox = root.querySelector('#live-call iframe');
  if (callBox && 'IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var src = callBox.getAttribute('data-src');
          if (src) {
            callBox.setAttribute('src', src);
            callBox.removeAttribute('data-src');
          }
          io.disconnect();
        }
      });
    }, { rootMargin: '200px' });
    io.observe(callBox);
  } else if (callBox) {
    var srcFallback = callBox.getAttribute('data-src');
    if (srcFallback) { callBox.setAttribute('src', srcFallback); }
  }
})();