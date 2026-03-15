<?php

declare(strict_types=1);

namespace App\Providers;

use App\Modules\Auth\Application\Listeners\LoginSuccessful;
use App\Modules\Auth\Application\Listeners\LogoutSuccessful;
use App\Modules\Auth\Application\Listeners\RegisterSuccessful;
use App\Modules\Auth\Domain\Events\LoginEvent;
use App\Modules\Auth\Domain\Events\LogoutEvent;
use App\Modules\Auth\Domain\Events\RegisterEvent;
use App\Modules\Log\Domain\Repo\LogRepository;
use App\Modules\Log\Infra\Persistence\EloquentLogRepository;
use App\Modules\Payment\Application\Events\WebhookSaleNotFoundEvent;
use App\Modules\Payment\Application\Listeners\LogSaleCreated;
use App\Modules\Payment\Application\Listeners\LogSaleUpdated;
use App\Modules\Payment\Application\Listeners\LogWebhookSaleNotFound;
use App\Modules\Payment\Domain\Events\SaleApprovedEvent;
use App\Modules\Payment\Domain\Events\SaleCreatedEvent;
use App\Modules\Payment\Domain\Events\SaleFailedEvent;
use App\Shared\Application\Events\EventDispatcherInterface;
use App\Shared\Infrastructure\Events\LaravelEventDispatcher;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            LogRepository::class,
            EloquentLogRepository::class,
        );
        $this->app->bind(
            EventDispatcherInterface::class,
            LaravelEventDispatcher::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // --- Events ---

        // Payment
        Event::listen(
            SaleApprovedEvent::class,
            LogSaleUpdated::class,
        );
        Event::listen(
            SaleCreatedEvent::class,
            LogSaleCreated::class,
        );
        Event::listen(
            SaleFailedEvent::class,
            LogSaleUpdated::class,
        );
        Event::listen( // Entity-less case
            WebhookSaleNotFoundEvent::class,
            LogWebhookSaleNotFound::class,
        );

        // Auth
        Event::listen(
            LoginEvent::class,
            LoginSuccessful::class,
        );
        Event::listen(
            RegisterEvent::class,
            RegisterSuccessful::class,
        );
        Event::listen(
            LogoutEvent::class,
            LogoutSuccessful::class,
        );
    }
}
