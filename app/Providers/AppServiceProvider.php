<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\FavoriteRepository;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\FavoriteRepositoryInterface;
use App\Repositories\Interfaces\LearningRecordRepositoryInterface;
use App\Repositories\Interfaces\QuizRepositoryInterface;
use App\Repositories\Interfaces\QuizSessionRepositoryInterface;
use App\Repositories\Interfaces\TermRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\WeakPointRepositoryInterface;
use App\Repositories\LearningRecordRepository;
use App\Repositories\QuizRepository;
use App\Repositories\QuizSessionRepository;
use App\Repositories\TermRepository;
use App\Repositories\UserRepository;
use App\Repositories\WeakPointRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class,      CategoryRepository::class);
        $this->app->bind(TermRepositoryInterface::class,          TermRepository::class);
        $this->app->bind(FavoriteRepositoryInterface::class,      FavoriteRepository::class);
        $this->app->bind(QuizRepositoryInterface::class,          QuizRepository::class);
        $this->app->bind(QuizSessionRepositoryInterface::class,   QuizSessionRepository::class);
        $this->app->bind(LearningRecordRepositoryInterface::class, LearningRecordRepository::class);
        $this->app->bind(WeakPointRepositoryInterface::class,     WeakPointRepository::class);
        $this->app->bind(UserRepositoryInterface::class,          UserRepository::class);
    }

    public function boot(): void {}
}
