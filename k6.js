import http from 'k6/http';
import { sleep } from 'k6';
import { Trend } from 'k6/metrics';

const responseTime = new Trend('response_time', true);

export const options = {
    stages: [
        { duration: '10s', target: 10 },
        { duration: '30s', target: 50 },
        { duration: '10s', target: 10 },
    ],
};

export default function () {
    const res = http.get('http://nginx:80/up');
    responseTime.add(res.timings.duration);

    sleep(1);
}
