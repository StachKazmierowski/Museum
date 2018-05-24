drop table if exists users;

create table users (
  login varchar(20) primary key,
  password varchar(20));


insert into users(login, password) values('admin', 'admin1');

grant usage on schema kd370826 to scott;
grant select on users to scott;

