<div id="bgmodel" class="fixed hidden inset-0 z-50 opacity-0 transition-opacity duration-300 ease-in-out">
  
  <div class="absolute inset-0 bg-gray-900 opacity-60"></div>

  <div id="commentModel" class="relative h-[100vh] w-screen md:w-[40%] overflow-x-hidden bg-white overflow-y-auto shadow-xl translate-x-[-110vw] transition-all duration-300 ease-in-out">
  <span id="closeModel" title="close" class="cursor-pointer absolute top-4 right-4 text-xl"><i class="fas fa-times"></i></span>
    <!-- Comment Form -->
    <p class="w-fit md:text-xl text-md mb-3 p-2 font-semibold">
      Comments 
      <span id="comment-count-number" data-count={{$post->totalcomments_count}}>
        {{'('. $post->totalcomments_count .')'}}
      </span>
    </p>
    <div class="w-full mb-4 p-1 px-5 border-b border-b-gray-200">
      @include('comments.partials.comment_form', ['post' => $post])
    </div>

    <!-- display comments | replies UI -->
     <div id="comments-container" class="w-full">
      @include('comments.comments',['comments'=>$comments])
    </div>
      <!-- Load More comments -->
    @if($comments->hasMorePages())
      <div id="load-more-container" class="text-center my-4">
        <button id="load-more-comments" 
                data-post-id="{{ $post->id }}" 
                data-next-page="{{ $comments->currentPage() + 1 }}"
                class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">
          Load More
        </button>
      </div>
    @endif

    <!-- Loading Spinner -->
    <div id="loading-spinner" class="hidden text-center mt-4">
      <div class="inline-flex items-center">
        <i class="fas fa-spinner fa-spin text-blue-500 mr-2"></i>
        <span>Loading more...</span>
      </div>
    </div>

  </div>
</div>

@push('scripts')
@if($post->allow_comments)
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const opencommentModel = document.getElementById('openModel');
    const closeModel = document.getElementById('closeModel');
    const commentModel = document.getElementById('commentModel');
    const bgmodel = document.getElementById('bgmodel');
  
    opencommentModel.addEventListener('click', () => {
      document.body.classList.add('no-scroll');
  
      bgmodel.classList.remove('hidden');
      setTimeout(() => {
        bgmodel.classList.add('opacity-100');
        bgmodel.classList.remove('opacity-0');

        commentModel.classList.remove('translate-x-[-110vw]');
      commentModel.classList.add('translate-x-[0]');
      }, 10); 
    });
  
    closeModel.addEventListener('click', () => {
      document.body.classList.remove('no-scroll');
  
      bgmodel.classList.remove('opacity-100');
      bgmodel.classList.add('opacity-0');
  
      commentModel.classList.remove('translate-x-[0]');
      commentModel.classList.add('translate-x-[-110vw]');
  

      setTimeout(() => {
        bgmodel.classList.add('hidden');
      }, 300); 
    });
  });
  </script>
@endif
<!-- trigger and Scroll to comment/reply if anchor is present in user activities -->
<script>

window.addEventListener('open-comment-modal', function () {
  const bgmodel = document.getElementById('bgmodel');
  const commentModel = document.getElementById('commentModel');

  if (bgmodel && commentModel) {
    document.body.classList.add('no-scroll');

    bgmodel.classList.remove('hidden');
    setTimeout(() => {
      bgmodel.classList.add('opacity-100');
      bgmodel.classList.remove('opacity-0');

      commentModel.classList.remove('translate-x-[-110vw]');
      commentModel.classList.add('translate-x-[0]');

      const hash = window.location.hash;
      setTimeout(()=>{
       if (hash) {
        const target = document.querySelector(hash);
        if (target) {
        
          let parent = target.parentElement;
           while (parent) {
            if ((parent.classList.contains('reply-content') || parent.classList.contains('nested-replies')) 
                && parent.classList.contains('hidden')) {
              parent.classList.remove('hidden');
            }
            parent = parent.parentElement;
          }
          
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
          target.classList.add('highlight');
          setTimeout(() => target.classList.remove('highlight'), 2000);
        }
      }
      },500)
    
    }, 10);
  }
});


</script>
<!-- dispatch event when comments opened from user activity-->
<script>
document.addEventListener('DOMContentLoaded', () => {

  if (sessionStorage.getItem('showCommentModal') === 'true') {
    window.dispatchEvent(new CustomEvent('open-comment-modal'));
    sessionStorage.removeItem('showCommentModal'); 
  }
});
</script>
<!-- Ajax load more comments -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const loadMoreBtn = document.getElementById('load-more-comments');
    const commentsContainer = document.getElementById('comments-container');
    const loadingSpinner = document.getElementById('loading-spinner');
    const loadMoreContainer = document.getElementById('load-more-container');

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const postId = this.dataset.postId;
            const nextPage = this.dataset.nextPage;

            loadingSpinner.classList.remove('hidden');
            loadMoreContainer.classList.add('hidden');

            fetch(`/posts/${postId}/comments?comments_page=${nextPage}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {

                commentsContainer.insertAdjacentHTML('beforeend', data.html);
                
                if (data.hasMore) {
                    loadMoreBtn.dataset.nextPage = data.nextPage;
                    loadMoreContainer.classList.remove('hidden');
                } else {
                    loadMoreContainer.remove();
                }
            })
            .catch(error => {
                console.error('Error loading more comments:', error);
                loadMoreContainer.classList.remove('hidden');
            })
            .finally(() => {
                loadingSpinner.classList.add('hidden');
            });
        });
    }
});
</script>
@endpush