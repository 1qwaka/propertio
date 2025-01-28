
create role guest;
create role agent;
create role common_user;
create role administrator;

grant select on advertisements, contracts, properties to guest;
grant insert on users to guest;


grant select on advertisements, view_requests, contracts, properties, agents, buildings, districts, cities, developers, agent_type, building_type, floor_type to common_user;
grant insert on agents, view_requests to common_user;
grant delete on view_requests to common_user;
grant update on contracts, users to common_user;

grant select on advertisements, view_requests, contracts, properties, agents, buildings, districts, cities, developers, agent_type, building_type, floor_type to agent;
grant insert, delete on contracts, properties, advertisements to agent;
grant update on agents, view_requests, contracts, properties, advertisements to agent;


grant select on advertisements, view_requests, contracts, properties, agents, buildings, districts, cities, developers, agent_type, building_type, floor_type to administrator;
grant insert, update, delete on buildings, cities, developers, districts to administrator;



