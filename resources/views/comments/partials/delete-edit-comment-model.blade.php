@php
  $target = isset($reply) ? $reply : $comment;
@endphp

<button type="button" title="Comment options"
  class="opencommentmodel-btn absolute right-3 top-3 z-10 inline-flex h-8 w-8 items-center justify-center rounded-full text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
  <i class="fas fa-ellipsis-v text-xs"></i>
</button>

<div class="commentmodel absolute right-3 top-12 z-20 hidden w-40 space-y-0.5 rounded-xl border border-border bg-card p-1.5 shadow-xl">
  @can('edit', $target)
    <button type="button"
      class="edit-btn block w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-foreground transition-colors hover:bg-secondary">
      Edit
    </button>
  @endcan

  @can('delete', $target)
    @include('comments.partials.delete_form', ['comment' => $target])
  @endcan

  @can('report', $target)
    <button type="button" onclick="openReport('reportsmodel-{{ $target->id }}')"
      class="block w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-foreground transition-colors hover:bg-secondary">
      Report
    </button>
  @endcan

  @include('comments.partials.report-model', ['comment' => $target, 'id' => "reportsmodel-{$target->id}"])
</div>
