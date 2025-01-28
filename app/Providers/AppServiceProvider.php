<?php

namespace App\Providers;

use App\Domain\Agent\IAgentService;
use App\Domain\Property\IPropertyService;
use App\Domain\User\IUserService;
use App\Services\AgentService;
use App\Services\PropertyService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

use Illuminate\Database\Eloquent\Factories\Factory;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Database\\Factories\\'.class_basename($modelName).'Factory';
        });

        $services = [
            IUserService::class => UserService::class,
            IAgentService::class => AgentService::class,
            IPropertyService::class => PropertyService::class,
        ];
        foreach ($services as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }
}
