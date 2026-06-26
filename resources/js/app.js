import './bootstrap';
import 'flowbite';
import { initTinyMCE } from './tinymce';
import mediumZoom from 'medium-zoom';
import Prism from 'prismjs';
import 'prismjs/components/prism-core';
import 'prismjs/components/prism-clike';
import 'prismjs/components/prism-javascript';
import 'prismjs/components/prism-markup';
import 'prismjs/components/prism-markup-templating';
import 'prismjs/components/prism-php';
import 'prismjs/components/prism-css';
import 'prismjs/components/prism-json';
import 'prismjs/components/prism-python';
import 'prismjs/components/prism-java';
import 'prismjs/components/prism-c';
import 'prismjs/components/prism-cpp';
import 'prismjs/components/prism-csharp';
import 'prismjs/components/prism-go';
import 'prismjs/components/prism-ruby';
import 'prismjs/plugins/toolbar/prism-toolbar';
import 'prismjs/plugins/toolbar/prism-toolbar.css';
import 'prismjs/plugins/copy-to-clipboard/prism-copy-to-clipboard';

window.addEventListener('DOMContentLoaded', () => {
  initTinyMCE();

  mediumZoom('.published-content img', {
    margin: 50,
    background: '#000',
  }).on('opened', () => {
    document.querySelector('.medium-zoom-overlay').style.zIndex = '50';
    document.querySelector('.medium-zoom-overlay').style.background = 'rgba(0,0,0,0.9)';
    document.querySelector('.medium-zoom-image--opened').style.zIndex = '50';
  });

  const posts = document.querySelectorAll('.published-content');
  posts.forEach(post => {
    if (post.querySelector('.tox-tinymce')) {
      return;
    }
    Prism.highlightAllUnder(post)
  });
});

// websocket
if(window.Echo && window.userId){
  window.Echo.private(`notifications.${userId}`)
    .notification(async(notification) => {
      const response = await fetch(`/notification/render/${notification.id}`);
      const data = await response.json();

      document.querySelectorAll('[data-notification-list]').forEach((list) => {
        list.insertAdjacentHTML('afterbegin', data.html);
      });

      document.querySelectorAll('[data-notification-count]').forEach((countElement) => {
        const count = Number(countElement.textContent.trim()) || 0;
        countElement.textContent = count + 1;
      });
    });
}
