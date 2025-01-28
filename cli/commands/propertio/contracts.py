from typing import Set

import registry
from client.HttpRequest import HttpMethod
from manager.ICommand import ICommand


def register():
    registry.register(Create())
    registry.register(Update())
    registry.register(Delete())
    registry.register(GetId())
    registry.register(GetAsAgent())
    registry.register(GetAsUser())
    registry.register(ChangeStatus())


class Create(ICommand):
    def get_name(self) -> str:
        return 'create-contract'

    def get_url(self) -> str:
        return 'contracts/'

    def get_body_params(self) -> Set[str]:
        return {'property_id', 'date', 'price', 'buyer_id', 'until'} 

    def get_method(self) -> HttpMethod:
        return HttpMethod.POST

class Update(ICommand):
    def get_name(self) -> str:
        return 'update-contract'

    def get_path_params(self) -> Set[str]:
        return {'id'}

    def get_url(self) -> str:
        return 'contracts/{id}'

    def get_body_params(self) -> Set[str]:
        return {'date', 'price', 'until'}

    def get_method(self) -> HttpMethod:
        return HttpMethod.PATCH

class Delete(ICommand):
    def get_name(self) -> str:
        return 'delete-contract'

    def get_path_params(self) -> Set[str]:
        return {'id'}

    def get_url(self) -> str:
        return 'contracts/{id}'

    def get_method(self) -> HttpMethod:
        return HttpMethod.DELETE

class GetId(ICommand):
    def get_name(self) -> str:
        return 'get-contract-by-id'

    def get_path_params(self) -> Set[str]:
        return {'id'}

    def get_url(self) -> str:
        return 'contracts/{id}'

    def get_method(self) -> HttpMethod:
        return HttpMethod.GET

class GetAsUser(ICommand):
    def get_name(self) -> str:
        return 'get-contract-as-user'

    def get_url(self) -> str:
        return 'contracts/user'

    def get_queries(self) -> Set[str]:
        return {'page', 'perPage'}

    def get_method(self) -> HttpMethod:
        return HttpMethod.GET

class GetAsAgent(ICommand):
    def get_name(self) -> str:
        return 'get-contract-as-agent'

    def get_url(self) -> str:
        return 'contracts/agent'
    
    def get_queries(self) -> Set[str]:
        return {'page', 'perPage'}

    def get_method(self) -> HttpMethod:
        return HttpMethod.GET

class ChangeStatus(ICommand):
    def get_name(self) -> str:
        return 'change-status-contract'
    
    def get_path_params(self) -> Set[str]:
        return {'id'}

    def get_url(self) -> str:
        return 'contracts/{id}/status'
    
    def get_body_params(self) -> Set[str]:
        return {'status'}

    def get_method(self) -> HttpMethod:
        return HttpMethod.POST
