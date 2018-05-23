drop table if exists users;

create table users (
  login varchar(20) primary key,
  password varchar(20),
  isAdmin boolean);


insert into users(login, password, isAdmin) values('admin', 'admin1', true);
insert into users(login, password, isAdmin) values('guest', 'guest1', false);
