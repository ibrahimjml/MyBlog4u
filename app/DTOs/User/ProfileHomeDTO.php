<?php

namespace App\DTOs\User;

use App\Enums\ProfileArticleSort;
use App\Http\Requests\App\ProfileHomeRequest;

class ProfileHomeDTO
{
    public function __construct(
        public readonly ProfileArticleSort $sort,
        public readonly int $perPage,
        public readonly int $page,
    ) {}

    public static function fromRequest(ProfileHomeRequest $request): self
    {
        return new self(
            sort: ProfileArticleSort::tryFrom((string) $request->input('sort', ProfileArticleSort::RECENT->value))
                ?? ProfileArticleSort::RECENT,
            perPage: (int) $request->input('perpage', 5),
            page: (int) $request->input('home_page', 1),
        );
    }

    /**
     * Query string params, omitting defaults so the URL stays clean.
     *
     * @return array<string, int|string>
     */
    public function query(): array
    {
        return array_filter([
            'sort'    => $this->sort->isDefault() ? null : $this->sort->value,
            'perpage' => $this->perPage === 5 ? null : $this->perPage,
        ], fn ($value) => $value !== null);
    }
}
