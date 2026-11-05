
  const input = document.getElementById('hashtagInput');
  const tagContainer = document.getElementById('tagContainer');
  const hiddenInput = document.getElementById('hashtagsHidden');

  let tags = window.initialTags;

  function updateTags() {
    tagContainer.innerHTML = '';
    tags.forEach((tag, index) => {
      if(tag){
        const span = document.createElement('span');
        span.className = 'inline-flex items-center gap-1.5 rounded-full border border-border bg-secondary px-2.5 py-1 font-mono text-[11px] text-foreground';
    
        span.innerHTML = `
          <span>#${tag}</span>
          <button type="button" class="text-muted-foreground transition-colors hover:text-destructive" onclick="removeTag(${index})" aria-label="Remove ${tag}">&times;</button>
        `;
        tagContainer.appendChild(span);
      }
    
    });
  
    hiddenInput.value = tags.join(',');
  }

  function removeTag(index) {
    tags.splice(index, 1);
    updateTags();
  }
  
  input.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      const tag = input.value.trim();
      const regexTag = /^[A-Za-z0-9_-]+$/;
  
      if (tag && regexTag.test(tag) && !tags.includes(tag)) {
        input.placeholder = 'Type a hashtag and press Enter';
        tags.push(tag);
        updateTags();
        input.value = '';
  
      } else if (!regexTag.test(tag)) {
        
        setTimeout(()=>{
          input.value = '';
          input.placeholder = 'please type a valid hashtag';
        },50)
      }
    }
  });
  
  // Initial render
  updateTags();

    function addSelectedHashtags() {
    const select = document.getElementById('selectedHashtag');
    const selected = [...select.selectedOptions].map(option => option.value.trim());
  
    selected.forEach(tag => {
      if (tag && !tags.includes(tag)) {
        tags.push(tag);
      }
    });
  
    updateTags();
    select.selectedIndex = -1; // clear selected  after adding
  }


