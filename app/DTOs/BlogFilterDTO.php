<?php

namespace App\DTOs;

use App\Enums\Blog\BlogSort;
use App\Enums\Blog\BlogTab;
use App\Http\Requests\App\FilterRequest;
use Carbon\Carbon;
use Carbon\CarbonInterface;

final class BlogFilterDTO
{
    public function __construct(
        public readonly BlogTab $tab,
        public readonly BlogSort $sort,
        public readonly ?string $from = null,
        public readonly ?string $to = null,
    ) {
    }

    public static function fromRequest(FilterRequest $request): self
    {
        $validated = $request->validated();

        $tab  = $request->resolvedTab();
        $sort = BlogSort::tryFrom((string) ($validated['sort'] ?? ''))
            ?? $tab->defaultSort();

        return new self(
            tab:  $tab,
            sort: self::resolveSort($tab, $sort),
            from: $validated['from'] ?? null,
            to:   $validated['to']   ?? null,
        );
    }

    public static function resolveSort(BlogTab $tab, BlogSort $sort): BlogSort
    {
        return in_array($sort, $tab->sorts(), true)
            ? $sort
            : $tab->defaultSort();
    }

    public function resolvedSort(): BlogSort
    {
        return self::resolveSort($this->tab, $this->sort);
    }

    public function fromDate(): ?CarbonInterface
    {
        return $this->from ? Carbon::parse($this->from)->startOfDay() : null;
    }

    public function toDate(): ?CarbonInterface
    {
        return $this->to ? Carbon::parse($this->to)->endOfDay() : null;
    }

    /**
     * Canonical query
     */
    public function query(): array
    {
        $sort = $this->resolvedSort();

        return array_filter([
            'tab'  => $this->tab === BlogTab::FOR_YOU 
                              ? null 
                              : $this->tab->value,
            'sort' => $sort === $this->tab->defaultSort()
                              ? null 
                              : $sort->value,
            'from' => $this->fromDate()?->toDateString(),
            'to'   => $this->toDate()?->toDateString(),
        ], fn ($value) => $value !== null && $value !== '');
    }

    public function canonicalUrl(string $baseUrl): string
    {
        $query = http_build_query($this->query());

        return $query === '' 
                 ? $baseUrl 
                 : "{$baseUrl}?{$query}";
    }

    public function cacheKey(): string
    {
        return sprintf(
            '%s:sort:%s:from:%s:to:%s',
            $this->tab->value,
            $this->resolvedSort()->value,
            $this->fromDate()?->toDateString() ?? 'all',
            $this->toDate()?->toDateString() ?? 'all'
        );
    }
}
