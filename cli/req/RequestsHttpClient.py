import json
import os

import requests

from client.HttpRequest import HttpRequest, HttpMethod
from client.HttpResponse import HttpResponse
from client.IHttpClient import IHttpClient


def queries_to_str(queries):
    if len(queries) == 0:
        return ''
    ret = '?'
    for k, v in queries.items():
        ret += k + '=' + str(v) + '&'
    return ret[:-1]


class RequestsHttpClient(IHttpClient):

    def send(self, request: HttpRequest) -> HttpResponse:
        method = request.get_method().name
        # Prepare url
        url = request.get_url() + queries_to_str(request.get_queries())
        for k, v in request.get_paths().items():
            url = url.replace('{%s}' % k, v)
        # Prepare body
        if method == HttpMethod.GET:
            body = None
        else:
            # body = json.dumps(request.get_body())
            body = request.get_body()

        session = requests.Session()

        if os.path.exists('cookies.json'):
            with open('cookies.json', 'r') as f:
                cookies = json.load(f)
                cookies_jar = requests.utils.cookiejar_from_dict(cookies)
                session.cookies.update(cookies_jar)

        # print(f'method: {method}')
        # print(f'url: {url}')
        # print(f'body: {body} {type(body)}')
        # print(f'headers: {request.get_headers()}')

        # session.headers.update({'Content-Type': 'application/json'})

        raw_response = session.request(
            method=method,
            url=url,
            data=body,
            # headers=request.get_headers()
        )

        with open('cookies.json', 'w', encoding='utf8') as f:
            cookies = requests.utils.dict_from_cookiejar(session.cookies)
            json.dump(cookies, f, indent=4)

        text = raw_response.text.strip()

        if len(text) == 0:
            body = {}
        elif not text.startswith('{') and not text.startswith('['):
            body = text
        else:
            body = json.loads(text)
        ret = HttpResponse(raw_response.status_code, body)
        return ret
