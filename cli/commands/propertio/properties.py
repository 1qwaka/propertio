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
    registry.register(GetSpaceTypes())


class Create(ICommand):
    def get_name(self) -> str:
        return 'create-property'

    def get_url(self) -> str:
        return 'properties/'

    def get_body_params(self) -> Set[str]:
        return {'renovation', 'building_id', 'floor', 'area', 'floor_type_id', 'address', 'living_space_type'} 

    def get_method(self) -> HttpMethod:
        return HttpMethod.POST

class Update(ICommand):
    def get_name(self) -> str:
        return 'update-property'

    def get_path_params(self) -> Set[str]:
        return {'id'}

    def get_url(self) -> str:
        return 'properties/{id}'

    def get_body_params(self) -> Set[str]:
        return {'renovation', 'building_id', 'floor', 'area', 'floor_type_id', 'address', 'living_space_type'} 

    def get_method(self) -> HttpMethod:
        return HttpMethod.PATCH

class Delete(ICommand):
    def get_name(self) -> str:
        return 'delete-property'

    def get_path_params(self) -> Set[str]:
        return {'id'}

    def get_url(self) -> str:
        return 'properties/{id}'

    def get_method(self) -> HttpMethod:
        return HttpMethod.DELETE

class GetId(ICommand):
    def get_name(self) -> str:
        return 'get-property-by-id'

    def get_path_params(self) -> Set[str]:
        return {'id'}

    def get_url(self) -> str:
        return 'properties/{id}'

    def get_method(self) -> HttpMethod:
        return HttpMethod.GET

class Get(ICommand):
    def get_name(self) -> str:
        return 'get-property'

    def get_url(self) -> str:
        return 'properties/'

    def get_queries(self) -> Set[str]:
        return {'agent_id', 'living_space_type', 'page', 'perPage'}
    
    def get_method(self) -> HttpMethod:
        return HttpMethod.GET

class GetTypes(ICommand):
    def get_name(self) -> str:
        return 'get-property-types'

    def get_url(self) -> str:
        return 'properties/types'
    
    def get_method(self) -> HttpMethod:
        return HttpMethod.GET

class GetSpaceTypes(ICommand):
    def get_name(self) -> str:
        return 'get-property-space-types'

    def get_url(self) -> str:
        return 'properties/space-types'
    
    def get_method(self) -> HttpMethod:
        return HttpMethod.GET
