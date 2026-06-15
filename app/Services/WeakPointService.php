<?php

namespace App\Services;

use App\Repositories\Interfaces\WeakPointRepositoryInterface;
use Illuminate\Support\Collection;

class WeakPointService
{
    public function __construct(
        private WeakPointRepositoryInterface $repository
    ) {}

    public function getWeakPoints(int $userId): Collection
    {
        return $this->repository->getWeakPoints($userId);
    }
}
