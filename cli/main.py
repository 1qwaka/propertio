import json
import os.path
import sys

import registry
from client.HttpResponse import HttpResponse
from manager.Manager import Manager
from manager.ManagerBuilder import ManagerBuilder
from req.RequestsHttpClient import RequestsHttpClient

# import commands.auth
# import commands.account
# import commands.budget
# import commands.category
# import commands.currency
# import commands.exchange
# import commands.goal
# import commands.transaction
# import commands.user

# commands.auth.register()
# commands.account.register()
# commands.budget.register()
# commands.category.register()
# commands.currency.register()
# commands.exchange.register()
# commands.goal.register()
# commands.transaction.register()
# commands.user.register()

import commands.propertio.advertisements
import commands.propertio.agent
import commands.propertio.buildings
import commands.propertio.cities
import commands.propertio.contracts
import commands.propertio.developers
import commands.propertio.districts
import commands.propertio.properties
import commands.propertio.user
import commands.propertio.views

commands.propertio.advertisements.register()
commands.propertio.agent.register()
commands.propertio.buildings.register()
commands.propertio.cities.register()
commands.propertio.contracts.register()
commands.propertio.developers.register()
commands.propertio.districts.register()
commands.propertio.properties.register()
commands.propertio.user.register()
commands.propertio.views.register()

TABULAR = '  '

def print_dict(e, level=0):
    for k, v in e.items():
        indent = level * TABULAR
        if isinstance(v, int) or isinstance(v, float) or isinstance(v, str):
            print(f'{indent}{k}:  {v}')
        else:   
            print(f'{indent}{k}:  ')
            print_json(v, level+1)


def print_json(e, level=0):
    indent = level * TABULAR
    # print(f'e: {str(e)[:10]} dict {isinstance(e, dict)}')
    # print(f'e: {str(e)[:10]} list {isinstance(e, list)}')
    if isinstance(e, dict):
        print_dict(e, level+1)
        return
    if isinstance(e, list):
        print(f'{indent}[')
        for item in e:
            print_json(item, level+1)
            print(f'{indent},')
        print(f'{indent}]')
        return
    print(f'{indent}{e}')


def output(response: HttpResponse):
    print('Status: ' + str(response.get_status()))
    body = response.get_body()
    print_json(body)


def build(builder: ManagerBuilder, token, args) -> Manager:
    # Set host
    host = args.get('host')
    if host is None:
        host = 'http://127.0.0.1:8000/'
    builder.set_host(host)
    # Set http client
    client = RequestsHttpClient()
    builder.set_client(client)
    # Set printer
    builder.set_printer(output)

    # Set source
    def source(name):
        ret = args.get(name)
        if ret is None and name == 'Authorization':
            return token
        return ret

    builder.set_source(source)

    # Register commands
    registry.add_to_builder(builder)
    return builder.build()


def parse_named(args):
    ret = dict()
    for arg in args:
        if not arg.startswith('--'):
            continue
        index = arg.find('=')
        if index < 0:
            ret[arg[2:]] = None
        else:
            value = arg[index + 1:]
            if value.startswith('[') and value.endswith(']'):
                value = json.loads(value)
            ret[arg[2:index]] = value
    return ret


TOKEN_FILE = 'token.txt'


def read_token():
    if not os.path.exists(TOKEN_FILE):
        return None
    try:
        with open(TOKEN_FILE, 'r') as f:
            return 'Bearer ' + f.readline().strip()
    except:
        return None


def main(args):
    if len(args) == 0:
        print('Missing command')
        return 1
    token = read_token()
    builder = ManagerBuilder()
    manager = build(builder, token, parse_named(args[1:]))
    return 0 if manager.execute(args[0]) else 1


if __name__ == '__main__':
    sys.exit(main(sys.argv[1:]))
