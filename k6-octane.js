import http from 'k6/http';
import { sleep } from 'k6';
import { Trend } from 'k6/metrics';

const responseTime = new Trend('response_time', true);

export const options = {
    stages: [
        { duration: '10s', target: 100 },
        { duration: '60s', target: 2000 },
        { duration: '10s', target: 100 },
    ],
};

export default function () {
    const res = http.get('http://app:8000/up');
    responseTime.add(res.timings.duration);

    sleep(1);
}
