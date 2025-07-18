<?php

namespace App\Providers;

use App\Domain\Todo\Repositories\TodoRepositoryInterface;
use App\Infrastructure\Repositories\EloquentTodoRepository;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            TodoRepositoryInterface::class,
            EloquentTodoRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Scramble::configure()
            ->routes(function (Route $route) {
                return str_starts_with($route->uri, 'api') || str_starts_with($route->uri, 'sanctum');
            })
            ->withDocumentTransformers(
                function (OpenApi $openApi) {
                    $cookieAuth = SecurityScheme::apiKey('cookie', 'laravel_session')
                        ->setDescription('SPA認証を利用する場合にセッションIDを指定');
                    $bearerAuth = SecurityScheme::http('bearer')
                        ->setDescription('APIトークン認証を利用する場合にAPIトークンを指定');

                    // SPA認証か、APIトークン認証のいずれかを要求
                    $openApi->secure($cookieAuth);
                    $openApi->secure($bearerAuth);
                }
            );
    }
}
