create table users
(
    id int unsigned auto_increment
        primary key,
    name varchar(255) not null ,
    email varchar(255) not null,
    image varchar(255) not null ,
    consent bool not null
)