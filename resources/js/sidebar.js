// OPEN SIDEBAR
window.openSidebar = function () {
  const sidebar = document.getElementById('sidebar');
  const backdrop = document.getElementById('sidebar-backdrop');

  if (!sidebar || !backdrop) return;

  sidebar.classList.remove('-translate-x-full');
  sidebar.classList.add('translate-x-0');

  backdrop.classList.remove('opacity-0', 'pointer-events-none');
  backdrop.classList.add('opacity-100');
};

// CLOSE SIDEBAR
window.closeSidebar = function () {
  const sidebar = document.getElementById('sidebar');
  const backdrop = document.getElementById('sidebar-backdrop');

  if (!sidebar || !backdrop) return;

  sidebar.classList.add('-translate-x-full');
  sidebar.classList.remove('translate-x-0');

  backdrop.classList.add('opacity-0', 'pointer-events-none');
  backdrop.classList.remove('opacity-100');
};

// TOGGLE SUBMENU
window.toggleSubmenu = function (menuId, chevronId) {
  const menu = document.getElementById(menuId);
  const chevron = document.getElementById(chevronId);

  if (!menu || !chevron) return;

  menu.classList.toggle('hidden');
  chevron.classList.toggle('rotate-180');
};

// TOGGLE MANAGE MENU
window.toggleManage = function () {
  const menu = document.getElementById('manage-menu');
  const arrow = document.getElementById('arrow-manage');

  if (!menu || !arrow) return;

  menu.classList.toggle('hidden');
  arrow.classList.toggle('rotate-180');
};

// CLICK OUTSIDE (CLOSE MANAGE MENU)
document.addEventListener('click', function (e) {
  const menu = document.getElementById('manage-menu');
  if (!menu) return;

  const btn = menu.previousElementSibling;

  if (
    btn &&
    !btn.contains(e.target) &&
    !menu.contains(e.target)
  ) {
    menu.classList.add('hidden');

    const arrow = document.getElementById('arrow-manage');
    if (arrow) arrow.classList.remove('rotate-180');
  }
});