create table uploads
(
    id            int auto_increment
        primary key,
    file_name     varchar(300) default '' not null,
    original_name varchar(300) default '' not null,
    alt           varchar(300)            null,
    constraint file_name
        unique (file_name),
    constraint original_name
        unique (original_name)
)
    engine = InnoDB;

create table social_networks
(
    id        int auto_increment
        primary key,
    name      varchar(300) not null,
    upload_id int          not null,
    constraint name
        unique (name),
    constraint upload_id
        unique (upload_id),
    constraint social_networks_ibfk_1
        foreign key (upload_id) references uploads (id)
)
    engine = InnoDB;

create table users
(
    id           int auto_increment
        primary key,
    username     varchar(300)                       null,
    email        varchar(300)                       not null,
    password     varchar(300)                       not null,
    role         tinyint                            not null,
    avatar_id    int                                null,
    first_name   varchar(300)                       null,
    last_name    varchar(500)                       null,
    title        varchar(300)                       null,
    phone        varchar(300)                       null,
    baseline     mediumtext                         null,
    introduction mediumtext                         null,
    resume_id    int                                null,
    date_added   datetime default CURRENT_TIMESTAMP not null,
    constraint email
        unique (email),
    constraint username
        unique (username),
    constraint users_ibfk_1
        foreign key (avatar_id) references uploads (id),
    constraint users_ibfk_2
        foreign key (resume_id) references uploads (id)
)
    engine = InnoDB;

create table network_links
(
    id         int auto_increment
        primary key,
    network_id int          not null,
    user_id    int          not null,
    link       varchar(300) not null,
    constraint network_links_ibfk_1
        foreign key (network_id) references social_networks (id),
    constraint network_links_ibfk_2
        foreign key (user_id) references users (id)
)
    engine = InnoDB;

create index network_id
    on network_links (network_id);

create index user_id
    on network_links (user_id);

create table posts
(
    id            int auto_increment
        primary key,
    user_id       int                                not null,
    title         varchar(300)                       null,
    header_id     int                                null,
    extract       mediumtext                         null,
    content       mediumtext                         null,
    date_added    datetime default CURRENT_TIMESTAMP not null,
    date_modified datetime default CURRENT_TIMESTAMP not null,
    status        tinyint  default 0                 not null,
    constraint posts_ibfk_1
        foreign key (header_id) references uploads (id),
    constraint posts_ibfk_2
        foreign key (user_id) references users (id)
)
    engine = InnoDB;

create table comments
(
    id         int auto_increment
        primary key,
    post_id    int                                not null,
    pseudo     varchar(300)                       not null,
    content    mediumtext                         not null,
    date_added datetime default CURRENT_TIMESTAMP not null,
    status     tinyint  default 0                 not null,
    constraint comments_ibfk_1
        foreign key (post_id) references posts (id)
)
    engine = InnoDB;

create index post_id
    on comments (post_id);

create index header_id
    on posts (header_id);

create index user_id
    on posts (user_id);

create index avatar_id
    on users (avatar_id);

create index resume_id
    on users (resume_id);


