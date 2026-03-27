<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface InterestRepositoryInterface
{
    public function syncUserInterests(int $userId, array $interestNames): Collection;
}
