<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class RecommendationService
{
    public function getForStudent(User $student): Collection
    {
        $domains = $student->interests()->pluck('name');

        return Course::query()
            ->with('teacher:id,name,email')
            ->when(
                $domains->isNotEmpty(),
                fn ($query) => $query->whereIn('domain', $domains->all()),
                fn ($query) => $query->latest()
            )
            ->latest()
            ->get();
    }
}
