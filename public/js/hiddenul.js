const mobilebtn = document.querySelector("#mobile-btn");
const mobilemenu = document.querySelector("#mobile-menu");

const hiddenul = document.getElementById("hiddenul2");
const change = document.getElementById("dropdown");

const notificationTriggers = document.querySelectorAll('[data-notification-trigger]');
let   hideTimeout;
// show burger menu
if(mobilebtn){
mobilebtn.addEventListener('click', (e) => {
  
  mobilemenu.classList.toggle('hidden');

  if (!mobilemenu.classList.contains('hidden')) {
      const closeMenu = (event) => {

        if (!mobilemenu.contains(event.target) && !mobilebtn.contains(event.target)) {
          mobilemenu.classList.add('hidden');
          document.removeEventListener('click', closeMenu);
        }
      };

      document.addEventListener('click', closeMenu);

  }
});
}


// show hover menu user info
if(change){
  change.addEventListener("mousemove",(eo) => {
    hiddenul.style.display="block";
    });
    change.addEventListener("mouseout",(eo) => {
      setTimeout(function() {
        if (!hiddenul.matches(':hover')) {
          hiddenul.style.display = "none";
        }
      }, 300); 
    });

    change.addEventListener("mousemove",(eo) => {
      hiddenul.style.display="block";
      });
      
      change.addEventListener("mouseout",(eo) => {
      hiddenul.style.display = "none";
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
    console.log("Notification menu toggled");
  });

  if (window.matchMedia('(hover: hover)').matches) {
    trigger.addEventListener('mousemove', showNotifications);

    trigger.addEventListener('mouseout', function() {
      setTimeout(function() {
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
