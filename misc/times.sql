-- create index contracts_status_agent_id_idx on contracts (status, agent_id);
-- drop index if exists contracts_status_agent_id_idx;

-- 100   - 0.02650803000000000000
-- 500   - 0.07019244000000000000
-- 1000  - 0.11415593000000000000
-- 2000  - 0.22859234000000000000
-- 5000  - 0.51991859000000000000
-- 10000 - 0.89424503000000000000

-- индексы 1
-- 100   - 0.08392779000000000000
-- 500   - 0.08742284000000000000
-- 1000  - 0.08535853000000000000
-- 2000  - 0.08615367000000000000
-- 5000  - 0.08553822000000000000
-- 10000 - 0.08379414000000000000

--  количество / с индексом / без индекса
-- 100:   0.02634822000000000000 0.02417479000000000000
-- 500:   0.03587690000000000000 0.06590351000000000000
-- 1000:  0.05492864000000000000 0.11648976000000000000
-- 2000:  0.03873439000000000000 0.20167181000000000000
-- 5000:  0.05231469000000000000 0.50318633000000000000
-- 10000: 0.03545166000000000000 0.94801155000000000000

create or replace function measure(msg text)
    returns void as $$
declare
    start_time timestamp;
    end_time timestamp;
    total_time interval;
    i int;
    n int := 100000;
    ro int;
begin
    start_time := clock_timestamp();

    for i in 1..n loop
            perform count_accepted_contracts(2);
        end loop;

    end_time := clock_timestamp();

    total_time := end_time - start_time;

    select count(*) into ro from contracts;

    raise notice 'with indexes time %: % milliseconds', ro, extract(epoch from total_time) * 1000 / n;
end $$ language plpgsql;

select measure('10000'::text);

delete from contracts where id in (select id from contracts order by random() limit 5000);

select measure('5000'::text);

delete from contracts where id in (select id from contracts order by random() limit 3000);

select measure('2000'::text);

delete from contracts where id in (select id from contracts order by random() limit 1000);

select measure('1000'::text);

delete from contracts where id in (select id from contracts order by random() limit 500);

select measure('500'::text);

delete from contracts where id in (select id from contracts order by random() limit 400);

select measure('100'::text);


