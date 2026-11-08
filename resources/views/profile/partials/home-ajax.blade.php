 @forelse($articles as $post)
        <x-profile.post-card :post="$post" />
    @empty
      <p class="col-span-full py-10 text-center font-display text-lg font-semibold text-muted-foreground">
              No articles yet.
        </p>
 @endforelse