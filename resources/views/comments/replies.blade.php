@foreach($comments as $reply)
  <div class="reply relative rounded-xl border border-border/80 bg-secondary/40 p-4">
    @include('comments.partials.reply_info', ['reply' => $reply])

    <div class="mt-1">
      <p id="comment-{{ $reply->id }}"
        class="comment-content whitespace-pre-line break-words px-1 text-sm leading-relaxed text-muted-foreground">
        {!! render_mentions($reply->content) !!}
      </p>

      <div class="mt-3 flex flex-wrap items-center justify-end gap-4 border-t border-border/60 pt-3">
        @if($reply->replies_count > 0)
          <button type="button" class="view-all cursor-pointer font-mono text-[10px] uppercase tracking-[0.12em] text-muted-foreground transition-colors hover:text-foreground"
            reply-replies-count="{{ $reply->replies_count }}">
            view {{ $reply->replies_count }} {{ Str::plural('reply', $reply->replies_count) }}
          </button>
        @endif

        @include('comments.partials.delete-edit-comment-model', ['comment' => $reply])

        <button type="button" class="reply-btn cursor-pointer font-mono text-[10px] uppercase tracking-[0.12em] text-primary transition-opacity hover:opacity-75">
          Reply
        </button>
      </div>
    </div>

    @can('edit', $reply)
      @include('comments.partials.edit_form', ['comment' => $reply])
    @endcan

    @include('comments.partials.reply_form', ['comment' => $reply, 'reply' => $reply])

    <div class="nested-replies ml-4 mt-4 hidden border-l-2 border-border pl-4">
      @if($reply->replies->count() > 0)
        @include('comments.replies', ['comments' => $reply->replies])
      @endif
    </div>
  </div>
@endforeach
