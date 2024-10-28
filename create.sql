DROP SCHEMA IF EXISTS lbaw2411 CASCADE;

CREATE SCHEMA lbaw2411;

SET
    search_path TO lbaw2411;

CREATE TABLE account (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    register_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_stats (id SERIAL PRIMARY KEY);

CREATE TABLE users (
    account_id SERIAL PRIMARY KEY,
    user_stats_id INTEGER NOT NULL,
    handle TEXT NOT NULL UNIQUE,
    is_public BOOLEAN NOT NULL,
    description TEXT,
    profile_picture_url TEXT,
    banner_image_url TEXT,
    FOREIGN KEY (account_id) REFERENCES account (id),
    FOREIGN KEY (user_stats_id) REFERENCES user_stats (id),
    UNIQUE (user_stats_id)
);

CREATE TABLE administrator (
    account_id INT PRIMARY KEY,
    FOREIGN KEY (account_id) REFERENCES account (id)
);

CREATE TABLE top_project (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    url TEXT NOT NULL,
    user_stats_id INTEGER NOT NULL,
    FOREIGN KEY (user_stats_id) REFERENCES user_stats (id)
);

CREATE TABLE technology (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL UNIQUE
);

CREATE TABLE language (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL UNIQUE
);

CREATE TABLE follow (
    id SERIAL PRIMARY KEY,
    follower_id INTEGER NOT NULL,
    followed_id INTEGER NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (follower_id) REFERENCES users (account_id),
    FOREIGN KEY (followed_id) REFERENCES users (account_id)
);

-- cenas do ze
CREATE TABLE ban (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    administrator_id INT NOT NULL,
    start TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reason TEXT NOT NULL,
    duration INT NOT NULL CHECK (duration >= 0),
    FOREIGN KEY (user_id) REFERENCES users (account_id),
    FOREIGN KEY (administrator_id) REFERENCES administrator (account_id)
);

CREATE TABLE post (
    id SERIAL PRIMARY KEY,
    author_id INT NOT NULL,
    title TEXT NOT NULL,
    text TEXT,
    creation_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_announcement BOOLEAN NOT NULL DEFAULT FALSE,
    is_public BOOLEAN NOT NULL,
    FOREIGN KEY (author_id) REFERENCES users (account_id)
);

CREATE TABLE post_like (
    id SERIAL PRIMARY KEY,
    liker_id INT NOT NULL,
    post_id INT NOT NULL,
    timestamp TIMESTAMP NOT NULL,
    FOREIGN KEY (liker_id) REFERENCES users (account_id),
    FOREIGN KEY (post_id) REFERENCES post (id)
);

CREATE TYPE AttachmentTypes AS ENUM ('image', 'video');

CREATE TABLE post_attachment (
    id SERIAL PRIMARY KEY,
    post_id INT NOT NULL,
    url TEXT NOT NULL,
    type AttachmentTypes NOT NULL,
    FOREIGN KEY (post_id) REFERENCES post (id)
);

CREATE TABLE token (
    id SERIAL PRIMARY KEY,
    account_id INT NOT NULL UNIQUE,
    creation_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    validity_timestamp TIMESTAMP NOT NULL CHECK (validity_timestamp > creation_timestamp),
    FOREIGN KEY (account_id) REFERENCES account (id)
);

CREATE
OR REPLACE FUNCTION check_top_projects_count() RETURNS TRIGGER AS $ $ BEGIN IF (
    SELECT
        COUNT(*)
    FROM
        top_project
    WHERE
        user_stats_id = NEW.user_stats_id
) >= 10 THEN RAISE EXCEPTION 'User can have at most 10 top projects';

END IF;

RETURN NEW;

END;

$ $ LANGUAGE plpgsql;

CREATE TRIGGER limit_top_projects BEFORE
INSERT
    ON top_project FOR EACH ROW EXECUTE FUNCTION check_top_projects_count();