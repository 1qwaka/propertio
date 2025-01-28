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
    registry.register(GetTypes())


class Create(ICommand):
    def get_name(self) -> str:
        return 'create-building'

    def get_url(self) -> str:
        return 'buildings/'

    def get_body_params(self) -> Set[str]:
        return {'type', 'hot_water', 'gas', 'elevators', 'floors', 'build_year', 'district_id', 'developer_id', 'address'} 

    def get_method(self) -> HttpMethod:
        return HttpMethod.POST

class Update(ICommand):
    def get_name(self) -> str:
        return 'update-building'

    def get_path_params(self) -> Set[str]:
        return {'id'}

    def get_url(self) -> str:
        return 'buildings/{id}'

    def get_body_params(self) -> Set[str]:
        return {'type', 'hot_water', 'gas', 'elevators', 'floors', 'build_year', 'district_id', 'developer_id', 'address'} 

    def get_method(self) -> HttpMethod:
        return HttpMethod.PATCH

class Delete(ICommand):
    def get_name(self) -> str:
        return 'delete-building'

    def get_path_params(self) -> Set[str]:
        return {'id'}

    def get_url(self) -> str:
        return 'buildings/{id}'

    def get_method(self) -> HttpMethod:
        return HttpMethod.DELETE

class GetId(ICommand):
    def get_name(self) -> str:
        return 'get-building-by-id'

    def get_path_params(self) -> Set[str]:
        return {'id'}

    def get_url(self) -> str:
        return 'buildings/{id}'

    def get_method(self) -> HttpMethod:
        return HttpMethod.GET

class Get(ICommand):
    def get_name(self) -> str:
        return 'get-building'

    def get_url(self) -> str:
        return 'buildings/'

    def get_queries(self) -> Set[str]:
        return {'page', 'perPage'}
    
    def get_method(self) -> HttpMethod:
        return HttpMethod.GET

class GetTypes(ICommand):
    def get_name(self) -> str:
        return 'get-building-types'

    def get_url(self) -> str:
        return 'buildings/types'
    
    def get_method(self) -> HttpMethod:
        return HttpMethod.GET
