<?php

namespace App\Providers;

use App\Domain\Advertisement\IAdvertisementRepository;
use App\Domain\Advertisement\IAdvertisementService;
use App\Domain\Agent\IAgentRepository;
use App\Domain\Agent\IAgentService;
use App\Domain\Building\IBuildingRepository;
use App\Domain\Building\IBuildingService;
use App\Domain\Developer\IDeveloperRepository;
use App\Domain\Developer\IDeveloperService;
use App\Domain\Property\IPropertyRepository;
use App\Domain\Property\IPropertyService;
use App\Domain\User\IUserRepository;
use App\Domain\User\IUserService;
use App\Domain\ViewRequest\IViewRequestRepository;
use App\Domain\ViewRequest\IViewRequestService;
use App\Models\Advertisement;
use App\Models\Agent;
use App\Models\Building;
use App\Models\Developer;
use App\Models\Property;
use App\Models\User;
use App\Models\ViewRequest;
use App\Persistence\Repository\AdvertisementRepository;
use App\Persistence\Repository\AgentRepository;
use App\Persistence\Repository\BuildingRepository;
use App\Persistence\Repository\DeveloperRepository;
use App\Persistence\Repository\PropertyRepository;
use App\Persistence\Repository\UserRepository;
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
            IPropertyRepository::class => PropertyRepository::class,
            IAgentRepository::class => AgentRepository::class,
            IUserRepository::class => UserRepository::class,
        ];
        foreach ($services as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }

        $this->app->bind(Building::class, fn () => new Building());
        $this->app->bind(Developer::class, fn () => new Developer());
        $this->app->bind(Advertisement::class, fn () => new Advertisement());
        $this->app->bind(Agent::class, fn () => new Agent());
        $this->app->bind(Property::class, fn () => new Property());
        $this->app->bind(User::class, fn () => new User());
        $this->app->bind(ViewRequest::class, fn () => new ViewRequest());
    }
}
