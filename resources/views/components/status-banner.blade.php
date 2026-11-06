 @if($post->status === \App\Enums\PostStatus::DRAFT)
    <div class="border-b border-primary/20 bg-primary/10 px-4 py-3 text-sm">
      <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-center gap-x-2 gap-y-1 text-center">
        <span class="inline-flex items-center gap-2 font-mono text-[11px] font-semibold uppercase tracking-[0.18em] text-primary">
          <i class="fas fa-exclamation-triangle" aria-hidden="true"></i> Draft
        </span>
        <span class="text-muted-foreground">This secure URL is only visible to you.</span>
        <a href="{{ route('edit.post', $post->slug) }}"
          class="font-semibold text-primary underline underline-offset-4 hover:opacity-80">Edit to publish</a>
      </div>
    </div>
  @endif

  @if($post->status === \App\Enums\PostStatus::PENDING)
    <div class="border-b border-primary/20 bg-primary/10 px-4 py-3 text-sm">
      <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-center gap-x-2 gap-y-1 text-center">
        <span class="inline-flex items-center gap-2 font-mono text-[11px] font-semibold uppercase tracking-[0.18em] text-primary">
          <i class="fas fa-exclamation-triangle" aria-hidden="true"></i> Pending review
        </span>
        <span class="text-muted-foreground">This secure URL is only visible to you. It will be published when approved.</span>
        <a href="{{ route('dashboard.index') }}"
          class="font-semibold text-primary underline underline-offset-4 hover:opacity-80">Return to dashboard</a>
      </div>
    </div>
  @endif