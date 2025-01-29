<?php

namespace App\Providers;

use App\Domain\Advertisement\IAdvertisementRepository;
use App\Domain\Advertisement\IAdvertisementService;
use App\Domain\Agent\IAgentService;
use App\Domain\Building\IBuildingRepository;
use App\Domain\Building\IBuildingService;
use App\Domain\Developer\IDeveloperRepository;
use App\Domain\Developer\IDeveloperService;
use App\Domain\Property\IPropertyService;
use App\Domain\User\IUserService;
use App\Domain\ViewRequest\IViewRequestRepository;
use App\Domain\ViewRequest\IViewRequestService;
use App\Persistence\Repository\AdvertisementRepository;
use App\Persistence\Repository\BuildingRepository;
use App\Persistence\Repository\DeveloperRepository;
use App\Persistence\Repository\ViewRequestRepository;
use App\Services\AdvertisementService;
use App\Services\AgentService;
use App\Services\BuildingService;
use App\Services\DeveloperService;
use App\Services\PropertyService;
use App\Services\UserService;
use App\Services\ViewRequestService;
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
            IAdvertisementService::class => AdvertisementService::class,
            IBuildingService::class => BuildingService::class,
            IDeveloperService::class => DeveloperService::class,
            IViewRequestService::class => ViewRequestService::class,

            IBuildingRepository::class => BuildingRepository::class,
            IDeveloperRepository::class => DeveloperRepository::class,
            IViewRequestRepository::class => ViewRequestRepository::class,
            IAdvertisementRepository::class => AdvertisementRepository::class,
        ];
        foreach ($services as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }
}
