create table users
(
    id             bigserial    primary key,
    name           varchar(100),
    email          varchar(100) not null        unique,
    password       varchar(255) not null,
    is_admin       boolean      default false   not null,
    remember_token varchar(100),
    created_at     timestamp(0),
    updated_at     timestamp(0)
);

create table agent_type
(
    id   bigserial      primary key,
    name varchar(50)    not null    unique
);

create table agents
(
    id         bigserial    primary key,
    type_id    bigint       not null    references agent_type,
    name       varchar(100) not null,
    address    varchar(100) not null,
    email      varchar(255) not null    unique,
    user_id    bigint       not null    references users    unique,
    created_at timestamp(0),
    updated_at timestamp(0)
);

create table cities
(
    id         bigserial        primary key,
    population integer          not null,
    area       integer          ,
    name       varchar(100)     not null,
    rating     float            ,
    created_at timestamp(0),
    updated_at timestamp(0)
);

create table districts
(
    id         bigserial        primary key,
    city_id    bigint           references cities on delete set null,
    population integer          ,
    area       integer          ,
    name       varchar(100)     not null,
    rating     float            ,
    created_at timestamp(0),
    updated_at timestamp(0)
);

create table developers
(
    id         bigserial        primary key,
    address    varchar(100)     not null,
    name       varchar(100)     not null,
    rating     float            ,
    email      varchar(100)     not null    unique,
    created_at timestamp(0),
    updated_at timestamp(0)
);

create table building_type
(
    id   bigserial      primary key,
    name varchar(50)    not null    unique
);

create table buildings
(
    id           bigserial                      primary key,
    type_id      bigint                         not null    references building_type,
    hot_water    boolean  default true          ,
    gas          boolean  default true          ,
    elevators    smallint default 0
        constraint check_elevators check ( elevators >= 0 ),
    floors       smallint                       not null
        constraint check_floors check ( floors >= 0 ),
    build_year   smallint                       not null,
    district_id  bigint                         references districts on delete set null,
    developer_id bigint                         references developers on delete set null,
    address      varchar(100)                   not null,
    created_at   timestamp(0),
    updated_at   timestamp(0)
);

create table floor_type
(
    id   bigserial      primary key,
    name varchar(50)    not null    unique
);

create type living_space_t as enum
(
    'primary',
    'secondary'
);

create table properties
(
    id                bigserial         primary key,
    renovation        varchar(100),
    building_id       bigint            references buildings,
    floor             smallint          not null,
    area              smallint
        constraint check_area check ( area > 0 ),
    floor_type_id     bigint            not null    references floor_type,
    address           varchar(100)      not null,
    living_space_type living_space_t    not null,
    agent_id          bigint            not null    references agents on delete cascade,
    created_at        timestamp(0),
    updated_at        timestamp(0)
);

create type contract_status_t as enum
(
    'open',
    'accepted',
    'rejected'
);

create table contracts
(
    id              bigserial               primary key,
    property_id     bigint                                  references properties on delete set null,
    status          contract_status_t       not null,
    date            date default now()      not null,
    price           integer                 not null
        constraint check_price check ( price > 0 ),
    buyer_id        bigint                                  references users on delete set null,
    agent_id        bigint                                  references agents on delete set null,
    until           date,
    buyer_agreement boolean default false   not null,
    created_at      timestamp(0),
    updated_at      timestamp(0)
);

create type view_request_status_t as enum
(
    'open',
    'accepted',
    'rejected'
);

create table view_requests
(
    id          bigserial               primary key,
    status      view_request_status_t   not null,
    date        date,
    property_id bigint       not null   references properties on delete cascade ,
    user_id     bigint       not null   references users on delete cascade,
    created_at  timestamp(0),
    updated_at  timestamp(0)
);

create type advertisement_type_t as enum
(
    'sell',
    'rent'
);

create table advertisements
(
    id          bigserial               primary key,
    agent_id    bigint                  not null        references agents on delete cascade,
    description text                    ,
    price       integer                 not null
        constraint check_price check ( price > 0 ),
    property_id bigint                  not null        references properties on delete cascade,
    type        advertisement_type_t    not null,
    hidden      boolean default false   ,
    created_at  timestamp(0),
    updated_at  timestamp(0)
);
