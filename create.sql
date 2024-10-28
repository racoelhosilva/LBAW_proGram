-- Initial schema/path setup

DROP SCHEMA IF EXISTS lbaw2411 CASCADE;
CREATE SCHEMA lbaw2411;
SET search_path TO lbaw2411;

-- Type Creations

CREATE TYPE attachment_type AS ENUM (
    'image',
    'video'
);

CREATE TYPE status AS ENUM (
    'pending',
    'accepted',
    'rejected'
);

CREATE TYPE notification_type AS ENUM (
    'follow',
    'comment',
    'post_like',
    'comment_like',
    'post_mention',
    'comment_mention'
);

-- Table Creations

CREATE TABLE account (
    id SERIAL,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    register_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

CREATE TABLE user_stats (
    id SERIAL,
    PRIMARY KEY (id)
);

CREATE TABLE users (
    account_id SERIAL,
    user_stats_id INTEGER NOT NULL UNIQUE,
    handle TEXT NOT NULL UNIQUE,
    is_public BOOLEAN NOT NULL,
    description TEXT,
    profile_picture_url TEXT,
    banner_image_url TEXT,
    PRIMARY KEY (account_id),
    FOREIGN KEY (account_id) REFERENCES account (id) ON UPDATE CASCADE,
    FOREIGN KEY (user_stats_id) REFERENCES user_stats (id) ON UPDATE CASCADE
);

CREATE TABLE language (
    id SERIAL,
    name TEXT NOT NULL UNIQUE,
    PRIMARY KEY (id)
);

CREATE TABLE user_stats_language (
    user_stats_id INTEGER, 
    language_id INTEGER,
    PRIMARY KEY (user_stats_id, language_id),
    FOREIGN KEY (user_stats_id) REFERENCES user_stats (id) ON UPDATE CASCADE,
    FOREIGN KEY (language_id) REFERENCES language (id) ON UPDATE CASCADE
);

CREATE TABLE administrator (
    account_id INTEGER NOT NULL,
    PRIMARY KEY (account_id),
    FOREIGN KEY (account_id) REFERENCES account (id) ON UPDATE CASCADE
);

CREATE TABLE technology (
    id SERIAL,
    name TEXT NOT NULL UNIQUE,
    PRIMARY KEY (id)
);

CREATE TABLE user_stats_technology (
    user_stats_id INTEGER NOT NULL,
    technology_id INTEGER NOT NULL,
    PRIMARY KEY (user_stats_id,technology_id),
    FOREIGN KEY (user_stats_id) REFERENCES user_stats (id) ON UPDATE CASCADE,
    FOREIGN KEY (technology_id) REFERENCES technology (id) ON UPDATE CASCADE
);

CREATE TABLE top_project (
    id SERIAL,
    name TEXT NOT NULL,
    url TEXT NOT NULL,
    user_stats_id INTEGER NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_stats_id) REFERENCES user_stats (id) ON UPDATE CASCADE
);

CREATE TABLE post (
    id SERIAL,
    author_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    text TEXT,
    creation_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_announcement BOOLEAN NOT NULL DEFAULT FALSE,
    is_public BOOLEAN NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (author_id) REFERENCES users (account_id) ON UPDATE CASCADE
);

CREATE TABLE post_like (
    id SERIAL,
    liker_id INTEGER NOT NULL,
    post_id INTEGER NOT NULL,
    timestamp TIMESTAMP NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (liker_id) REFERENCES users (account_id) ON UPDATE CASCADE,
    FOREIGN KEY (post_id) REFERENCES post (id) ON UPDATE CASCADE
);

CREATE TABLE post_attachment (
    id SERIAL,
    post_id INTEGER NOT NULL,
    url TEXT NOT NULL,
    type attachment_type NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (post_id) REFERENCES post (id)
);

CREATE TABLE tag (
    id SERIAL,
    name TEXT NOT NULL UNIQUE,
    PRIMARY KEY (id)
);


CREATE TABLE post_tag (
    post_id INTEGER NOT NULL,
    tag_id INTEGER NOT NULL,
    PRIMARY KEY (post_id, tag_id),
    FOREIGN KEY (post_id) REFERENCES post (id) ON UPDATE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tag (id) ON UPDATE CASCADE
);

CREATE TABLE comment (
    id SERIAL,
    post_id INTEGER NOT NULL,
    author_id INTEGER NOT NULL,
    content TEXT NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (post_id) REFERENCES post (id) ON UPDATE CASCADE,
    FOREIGN KEY (author_id) REFERENCES users (account_id) ON UPDATE CASCADE
);

CREATE TABLE comment_like (
    id SERIAL,
    comment_id INT NOT NULL,
    liker_id INT NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (comment_id) REFERENCES comment (id) ON UPDATE CASCADE,
    FOREIGN KEY (liker_id) REFERENCES users (account_id) ON UPDATE CASCADE
);

CREATE TABLE follow (
    id SERIAL,
    follower_id INTEGER NOT NULL,
    followed_id INTEGER NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE (followed_id, follower_id),
    FOREIGN KEY (follower_id) REFERENCES users (account_id) ON UPDATE CASCADE,
    FOREIGN KEY (followed_id) REFERENCES users (account_id) ON UPDATE CASCADE,
    CONSTRAINT not_self_follow CHECK (followed_id <> followed_id)
);

CREATE TABLE follow_request (
    id SERIAL,
    follower_id INTEGER NOT NULL,
    followed_id INTEGER NOT NULL,
    creation_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    TYPE status NOT NULL DEFAULT 'pending',
    PRIMARY KEY (id),
    FOREIGN KEY (follower_id) REFERENCES users (account_id) ON UPDATE CASCADE,
    FOREIGN KEY (followed_id) REFERENCES users (account_id) ON UPDATE CASCADE,
    CONSTRAINT not_self_follow CHECK (followed_id <> followed_id)
);

CREATE TABLE ban (
    id SERIAL,
    user_id INTEGER NOT NULL,
    administrator_id INTEGER NOT NULL,
    start TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reason TEXT NOT NULL,
    duration INTEGER NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users (account_id) ON UPDATE CASCADE,
    FOREIGN KEY (administrator_id) REFERENCES administrator (account_id) ON UPDATE CASCADE,
    CONSTRAINT non_negative_duration CHECK (duration >= 0)
);

CREATE TABLE token(
    id SERIAL,
    account_id INTEGER NOT NULL,
    creation_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    validity_timestamp TIMESTAMP NOT NULL,
    PRIMARY KEY (id),
    UNIQUE (account_id),
    FOREIGN KEY (account_id) REFERENCES account (id) ON UPDATE CASCADE,
    CONSTRAINT validity_after_creation CHECK (validity_timestamp > creation_timestamp)
);

CREATE TABLE groups (
    id SERIAL,
    owner_id INTEGER NOT NULL,
    name TEXT NOT NULL UNIQUE,
    description TEXT,
    creation_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (owner_id) REFERENCES users (account_id)
);

CREATE TABLE group_member (
    user_id INTEGER NOT NULL,
    group_id INTEGER NOT NULL,
    join_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, group_id),
    FOREIGN KEY (user_id) REFERENCES users (account_id),
    FOREIGN KEY (group_id) REFERENCES groups (id)
);

CREATE TABLE group_post (
    post_id INTEGER,
    group_id INTEGER NOT NULL,
    PRIMARY KEY (post_id),
    FOREIGN KEY (post_id) REFERENCES post (id) ON UPDATE CASCADE,
    FOREIGN KEY (group_id) REFERENCES groups (id) ON UPDATE CASCADE
);

CREATE TABLE group_join_request (
    id SERIAL,
    group_id INTEGER NOT NULL,
    requester_id INTEGER NOT NULL,
    creation_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    TYPE status NOT NULL DEFAULT 'pending',
    PRIMARY KEY (id),
    FOREIGN KEY (group_id) REFERENCES groups (id) ON UPDATE CASCADE,
    FOREIGN KEY (requester_id) REFERENCES users (account_id) ON UPDATE CASCADE
);

CREATE TABLE group_invitation (
    id SERIAL,
    group_id INTEGER NOT NULL,
    invitee_id INTEGER NOT NULL,
    creation_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    TYPE status NOT NULL DEFAULT 'pending',
    PRIMARY KEY (id),
    FOREIGN KEY (group_id) REFERENCES groups (id) ON UPDATE CASCADE,
    FOREIGN KEY (invitee_id) REFERENCES users (account_id) ON UPDATE CASCADE
);

CREATE TABLE notification (
    id SERIAL,
    receiver_id INTEGER NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_read BOOLEAN DEFAULT FALSE,
    TYPE notification_type NOT NULL,
    follow_id INTEGER,
    post_id INTEGER,
    comment_id INTEGER,
    post_like_id INTEGER,
    comment_like_id INTEGER,
    PRIMARY KEY (id),
    FOREIGN KEY (receiver_id) REFERENCES users (account_id) ON UPDATE CASCADE,
    FOREIGN KEY (follow_id) REFERENCES follow (id) ON UPDATE CASCADE,
    FOREIGN KEY (post_id) REFERENCES post (id) ON UPDATE CASCADE,
    FOREIGN KEY (comment_id) REFERENCES comment (id) ON UPDATE CASCADE,
    FOREIGN KEY (post_like_id) REFERENCES post_like (id) ON UPDATE CASCADE,
    FOREIGN KEY (comment_like_id) REFERENCES comment_like (id) ON UPDATE CASCADE
);

-- Index Creations

-- Trigger Definitions

-- OR REPLACE FUNCTION check_top_projects_count() RETURNS TRIGGER AS $ $ BEGIN IF (
--     SELECT
--         COUNT(*)
--     FROM
--         top_project
--     WHERE
--         user_stats_id = NEW.user_stats_id
-- ) >= 10 THEN RAISE EXCEPTION 'User can have at most 10 top projects';

-- END IF;

-- RETURN NEW;

-- END;

-- $ $ LANGUAGE plpgsql;

-- CREATE TRIGGER limit_top_projects BEFORE
-- INSERT
--     ON top_project FOR EACH ROW EXECUTE FUNCTION check_top_projects_count();