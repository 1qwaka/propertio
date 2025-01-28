from typing import Set

import registry
from client.HttpRequest import HttpMethod
from manager.ICommand import ICommand


def register():
    registry.register(Register())
    registry.register(GetTypes())
    registry.register(Stats())
    registry.register(Update())


class Register(ICommand):
    def get_name(self) -> str:
        return 'register-agent'

    def get_url(self) -> str:
        return 'agents/register'

    def get_body_params(self) -> Set[str]:
        return {'type', 'name', 'address', 'email'} 

    def get_method(self) -> HttpMethod:
        return HttpMethod.POST

class GetTypes(ICommand):
    def get_name(self) -> str:
        return 'get-agent-types'

    def get_url(self) -> str:
        return 'agents/types'

    def get_method(self) -> HttpMethod:
        return HttpMethod.GET

class Stats(ICommand):
    def get_name(self) -> str:
        return 'get-agent-stats'

    def get_url(self) -> str:
        return 'agents/stats'

    def get_method(self) -> HttpMethod:
        return HttpMethod.GET

class Update(ICommand):
    def get_name(self) -> str:
        return 'update-agent'

    def get_url(self) -> str:
        return 'agents/'

    def get_body_params(self) -> Set[str]:
        return {'type', 'name', 'address', 'email'} 

    def get_method(self) -> HttpMethod:
        return HttpMethod.PATCH
