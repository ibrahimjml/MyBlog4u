@php
  $canReport = auth()->user()->can('report', $post);
  $canFollow = auth()->user()->isNot($post->user);
  $followStatus = $authFollowings[$post->user_id] ?? null;
  $status = isset($followStatus)
    ? \App\Enums\FollowerStatus::tryFrom((int) $followStatus)
    : null;
@endphp

<div id="moremodel"
  class="absolute -top-28 right-0 z-20 hidden h-fit w-40 space-y-0.5 rounded-xl border border-border bg-card p-1.5 shadow-xl">
  @if($canFollow)
    <button type="button" data-id="{{ $post->user->id }}" onclick="follows(this)"
      class="block w-full rounded-lg px-3 py-2 text-left text-sm font-medium transition-colors hover:bg-secondary
        {{ is_null($status) ? 'text-primary' : ($status === \App\Enums\FollowerStatus::PENDING ? 'text-muted-foreground' : 'text-destructive') }}">
      {{ is_null($status) ? 'Follow' : ($status === \App\Enums\FollowerStatus::PENDING ? 'Requested' : 'Unfollow') }}
    </button>
  @endif

  @if($canReport)
    <button type="button" onclick="openReort()"
      class="block w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-foreground transition-colors hover:bg-secondary">
      Report
    </button>
  @endif
</div>

@push('scripts')
  <script>
    const OpenMoreModel = document.getElementById('openmoremodel');
    const moreModel = document.getElementById('moremodel');

    if (OpenMoreModel && moreModel) {
      OpenMoreModel.addEventListener('click', () => {
        moreModel.classList.toggle('hidden');
      });
    }
  </script>
@endpush
