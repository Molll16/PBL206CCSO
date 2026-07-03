// 1. IMPORTS
import { animate, utils } from 'https://esm.sh/animejs';

// 2. DATA CONFIGURATION
const cardData = [
  { label: 'Security Alerts', color: '#ef4444', icon: '<path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/>', title: 'Live Alerts', desc: 'Instant notifications for any suspicious activities.' },
  { label: 'Agent Status', color: '#22c55e', icon: '<rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/><line x1="6" x2="6.01" y1="6" y2="6"/><line x1="6" x2="6.01" y1="18" y2="18"/>', title: 'All Online', desc: 'Monitor the connection status of all operational agents.', pulse: true },
  { label: 'Top Triggered Rules', color: '#f59e0b', icon: '<path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" x2="4" y1="22" y2="15"/>', title: 'Top Rules', desc: 'The security rules triggered most frequently.' },
  { label: 'Failed Login Monitoring', color: '#ef4444', icon: '<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>', title: 'Failed Login', desc: 'Track and log failed system authentication attempts.' },
  { label: 'System Resources', color: '#6366f1', icon: '<rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M1 9h3M1 15h3M20 9h3M20 15h3"/>', title: 'Resource Usage', desc: 'Real-time tracking of CPU, memory, and disk usage.' },
  { label: 'Network Traffic', color: '#06b6d4', icon: '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>', title: 'Traffic Flow', desc: 'Live visualization of incoming and outgoing network data.' },
  { label: 'Service Status', color: '#22c55e', icon: '<path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>', title: 'Running', desc: 'Real-time operational status of core system services.' },
  { label: 'File Integrity Monitoring', color: '#6366f1', icon: '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15l2 2 4-4"/>', title: 'FIM Active', desc: 'Automatically detect any unauthorized file modifications.' },
  { label: 'Active Connections', color: '#06b6d4', icon: '<path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/>', title: 'Connections', desc: 'A comprehensive list of currently active connections.' },
  { label: 'Firewall Events', color: '#f59e0b', icon: '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>', title: 'Firewall Log', desc: 'Up-to-the-minute records of firewall logs and activities.' },
  { label: 'User Login Activity', color: '#a855f7', icon: '<path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>', title: 'Login History', desc: 'Historical analysis and tracking of user login activities.' },
  { label: 'GeoIP Attack Map', color: '#a855f7', icon: '<circle cx="12" cy="12" r="10"/><line x1="2" x2="22" y1="12" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>', title: 'Attack Map', desc: 'Visual mapping of incoming threat origins based on location.' },
];

function makeSVG(path, color) {
  return `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="${color}" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">${path}</svg>`;
}

// Dibungkus DOMContentLoaded agar script menunggu HTML selesai di-load browser
document.addEventListener('DOMContentLoaded', () => {

  // 3. SCROLL REVEAL LOGIC
  const reveals = document.querySelectorAll('.reveal');
  if (reveals.length > 0) {
    const revealOnScroll = () => {
      const wh = window.innerHeight;
      reveals.forEach((el, i) => {
        const top = el.getBoundingClientRect().top;
        const bot = el.getBoundingClientRect().bottom;
        if (top < wh - 80 && bot > 0) {
          setTimeout(() => el.classList.add('active'), i * 60);
        } else {
          el.classList.remove('active');
        }
      });
    };
    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll();
  }

  // 4. ANIME.JS FEATURE CARDS ANIMATION
  const featCards = document.querySelectorAll('.feat-card');
  const gridSection = document.querySelector('.grid.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-3');
  let isGridInView = false;

  if (featCards.length > 0 && gridSection) {
    const resetCards = () => {
      featCards.forEach(c => { c.style.opacity = '0'; c.style.transform = 'translateY(40px) scale(0.85)'; });
    };
    const runCardAnimation = () => {
      animate(featCards, {
        opacity: [0, 1],
        translateY: (el, i) => [`${50 + (-20 * i)}px`, '0px'],
        scale: (el, i, t) => [(t.length - i) * 0.3 + 0.3, 1],
        rotate: [() => utils.random(-12, 12), 0],
        borderRadius: ['24px', '12px'],
        duration: () => utils.random(1200, 1800),
        delay: (el, i) => i * 130 + utils.random(0, 60),
        ease: 'outElastic(1, .5)',
      });
    };
    const checkGridScroll = () => {
      const rect = gridSection.getBoundingClientRect();
      const now = rect.top < window.innerHeight - 60 && rect.bottom > 0;
      if (now && !isGridInView) { isGridInView = true; runCardAnimation(); }
      if (!now && isGridInView) { isGridInView = false; resetCards(); }
    };
    window.addEventListener('scroll', checkGridScroll, { passive: true });
    setTimeout(checkGridScroll, 150);
  }

  // 5. BUILD DESKTOP CAROUSEL CARDS
  const track = document.getElementById('carouselTrack');
  if (track) {
    cardData.forEach((d, i) => {
      const card = document.createElement('div');
      card.className = 'c-card';
      const pos = i === 0 ? 'center' : i === 1 ? 'right' : 'hidden-right';
      card.setAttribute('data-pos', pos);
      card.setAttribute('data-idx', i);

      const titleRow = d.pulse
        ? `<div style="display:flex;align-items:center;gap:6px;margin-top:auto;">
             <span style="width:7px;height:7px;border-radius:50%;background:#22c55e;display:inline-block;" class="pulse-dot-anim"></span>
             <span style="font-size:18px;font-weight:800;color:#f1f3f9;">${d.title}</span>
           </div>`
        : `<p style="font-size:18px;font-weight:800;line-height:1.3;color:#f1f3f9;margin-top:auto;">${d.title}</p>`;

      card.innerHTML = `<div class="c-card-inner">
        <p style="font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:${d.color};margin-bottom:12px;">${d.label}</p>
        <div style="width:38px;height:38px;border-radius:10px;background:${d.color}1a;border:1px solid ${d.color}40;display:flex;align-items:center;justify-content:center;margin-bottom:12px;">${makeSVG(d.icon, d.color)}</div>
        ${titleRow}
        <p style="font-size:11px;color:#787f99;margin-top:6px;">${d.desc}</p>
      </div>`;
      track.appendChild(card);
    });
  }

  // 6. BUILD MOBILE HORIZONTAL SCROLL CARDS
  const mobileWrap = document.getElementById('mobileCards');
  if (mobileWrap) {
    [cardData, cardData].forEach(set => {
      set.forEach(d => {
        const card = document.createElement('div');
        card.className = 'm-card';
        card.innerHTML = `
          <div class="m-card-label" style="color:${d.color}">${d.label}</div>
          <div class="m-card-icon" style="background:${d.color}1a;border:1px solid ${d.color}40">${makeSVG(d.icon, d.color)}</div>
          <div class="m-card-title">${d.title}</div>
          <div class="m-card-desc">${d.desc}</div>
        `;
        mobileWrap.appendChild(card);
      });
    });

    // 7. MOBILE AUTO-SCROLL LOOPS
    (() => {
      const SPEED = 0.6;
      const RESUME_MS = 900;
      let auto = true;
      let pos = 0;
      let resumeTimer = null;

      const getSetWidth = () => mobileWrap.scrollWidth / 2;

      const tick = () => {
        if (auto) {
          pos += SPEED;
          const setW = getSetWidth();
          if (pos >= setW) pos -= setW;
          mobileWrap.scrollLeft = pos;
        }
        requestAnimationFrame(tick);
      };

      const stopAuto = () => {
        auto = false;
        clearTimeout(resumeTimer);
      };

      const scheduleResume = () => {
        pos = mobileWrap.scrollLeft % getSetWidth();
        resumeTimer = setTimeout(() => { auto = true; }, RESUME_MS);
      };

      mobileWrap.addEventListener('touchstart', stopAuto, { passive: true });
      mobileWrap.addEventListener('touchend', scheduleResume, { passive: true });
      mobileWrap.addEventListener('touchcancel', scheduleResume, { passive: true });

      requestAnimationFrame(tick);
    })();
  }

  // 8. DESKTOP CAROUSEL AUTO-ROTATE
  const carouselCards = Array.from(document.querySelectorAll('.c-card'));
  if (carouselCards.length > 0) {
    (() => {
      const total = carouselCards.length;
      let centerIdx = 0;

      const getPos = (i, center) => {
        const diff = (i - center + total) % total;
        if (diff === 0) return 'center';
        if (diff === 1) return 'right';
        if (diff === total - 1) return 'left';
        if (diff <= Math.floor(total / 2)) return 'hidden-right';
        return 'hidden-left';
      };

      const applyPositions = (center) => {
        carouselCards.forEach((c, i) => c.setAttribute('data-pos', getPos(i, center)));
      };

      applyPositions(centerIdx);
      setInterval(() => { centerIdx = (centerIdx + 1) % total; applyPositions(centerIdx); }, 1300);
    })();
  }
});