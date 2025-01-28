from typing import Set

import registry
from client.HttpRequest import HttpMethod
from manager.ICommand import ICommand


def register():
    registry.register(Register())
    registry.register(Login())
    registry.register(Logout())
    registry.register(Self())


class Register(ICommand):
    def get_name(self) -> str:
        return 'register'

    def get_url(self) -> str:
        return 'register/'

    def get_body_params(self) -> Set[str]:
        return {'name', 'email', 'password'}

    def get_method(self) -> HttpMethod:
        return HttpMethod.POST


class Login(ICommand):
    def get_name(self) -> str:
        return 'login'

    def get_url(self) -> str:
        return 'login/'

    def get_body_params(self) -> Set[str]:
        return {'email', 'password'}

    def get_method(self) -> HttpMethod:
        return HttpMethod.POST

class Logout(ICommand):
    def get_name(self) -> str:
        return 'logout'

    def get_url(self) -> str:
        return 'logout/'

    def get_method(self) -> HttpMethod:
        return HttpMethod.POST

class Self(ICommand):
    def get_name(self) -> str:
        return 'self'

    def get_url(self) -> str:
        return 'self/'

    def get_method(self) -> HttpMethod:
        return HttpMethod.GET
