/* ============================================================
   RS Company Profile - Main JS
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {

  /* ---------- Dark mode toggle ---------- */
  var themeToggle = document.getElementById('themeToggle');
  if (themeToggle) {
    themeToggle.addEventListener('click', function () {
      var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
      var next = isDark ? 'light' : 'dark';
      if (next === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
      } else {
        document.documentElement.removeAttribute('data-theme');
      }
      try { localStorage.setItem('theme', next); } catch (e) {}
    });
  }

  /* ---------- AOS (scroll reveal) ---------- */
  if (window.AOS) {
    AOS.init({
      duration: 800,
      easing: 'ease-out-cubic',
      once: true,
      offset: 90
    });
  }

  /* ---------- Navbar scroll state ---------- */
  var nav = document.getElementById('siteNavbar');
  if (nav) {
    var onScroll = function () {
      if (window.scrollY > 40) {
        nav.classList.add('navbar-scrolled');
      } else {
        nav.classList.remove('navbar-scrolled');
      }
    };
    window.addEventListener('scroll', onScroll);
    onScroll();
  }

  /* ---------- Navbar dropdown on hover (desktop only) ---------- */
  var canHover = window.matchMedia && window.matchMedia('(hover: hover)').matches;
  if (canHover) {
    document.querySelectorAll('.site-navbar .dropdown').forEach(function (dd) {
      var toggle = dd.querySelector('.dropdown-toggle');
      if (!toggle) return;
      dd.addEventListener('mouseenter', function () {
        bootstrap.Dropdown.getOrCreateInstance(toggle).show();
      });
      dd.addEventListener('mouseleave', function () {
        var inst = bootstrap.Dropdown.getInstance(toggle);
        if (inst) inst.hide();
      });
    });
  }

  /* ---------- Back to top ---------- */
  var btt = document.getElementById('backToTop');
  if (btt) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 500) {
        btt.classList.add('show');
      } else {
        btt.classList.remove('show');
      }
    });
    btt.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ---------- Animated counters ---------- */
  var counters = document.querySelectorAll('[data-counter]');
  if (counters.length) {
    var numLocale = (document.documentElement.lang || 'id') === 'en' ? 'en-US' : 'id-ID';
    var animateCounter = function (el) {
      var target = parseFloat(el.getAttribute('data-counter'));
      var suffix = el.getAttribute('data-suffix') || '';
      var duration = 1600;
      var start = null;
      var step = function (ts) {
        if (!start) start = ts;
        var progress = Math.min((ts - start) / duration, 1);
        var eased = 1 - Math.pow(1 - progress, 3);
        var val = Math.round(target * eased);
        el.textContent = val.toLocaleString(numLocale) + suffix;
        if (progress < 1) {
          requestAnimationFrame(step);
        }
      };
      requestAnimationFrame(step);
    };
    if ('IntersectionObserver' in window) {
      var obs = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            animateCounter(entry.target);
            obs.unobserve(entry.target);
          }
        });
      }, { threshold: 0.4 });
      counters.forEach(function (c) { obs.observe(c); });
    } else {
      counters.forEach(animateCounter);
    }
  }

  /* ---------- Auto hide flash alert ---------- */
  var flash = document.getElementById('flashAlert');
  if (flash) {
    setTimeout(function () {
      flash.classList.remove('show');
      setTimeout(function () { flash.remove(); }, 400);
    }, 4000);
  }

  /* ---------- Gallery lightbox ---------- */
  var galleryItems = Array.prototype.slice.call(document.querySelectorAll('.gallery-item'));
  var lbModalEl = document.getElementById('galleryLightbox');
  if (galleryItems.length && lbModalEl) {
    var lbImg = document.getElementById('galleryLbImage');
    var lbTitle = document.getElementById('galleryLbTitle');
    var lbPrev = document.getElementById('galleryLbPrev');
    var lbNext = document.getElementById('galleryLbNext');
    var lbModal = new bootstrap.Modal(lbModalEl);
    var lbCurrent = 0;

    var showLb = function (idx) {
      lbCurrent = (idx + galleryItems.length) % galleryItems.length;
      var item = galleryItems[lbCurrent];
      var imgEl = item.querySelector('img');
      lbImg.src = item.getAttribute('href');
      lbImg.alt = imgEl ? imgEl.alt : '';
      lbTitle.textContent = item.getAttribute('data-caption') || '';
    };

    galleryItems.forEach(function (item, i) {
      item.addEventListener('click', function (ev) {
        ev.preventDefault();
        showLb(i);
        lbModal.show();
      });
    });

    lbPrev.addEventListener('click', function () { showLb(lbCurrent - 1); });
    lbNext.addEventListener('click', function () { showLb(lbCurrent + 1); });
  }

});
