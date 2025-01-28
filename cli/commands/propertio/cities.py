from typing import Set

import registry
from client.HttpRequest import HttpMethod
from manager.ICommand import ICommand


def register():
    registry.register(Create())
    registry.register(Update())
    registry.register(Delete())
    registry.register(GetId())
    registry.register(Get())


class Create(ICommand):
    def get_name(self) -> str:
        return 'create-city'

    def get_url(self) -> str:
        return 'cities/'

    def get_body_params(self) -> Set[str]:
        return {'population', 'area', 'name', 'rating'} 

    def get_method(self) -> HttpMethod:
        return HttpMethod.POST

class Update(ICommand):
    def get_name(self) -> str:
        return 'update-city'

    def get_path_params(self) -> Set[str]:
        return {'id'}

    def get_url(self) -> str:
        return 'cities/{id}'

    def get_body_params(self) -> Set[str]:
        return {'population', 'area', 'name', 'rating'} 

    def get_method(self) -> HttpMethod:
        return HttpMethod.PATCH

class Delete(ICommand):
    def get_name(self) -> str:
        return 'delete-city'

    def get_path_params(self) -> Set[str]:
        return {'id'}

    def get_url(self) -> str:
        return 'cities/{id}'

    def get_method(self) -> HttpMethod:
        return HttpMethod.DELETE

class GetId(ICommand):
    def get_name(self) -> str:
        return 'get-city-by-id'

    def get_path_params(self) -> Set[str]:
        return {'id'}

    def get_url(self) -> str:
        return 'cities/{id}'

    def get_method(self) -> HttpMethod:
        return HttpMethod.GET

class Get(ICommand):
    def get_name(self) -> str:
        return 'get-city'

    def get_url(self) -> str:
        return 'cities/'
    
    def get_queries(self) -> Set[str]:
        return {'perPage', 'page', 'name', 'sortRating'}

    def get_method(self) -> HttpMethod:
        return HttpMethod.GET
