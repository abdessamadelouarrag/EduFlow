<?php

namespace App\Repositories;

use App\Models\Interest;
use App\Models\User;
use App\Repositories\Contracts\InterestRepositoryInterface;
use Illuminate\Support\Collection;

class InterestRepository implements InterestRepositoryInterface
{
    public function syncUserInterests(int $userId, array $interestNames): Collection
    {
        $interests = collect($interestNames)
            ->filter()
            ->map(fn ($name) => trim((string) $name))
            ->filter()
            ->unique()
            ->map(fn ($name) => Interest::firstOrCreate(['name' => $name]))
            ->values();

        User::query()->findOrFail($userId)->interests()->sync($interests->pluck('id')->all());

        return $interests;
    }
}
