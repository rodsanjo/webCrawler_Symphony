use sellekt;

set names utf8;

set sql_mode = 'traditional';

drop table if exists wcr_searches;

create table if not exists wcr_searches
(id integer auto_increment
,search varchar(100) not null
,date timestamp default now()
,profile varchar(500)
,primary key(id)
,unique(search,date,profile)
);

