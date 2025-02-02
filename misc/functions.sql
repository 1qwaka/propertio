create or replace function count_accepted_contracts(id_val int)
    returns int as $$
declare
    accepted_count int;
begin
    select count(*) into accepted_count
    from contracts
    where agent_id = id_val and status = 'accepted';

    return accepted_count;
end;
$$ language plpgsql;
