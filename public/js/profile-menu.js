function closeProfileMenus() {
  document.querySelectorAll('.profile-menu.open').forEach((menu) => {
    menu.classList.remove('open');
    const trigger = menu.querySelector('.js-profile-trigger');
    if (trigger) {
      trigger.setAttribute('aria-expanded', 'false');
    }
  });
}

function togglePasswordModal(open) {
  const modal = document.getElementById('password-modal');
  if (!modal) {
    return;
  }

  modal.classList.toggle('show', open);
}

document.addEventListener('click', (event) => {
  const profileTrigger = event.target.closest('.js-profile-trigger');
  const openPasswordBtn = event.target.closest('.js-open-password-modal');
  const closePasswordBtn = event.target.closest('.js-close-password-modal');
  const passwordOverlay = event.target.classList.contains('password-modal-overlay');
  const profileMenu = event.target.closest('.profile-menu');

  if (profileTrigger) {
    event.preventDefault();
    event.stopPropagation();

    const menu = profileTrigger.closest('.profile-menu');
    if (!menu) {
      return;
    }

    const isOpen = menu.classList.contains('open');
    closeProfileMenus();

    if (!isOpen) {
      menu.classList.add('open');
      profileTrigger.setAttribute('aria-expanded', 'true');
    }

    return;
  }

  if (openPasswordBtn) {
    event.preventDefault();
    closeProfileMenus();
    togglePasswordModal(true);
    return;
  }

  if (closePasswordBtn || passwordOverlay) {
    event.preventDefault();
    togglePasswordModal(false);
    return;
  }

  if (!profileMenu) {
    closeProfileMenus();
  }
});

document.addEventListener('keydown', (event) => {
  if (event.key === 'Escape') {
    closeProfileMenus();
    togglePasswordModal(false);
  }
});

document.addEventListener('submit', (event) => {
  if (event.target.classList.contains('password-modal-form')) {
    togglePasswordModal(false);
  }
});
