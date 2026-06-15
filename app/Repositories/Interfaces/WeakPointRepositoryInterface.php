<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface WeakPointRepositoryInterface
{
    public function getWeakPoints(int $userId): Collection;
}
