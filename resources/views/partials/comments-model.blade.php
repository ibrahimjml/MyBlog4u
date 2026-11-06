<div id="bgmodel" class="fixed inset-0 z-[70] hidden opacity-0 transition-opacity duration-300 ease-in-out">
  <div id="commentModelOverlay" class="absolute inset-0 bg-foreground/50 backdrop-blur-sm"></div>

  <div id="commentModel"
    class="relative ml-auto flex h-full w-full max-w-md translate-x-full flex-col overflow-hidden border-l border-border bg-background shadow-2xl transition-transform duration-300 ease-in-out">
    <header class="flex shrink-0 items-start justify-between gap-4 border-b border-border px-5 py-4">
      <div>
        <p class="font-mono text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Discussion</p>
        <h2 class="mt-1 font-display text-xl font-semibold text-foreground">
          Comments
          <span id="comment-count-number" data-count="{{ $post->totalcomments_count }}"
            class="font-mono text-sm font-normal text-muted-foreground">{{ '(' . $post->totalcomments_count . ')' }}</span>
        </h2>
      </div>

      <button id="closeModel" type="button" title="Close comments"
        class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
        <i class="fas fa-times"></i>
      </button>
    </header>

    <div class="flex-1 overflow-y-auto overscroll-contain px-5 py-5">
      <div class="rounded-2xl border border-border bg-card p-4">
        @include('comments.partials.comment_form', ['post' => $post])
      </div>

      <div id="comments-container" class="mt-6 space-y-4">
        @include('comments.comments', ['comments' => $comments])
      </div>

      <x-load-more button-id="load-more-comments" container="comments-container"
        :route="route('posts.comments.load', $post)" page-name="comments_page"
        :current-page="$comments->currentPage()" :has-more="$comments->hasMorePages()"
        button-text="Load More Comments" />
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
        const overlay = document.getElementById('commentModelOverlay');

        if (!commentModel || !bgmodel) return;

        const openDrawer = () => {
          document.body.classList.add('no-scroll');

          bgmodel.classList.remove('hidden');
          requestAnimationFrame(() => {
            bgmodel.classList.add('opacity-100');
            bgmodel.classList.remove('opacity-0');

            commentModel.classList.remove('translate-x-full');
            commentModel.classList.add('translate-x-0');
          });
        };

        const closeDrawer = () => {
          document.body.classList.remove('no-scroll');

          bgmodel.classList.remove('opacity-100');
          bgmodel.classList.add('opacity-0');

          commentModel.classList.remove('translate-x-0');
          commentModel.classList.add('translate-x-full');

          setTimeout(() => {
            bgmodel.classList.add('hidden');
          }, 300);
        };

        if (opencommentModel) {
          opencommentModel.addEventListener('click', openDrawer);
        }

        if (closeModel) {
          closeModel.addEventListener('click', closeDrawer);
        }

        if (overlay) {
          overlay.addEventListener('click', closeDrawer);
        }

        document.addEventListener('keydown', event => {
          if (event.key === 'Escape' && !bgmodel.classList.contains('hidden')) {
            closeDrawer();
          }
        });
      });
    </script>
  @endif

  <script>
    window.addEventListener('open-comment-modal', function() {
      const bgmodel = document.getElementById('bgmodel');
      const commentModel = document.getElementById('commentModel');

      if (!bgmodel || !commentModel) return;

      document.body.classList.add('no-scroll');

      bgmodel.classList.remove('hidden');
      requestAnimationFrame(() => {
        bgmodel.classList.add('opacity-100');
        bgmodel.classList.remove('opacity-0');

        commentModel.classList.remove('translate-x-full');
        commentModel.classList.add('translate-x-0');

        const hash = window.location.hash;
        setTimeout(() => {
          if (!hash) return;

          const target = document.querySelector(hash);
          if (!target) return;

          let parent = target.parentElement;
          while (parent) {
            if ((parent.classList.contains('reply-content') || parent.classList.contains('nested-replies')) &&
              parent.classList.contains('hidden')) {
              parent.classList.remove('hidden');
            }
            parent = parent.parentElement;
          }

          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
          target.classList.add('highlight');
          setTimeout(() => target.classList.remove('highlight'), 2000);
        }, 500);
      });
    });

    document.addEventListener('DOMContentLoaded', () => {
      if (sessionStorage.getItem('showCommentModal') === 'true') {
        window.dispatchEvent(new CustomEvent('open-comment-modal'));
        sessionStorage.removeItem('showCommentModal');
      }
    });
  </script>
@endpush
