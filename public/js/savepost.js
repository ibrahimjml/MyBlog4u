function save(eo,postId) {

  const icon = eo.querySelector('i.fa-bookmark'); 
  const span = eo.querySelector('span');

  let options = {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ post_id: postId })
  };

  fetch(`/saved-post`, options)
    .then(response => response.json())
    .then(data => {
      if (data.status === 'removed') {
        toastr.success("Bookmark Removed ");
        icon.classList.remove('fas');
        icon.classList.add('far');
        span.textContent = '+';
        eo.classList.remove('text-primary');
        eo.classList.add('text-muted-foreground');
        span.classList.remove('text-primary');
        span.classList.add('text-muted-foreground');

      } else if (data.status === 'added') {
        toastr.options = {
          "progressBar": true,
          "positionClass": "toast-top-right",
          "timeOut": 1000
        };
        toastr.success("Bookmark Added ");
        icon.classList.remove('far');
        icon.classList.add('fas');
        span.textContent = '✓';
        eo.classList.remove('text-muted-foreground');
        eo.classList.add('text-primary');
        span.classList.remove('text-muted-foreground');
        span.classList.add('text-primary');
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
}

