const mobilebtn = document.querySelector("#mobile-btn");
const mobilemenu = document.querySelector("#mobile-menu");

const hiddenul = document.getElementById("hiddenul2");
const change = document.getElementById("dropdown");

const notificationTriggers = document.querySelectorAll('[data-notification-trigger]');

// show burger menu (mobile)
if (mobilebtn && mobilemenu) {
  const toggleMobileMenu = (event) => {
    if (event) {
      event.preventDefault();
      event.stopPropagation();
    }

    mobilemenu.classList.toggle('hidden');
  };

  mobilebtn.addEventListener('click', toggleMobileMenu);
  mobilebtn.addEventListener('keydown', (event) => {
    if (event.key === 'Enter' || event.key === ' ') {
      toggleMobileMenu(event);
    }
  });

  document.addEventListener('click', (event) => {
    if (
      !mobilemenu.classList.contains('hidden') &&
      !mobilemenu.contains(event.target) &&
      !mobilebtn.contains(event.target)
    ) {
      mobilemenu.classList.add('hidden');
    }
  });
}

// show user hover menu
if (change && hiddenul) {
  let hideTimer;

  const showMenu = () => {
    clearTimeout(hideTimer);
    hiddenul.style.display = 'block';
  };

  const hideMenu = () => {
    clearTimeout(hideTimer);
    hideTimer = setTimeout(() => {
      if (!change.matches(':hover') && !hiddenul.matches(':hover')) {
        hiddenul.style.display = 'none';
      }
    }, 150);
  };

  change.addEventListener('mouseenter', showMenu);
  change.addEventListener('mouseleave', hideMenu);
  hiddenul.addEventListener('mouseenter', showMenu);
  hiddenul.addEventListener('mouseleave', hideMenu);

  change.addEventListener('click', (event) => {
    if (hiddenul.contains(event.target)) {
      return;
    }

    event.stopPropagation();
    hiddenul.style.display = hiddenul.style.display === 'block' ? 'none' : 'block';
  });

  document.addEventListener('click', (event) => {
    if (!change.contains(event.target) && !hiddenul.contains(event.target)) {
      hiddenul.style.display = 'none';
    }
  });
}

// show notification menu on click/tap and hover
notificationTriggers.forEach((trigger) => {
  const notifications = trigger.nextElementSibling;

  if (!notifications || !notifications.classList.contains('notification-menu')) {
    return;
  }

  const showNotifications = () => {
    notifications.classList.add('is-open');
  };

  const hideNotifications = () => {
    notifications.classList.remove('is-open');
    document.body.classList.remove('no-scroll');
  };

  trigger.addEventListener('click', (event) => {
    const isOpen = notifications.classList.contains('is-open');

    event.stopPropagation();

    document.querySelectorAll('.notification-menu.is-open').forEach((menu) => {
      if (menu !== notifications) {
        menu.classList.remove('is-open');
      }
    });

    notifications.classList.toggle('is-open', !isOpen);
    document.body.classList.toggle('no-scroll', !isOpen);
  });

  if (window.matchMedia('(hover: hover)').matches) {
    trigger.addEventListener('mousemove', showNotifications);

    trigger.addEventListener('mouseout', function () {
      setTimeout(function () {
        if (!notifications.matches(':hover')) {
          hideNotifications();
        }
      }, 300);
    });

    notifications.addEventListener('mousemove', showNotifications);
    notifications.addEventListener('mouseout', hideNotifications);
  }

  document.addEventListener('click', (event) => {
    if (!trigger.contains(event.target) && !notifications.contains(event.target)) {
      hideNotifications();
    }
  });
});
