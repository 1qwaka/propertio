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
        return 'create-advertisement'

    def get_url(self) -> str:
        return 'advertisements/'

    def get_body_params(self) -> Set[str]:
        return {'description', 'price', 'property_id', 'type', 'hidden'} 

    def get_method(self) -> HttpMethod:
        return HttpMethod.POST

class Update(ICommand):
    def get_name(self) -> str:
        return 'update-advertisement'

    def get_path_params(self) -> Set[str]:
        return {'id'}

    def get_url(self) -> str:
        return 'advertisements/{id}'

    def get_body_params(self) -> Set[str]:
        return {'description', 'price', 'property_id', 'type', 'hidden'} 

    def get_method(self) -> HttpMethod:
        return HttpMethod.PATCH

class Delete(ICommand):
    def get_name(self) -> str:
        return 'delete-advertisement'

    def get_path_params(self) -> Set[str]:
        return {'id'}

    def get_url(self) -> str:
        return 'advertisements/{id}'

    def get_method(self) -> HttpMethod:
        return HttpMethod.DELETE

class GetId(ICommand):
    def get_name(self) -> str:
        return 'get-advertisement-by-id'

    def get_path_params(self) -> Set[str]:
        return {'id'}

    def get_url(self) -> str:
        return 'advertisements/{id}'

    def get_method(self) -> HttpMethod:
        return HttpMethod.GET

class Get(ICommand):
    def get_name(self) -> str:
        return 'get-advertisement'

    def get_url(self) -> str:
        return 'advertisements/'

    def get_queries(self) -> Set[str]:
        return {'agent_id', 'page', 'perPage'}
    
    def get_method(self) -> HttpMethod:
        return HttpMethod.GET
