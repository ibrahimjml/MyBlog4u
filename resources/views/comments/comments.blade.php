<div id="wrapper" class="w-full">
  @foreach($comments as $comment)
    @if($comment->parent_id == null)
      <article class="comment relative rounded-xl border border-border bg-card p-4">
        @include('comments.partials.comment_info', ['comment' => $comment])

        <div class="mt-1">
          <p id="comment-{{ $comment->id }}"
            class="comment-content whitespace-pre-line break-words px-1 text-sm leading-relaxed text-muted-foreground">
            {!! render_mentions($comment->content) !!}
          </p>

          <div class="mt-3 flex flex-wrap items-center justify-end gap-4 border-t border-border/70 pt-3">
            @if($comment->replies_count > 0)
              <button type="button" class="show-all reply-count cursor-pointer font-mono text-[10px] uppercase tracking-[0.12em] text-muted-foreground transition-colors hover:text-foreground"
                data-reply-count="{{ $comment->replies_count }}">
                view {{ $comment->replies_count }} {{ Str::plural('reply', $comment->replies_count) }}
              </button>
            @endif

            @include('comments.partials.delete-edit-comment-model', ['comment' => $comment])

            <button type="button" class="reply-btn cursor-pointer font-mono text-[10px] uppercase tracking-[0.12em] text-primary transition-opacity hover:opacity-75">
              Reply
            </button>
          </div>
        </div>

        @can('edit', $comment)
          @include('comments.partials.edit_form', ['comment' => $comment])
        @endcan

        @include('comments.partials.reply_form', ['comment' => $comment])

        <div class="reply-content ml-4 mt-4 hidden border-l-2 border-border pl-4" id="wrapper-{{ $comment->id }}">
          @if($comment->replies->count() > 0)
            @include('comments.replies', ['comments' => $comment->replies])
          @endif
        </div>
      </article>
    @endif
  @endforeach

  @include('comments.partials.comment_mentions')
</div>
