-- * ====================================================
-- * Schema creation and search path setup
-- * ====================================================

DROP SCHEMA IF EXISTS lbaw2411 CASCADE;
CREATE SCHEMA lbaw2411;
SET search_path TO lbaw2411;


-- * ====================================================
-- * Enum and type creation
-- * ====================================================

CREATE TYPE attachment_type AS ENUM (
    'image',
    'video'
);

CREATE TYPE status_values AS ENUM (
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


-- * ====================================================
-- * Table creation
-- * ====================================================

CREATE TABLE user_stats (
    id SERIAL,
    github_url TEXT,
    gitlab_url TEXT,
    linkedin_url TEXT,
    PRIMARY KEY (id)
);

-- Plural used because "user" is a reserved keyword in PostgreSQL
CREATE TABLE users (
    id SERIAL,
    user_stats_id INTEGER NOT NULL UNIQUE,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    register_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    handle TEXT NOT NULL UNIQUE,
    is_public BOOLEAN NOT NULL,
    description TEXT,
    profile_picture_url TEXT,
    banner_image_url TEXT,
    num_followers INTEGER NOT NULL DEFAULT 0,
    num_following INTEGER NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (user_stats_id) REFERENCES user_stats (id) ON UPDATE CASCADE
);

CREATE TABLE administrator (
    id SERIAL,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    register_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
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
    user_stats_id INTEGER NOT NULL,
    name TEXT NOT NULL,
    url TEXT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_stats_id) REFERENCES user_stats (id) ON UPDATE CASCADE
);

CREATE TABLE post (
    id SERIAL,
    author_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    text TEXT,
    creation_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_announcement BOOLEAN NOT NULL DEFAULT FALSE,
    is_public BOOLEAN NOT NULL,
    likes INTEGER NOT NULL DEFAULT 0,
    comments INTEGER NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (author_id) REFERENCES users (id) ON UPDATE CASCADE
);

CREATE TABLE post_like (
    id SERIAL,
    liker_id INTEGER NOT NULL,
    post_id INTEGER NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (liker_id) REFERENCES users (id) ON UPDATE CASCADE,
    FOREIGN KEY (post_id) REFERENCES post (id) ON UPDATE CASCADE
);

CREATE TABLE post_attachment (
    id SERIAL,
    post_id INTEGER NOT NULL,
    url TEXT NOT NULL,
    type attachment_type NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (post_id) REFERENCES post (id) ON UPDATE CASCADE
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
    likes INTEGER NOT NULL DEFAULT 0,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (post_id) REFERENCES post (id) ON UPDATE CASCADE,
    FOREIGN KEY (author_id) REFERENCES users (id) ON UPDATE CASCADE
);

CREATE TABLE comment_like (
    id SERIAL,
    comment_id INT NOT NULL,
    liker_id INT NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (comment_id) REFERENCES comment (id) ON UPDATE CASCADE,
    FOREIGN KEY (liker_id) REFERENCES users (id) ON UPDATE CASCADE
);

CREATE TABLE follow (
    id SERIAL,
    follower_id INTEGER NOT NULL,
    followed_id INTEGER NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE (follower_id, followed_id),
    FOREIGN KEY (follower_id) REFERENCES users (id) ON UPDATE CASCADE,
    FOREIGN KEY (followed_id) REFERENCES users (id) ON UPDATE CASCADE,
    CONSTRAINT not_self_follow CHECK (follower_id <> followed_id)
);

CREATE TABLE follow_request (
    id SERIAL,
    follower_id INTEGER NOT NULL,
    followed_id INTEGER NOT NULL,
    creation_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status status_values NOT NULL DEFAULT 'pending',
    PRIMARY KEY (id),
    FOREIGN KEY (follower_id) REFERENCES users (id) ON UPDATE CASCADE,
    FOREIGN KEY (followed_id) REFERENCES users (id) ON UPDATE CASCADE,
    CONSTRAINT not_self_follow CHECK (follower_id <> followed_id)
);

CREATE TABLE ban (
    id SERIAL,
    user_id INTEGER NOT NULL,
    administrator_id INTEGER NOT NULL,
    start TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reason TEXT NOT NULL,
    duration INTERVAL NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE,
    FOREIGN KEY (administrator_id) REFERENCES administrator (id) ON UPDATE CASCADE
);

CREATE TABLE token (
    id SERIAL,
    value TEXT NOT NULL,
    user_id INTEGER,
    administrator_id INTEGER,
    creation_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    validity_timestamp TIMESTAMP NOT NULL,
    PRIMARY KEY (id),
    UNIQUE (user_id),
    UNIQUE (administrator_id),
    FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE,
    FOREIGN KEY (administrator_id) REFERENCES administrator (id) ON UPDATE CASCADE,
    CONSTRAINT account_fk_not_null CHECK ((user_id IS NULL) <> (administrator_id IS NULL)),
    CONSTRAINT validity_after_creation CHECK (validity_timestamp > creation_timestamp)
);

-- Plural used because "group" is a reserved keyword in PostgreSQL
CREATE TABLE groups (
    id SERIAL,
    owner_id INTEGER NOT NULL,
    name TEXT NOT NULL UNIQUE,
    description TEXT,
    creation_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_public BOOLEAN NOT NULL,
    member_count INTEGER NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    FOREIGN KEY (owner_id) REFERENCES users (id)
);

CREATE TABLE group_member (
    user_id INTEGER NOT NULL,
    group_id INTEGER NOT NULL,
    join_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, group_id),
    FOREIGN KEY (user_id) REFERENCES users (id),
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
    status status_values NOT NULL DEFAULT 'pending',
    PRIMARY KEY (id),
    FOREIGN KEY (group_id) REFERENCES groups (id) ON UPDATE CASCADE,
    FOREIGN KEY (requester_id) REFERENCES users (id) ON UPDATE CASCADE
);

CREATE TABLE group_invitation (
    id SERIAL,
    group_id INTEGER NOT NULL,
    invitee_id INTEGER NOT NULL,
    creation_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status status_values NOT NULL DEFAULT 'pending',
    PRIMARY KEY (id),
    FOREIGN KEY (group_id) REFERENCES groups (id) ON UPDATE CASCADE,
    FOREIGN KEY (invitee_id) REFERENCES users (id) ON UPDATE CASCADE
);

CREATE TABLE notification (
    id SERIAL,
    receiver_id INTEGER NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_read BOOLEAN DEFAULT FALSE,
    type notification_type NOT NULL,
    follow_id INTEGER,
    post_id INTEGER,
    comment_id INTEGER,
    post_like_id INTEGER,
    comment_like_id INTEGER,
    PRIMARY KEY (id),
    FOREIGN KEY (receiver_id) REFERENCES users (id) ON UPDATE CASCADE,
    FOREIGN KEY (follow_id) REFERENCES follow (id) ON UPDATE CASCADE,
    FOREIGN KEY (post_id) REFERENCES post (id) ON UPDATE CASCADE,
    FOREIGN KEY (comment_id) REFERENCES comment (id) ON UPDATE CASCADE,
    FOREIGN KEY (post_like_id) REFERENCES post_like (id) ON UPDATE CASCADE,
    FOREIGN KEY (comment_like_id) REFERENCES comment_like (id) ON UPDATE CASCADE
);


-- * ====================================================
-- * Index Creation
-- * ====================================================

CREATE INDEX notification_receiver_timestamp ON notification USING btree(receiver_id, timestamp DESC);

CREATE INDEX comment_post_likes ON comment USING btree(post_id, likes DESC);

CREATE INDEX post_author_creation ON post USING btree(author_id, creation_timestamp DESC);


-- * ====================================================
-- * Search Indexes
-- * ====================================================

ALTER TABLE post
ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION post_search_update()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT'
    OR (TG_OP = 'UPDATE' AND (NEW.title <> OLD.title OR NEW.text <> OLD.text))
    THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english', NEW.title), 'A') ||
            setweight(to_tsvector('english', (
                SELECT users.name
                FROM users
                WHERE users.id = NEW.author_id
            )), 'A') ||
            setweight(to_tsvector('english', (
                SELECT users.handle
                FROM users
                WHERE users.id = NEW.author_id
            )), 'A') ||
            setweight(to_tsvector('english', coalesce(NEW.text, '')), 'B') ||
            setweight(to_tsvector('english', (
                SELECT coalesce(string_agg(comment.content, ' '), '')
                FROM comment
                WHERE comment.post_id = NEW.id
            )), 'C')
        );
    END IF;
    RETURN NEW;
END
$$
LANGUAGE plpgsql;

CREATE TRIGGER post_search_update
BEFORE INSERT OR UPDATE ON post
FOR EACH ROW
EXECUTE PROCEDURE post_search_update();


CREATE FUNCTION post_comment_search_update()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN
        IF TG_OP = 'UPDATE' AND NEW.content <> OLD.content THEN
            UPDATE post
            SET tsvectors = (
                setweight(to_tsvector('english', post.title), 'A') ||
                setweight(to_tsvector('english', (
                    SELECT users.name
                    FROM users
                    WHERE users.id = post.author_id
                )), 'A') ||
                setweight(to_tsvector('english', (
                    SELECT users.handle
                    FROM users
                    WHERE users.id = post.author_id
                )), 'A') ||
                setweight(to_tsvector('english', coalesce(post.text, '')), 'B') ||
                setweight(to_tsvector('english', (
                    SELECT coalesce(string_agg(comment.content, ' '), '')
                    FROM comment
                    WHERE comment.post_id = post.id
                )), 'C'))
            WHERE post.id = NEW.post_id;
        END IF;

        RETURN NEW;

    ELSIF TG_OP = 'DELETE' THEN
        UPDATE post
        SET tsvectors = (
            setweight(to_tsvector('english', post.title), 'A') ||
            setweight(to_tsvector('english', (
                SELECT users.name
                FROM users
                WHERE users.id = post.author_id
            )), 'A') ||
            setweight(to_tsvector('english', (
                SELECT users.handle
                FROM users
                WHERE users.id = post.author_id
            )), 'A') ||
            setweight(to_tsvector('english', coalesce(post.text, '')), 'B') ||
            setweight(to_tsvector('english', (
                SELECT coalesce(string_agg(comment.content, ' '), '')
                FROM comment
                WHERE comment.post_id = post.id
            )), 'C'))
        WHERE post.id = OLD.post_id;

        RETURN OLD;
    END IF;
END
$$
LANGUAGE plpgsql;

CREATE TRIGGER post_comment_search_update
AFTER INSERT OR UPDATE OR DELETE ON comment
FOR EACH ROW
EXECUTE PROCEDURE post_comment_search_update();


CREATE FUNCTION post_author_search_update()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'UPDATE' AND (OLD.name <> NEW.name OR OLD.handle <> NEW.handle)
    THEN 
        UPDATE post
        SET tsvectors = (
            setweight(to_tsvector('english', post.title), 'A') ||
            setweight(to_tsvector('english', (
                SELECT users.name
                FROM users
                WHERE users.id = post.author_id
            )), 'A') ||
            setweight(to_tsvector('english', (
                SELECT users.handle
                FROM users
                WHERE users.id = post.author_id
            )), 'A') ||
            setweight(to_tsvector('english', coalesce(post.text, '')), 'B') ||
            setweight(to_tsvector('english', (
                SELECT coalesce(string_agg(comment.content, ' '), '')
                FROM comment
                WHERE comment.post_id = post.id
            )), 'C'))
        WHERE post.author_id = NEW.id;
    END IF;
    RETURN NEW;
END
$$
LANGUAGE plpgsql;

CREATE TRIGGER post_author_search_update
AFTER UPDATE ON users
FOR EACH ROW
EXECUTE PROCEDURE post_author_search_update();

CREATE INDEX post_search_idx ON post USING GiST (tsvectors);


ALTER TABLE users
ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION user_search_update()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT'
    OR (TG_OP = 'UPDATE' AND (NEW.name <> OLD.name OR NEW.handle <> OLD.handle OR NEW.description <> OLD.description))
    THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english', NEW.name), 'A') ||
            setweight(to_tsvector('english', NEW.handle), 'A') ||
            setweight(to_tsvector('english', coalesce(NEW.description, '')), 'B')
        );
    END IF;
    RETURN NEW;
END
$$
LANGUAGE plpgsql;

CREATE TRIGGER user_search_update
BEFORE INSERT OR UPDATE ON users 
FOR EACH ROW
EXECUTE PROCEDURE user_search_update();

CREATE INDEX user_search_idx ON users USING GiST (tsvectors);


ALTER TABLE groups
ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION group_search_update()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT'
    OR (TG_OP = 'UPDATE' AND (NEW.name <> OLD.name OR NEW.description <> OLD.description))
    THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english', NEW.name), 'A') ||
            setweight(to_tsvector('english', coalesce(NEW.description, 'batata')), 'B')            
        );
    END IF;
    RETURN NEW;
END
$$
LANGUAGE plpgsql;

CREATE TRIGGER group_search_update
BEFORE INSERT OR UPDATE ON groups
FOR EACH ROW
EXECUTE PROCEDURE group_search_update();

CREATE INDEX group_search_idx ON groups USING GIN (tsvectors);


-- * ====================================================
-- * Trigger creation
-- * ====================================================

-- * ====================================================
-- * Trigger creation: Notifications
-- * ====================================================

CREATE OR REPLACE FUNCTION notify_user_on_comment() 
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO notification (receiver_id, timestamp, is_read, type, post_id, comment_id) 
    VALUES (NEW.author_id, CURRENT_TIMESTAMP, FALSE, 'comment', NEW.post_id, NEW.id);

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_comment_insert
AFTER INSERT ON comment
FOR EACH ROW
EXECUTE FUNCTION notify_user_on_comment();


CREATE OR REPLACE FUNCTION notify_user_on_post_like()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO notification (receiver_id, timestamp, is_read, type, post_id, post_like_id) 
    VALUES ((SELECT author_id FROM post WHERE id = NEW.post_id), CURRENT_TIMESTAMP, FALSE, 'post_like', NEW.post_id, NEW.id);

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_post_like_insert
AFTER INSERT ON post_like
FOR EACH ROW
EXECUTE FUNCTION notify_user_on_post_like();


CREATE OR REPLACE FUNCTION notify_user_on_comment_like()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO notification (receiver_id, timestamp, is_read, type, comment_id, comment_like_id) 
    VALUES ((SELECT author_id FROM comment WHERE id = NEW.comment_id), CURRENT_TIMESTAMP, FALSE, 'comment_like', NEW.comment_id, NEW.id);

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_comment_like_insert
AFTER INSERT ON comment_like
FOR EACH ROW
EXECUTE FUNCTION notify_user_on_comment_like();


CREATE OR REPLACE FUNCTION notify_user_on_follow()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO notification (receiver_id, timestamp, is_read, type, follow_id) 
    VALUES (NEW.followed_id, CURRENT_TIMESTAMP, FALSE, 'follow', NEW.id);

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_follow_insert
AFTER INSERT ON follow
FOR EACH ROW
EXECUTE FUNCTION notify_user_on_follow();


-- * ====================================================
-- * Trigger creation: Group Owner
-- * ====================================================

CREATE OR REPLACE FUNCTION set_group_owner_as_member()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO group_member (user_id, group_id) 
    VALUES (NEW.owner_id, NEW.id);

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER set_group_owner_as_member
AFTER INSERT ON groups
FOR EACH ROW
EXECUTE FUNCTION set_group_owner_as_member();

-- * ====================================================
-- * Trigger creation: Join Requests
-- * ====================================================

CREATE OR REPLACE FUNCTION handle_group_invitation_acceptance()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.status = 'accepted' AND OLD.status <> 'accepted' THEN
        INSERT INTO group_member (user_id, group_id, joined_at) 
        VALUES (NEW.user_id, NEW.group_id, CURRENT_TIMESTAMP);
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_group_invitation_update
AFTER UPDATE ON group_invitation
FOR EACH ROW
EXECUTE FUNCTION handle_group_invitation_acceptance();


CREATE OR REPLACE FUNCTION handle_group_join_request_acceptance()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.status = 'accepted' AND OLD.status <> 'accepted' THEN
        INSERT INTO group_member (user_id, group_id, joined_at) 
        VALUES (NEW.requester_id, NEW.group_id, CURRENT_TIMESTAMP);
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_group_join_request_update
AFTER UPDATE ON group_join_request
FOR EACH ROW
EXECUTE FUNCTION handle_group_join_request_acceptance();


-- * ====================================================
-- * Trigger creation: Enforcements
-- * ====================================================

CREATE OR REPLACE FUNCTION enforce_different_post_author()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.liker_id = (SELECT author_id FROM post WHERE id = NEW.post_id)  THEN
        RAISE EXCEPTION 'A user cannot like their own post';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER before_post_like_insert
BEFORE INSERT ON post_like
FOR EACH ROW
EXECUTE FUNCTION enforce_different_post_author();


CREATE OR REPLACE FUNCTION enforce_different_comment_author()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.liker_id = (SELECT author_id FROM comment WHERE id = NEW.comment_id) THEN
        RAISE EXCEPTION 'A user cannot like their own comment';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER before_comment_like_insert
BEFORE INSERT ON comment_like
FOR EACH ROW
EXECUTE FUNCTION enforce_different_comment_author();


CREATE OR REPLACE FUNCTION enforce_group_owner_is_member()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.owner_id NOT IN (SELECT user_id FROM group_member WHERE group_id = NEW.id) THEN
        RAISE EXCEPTION 'Group owner must be a member of the group';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER enforce_group_owner_is_member
BEFORE UPDATE ON groups
FOR EACH ROW
EXECUTE FUNCTION enforce_group_owner_is_member();

-- * ====================================================
-- * Trigger creation: Derived Attributes
-- * ====================================================

CREATE FUNCTION update_post_likes()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        UPDATE post
        SET likes = (SELECT count(*) FROM post_like WHERE post_id = NEW.post_id)
        WHERE id = NEW.post_id;

        RETURN NEW;

    ELSIF TG_OP = 'DELETE' THEN
        UPDATE post
        SET likes = (SELECT count(*) FROM post_like WHERE post_id = OLD.post_id)
        WHERE id = OLD.post_id;

        RETURN OLD;
    END IF;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER update_post_likes
AFTER INSERT OR DELETE ON post_like
FOR EACH ROW
EXECUTE FUNCTION update_post_likes();

CREATE FUNCTION update_comment_likes()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        UPDATE comment
        SET likes = (SELECT count(*) FROM comment_like where comment_id = NEW.comment_id)
        WHERE id = NEW.comment_id;

        RETURN NEW;

    ELSIF TG_OP = 'DELETE' THEN
        UPDATE comment
        SET likes = (SELECT count(*) FROM comment_like where comment_id = OLD.comment_id)
        WHERE id = OLD.comment_id;

        RETURN OLD;
    END IF;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER update_comment_likes
AFTER INSERT OR DELETE ON comment_like
FOR EACH ROW
EXECUTE FUNCTION update_comment_likes();

CREATE function update_follow_counts()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN    
        UPDATE users
        SET num_followers = (SELECT count(*) FROM follow WHERE followed_id = users.id)
        WHERE users.id = NEW.followed_id;

        UPDATE users
        SET num_following = (SELECT count(*) FROM follow WHERE follower_id = users.id)
        WHERE users.id = NEW.follower_id;

        RETURN NEW;

    ELSIF TG_OP = 'DELETE' THEN
        UPDATE users
        SET num_followers = (SELECT count(*) FROM follow WHERE followed_id = users.id)
        WHERE users.id = OLD.followed_id;

        UPDATE users
        SET num_following = (SELECT count(*) FROM follow WHERE follower_id = users.id)
        WHERE users.id = OLD.follower_id;

        RETURN OLD;
    END IF;    
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER update_follow_counts
BEFORE INSERT OR DELETE ON follow
FOR EACH ROW
EXECUTE FUNCTION update_follow_counts();


CREATE FUNCTION update_comment_count()
RETURNS TRIGGER AS $$ 
BEGIN 
    IF TG_OP = 'INSERT' THEN 
        UPDATE post
        SET comments = (SELECT COUNT(*) FROM comment WHERE post_id = id)
        WHERE id = NEW.post_id;

        RETURN NEW;

    ELSIF TG_OP = 'DELETE' THEN 
        UPDATE post
        SET comments = (SELECT COUNT(*) FROM comment WHERE post_id = id)
        WHERE id = OLD.post_id;

        RETURN OLD;
    END IF;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER update_comment_count
AFTER INSERT OR DELETE ON comment
FOR EACH ROW
EXECUTE FUNCTION update_comment_count();


CREATE FUNCTION update_member_count()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        UPDATE groups 
        SET member_count = ( SELECT COUNT (*) from group_member where group_id = id)
        WHERE id = NEW.group_id;

        RETURN NEW;

    ELSIF TG_OP = 'DELETE' THEN 
        UPDATE groups 
        SET member_count = ( SELECT COUNT (*) from group_member where group_id = id)
        WHERE id = OLD.group_id;

        RETURN OLD;
    END IF;
    
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER update_member_count
AFTER INSERT OR DELETE ON group_member
FOR EACH ROW
EXECUTE FUNCTION update_member_count();


-- * ====================================================
-- * Transactions
-- * ====================================================

-- Post creation 
-- BEGIN TRANSACTION;
-- SET TRANSACTION ISOLATION LEVEL SERIALIZABLE READ WRITE;

-- -- Create post
-- INSERT INTO post(author_id, title, text, is_announcement, is_public)
-- VALUES ($author_id, $title, $text, $is_announcement, $is_public);

-- -- Associate tag
-- INSERT INTO post_tag(post_id, tag_id)
-- VALUES (currval('post_id_seq'), $tag_id);

-- -- Add attachment
-- INSERT INTO post_attachment(post_id, url, attachment_type)
-- VALUES (currval('post_id_seq'), $url, $attachment_type);

-- END TRANSACTION;

-- User registration
-- BEGIN TRANSACTION;
-- SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;
--     INSERT INTO user_stats DEFAULT VALUES;
--     INSERT INTO user(user_stats_id, name, email, password, handle, is_public, description, profile_picture_url, banner_image_url)
--     VALUES (currval('user_stats_id_seq'), $name, $email, $password, $is_public, $description, $profile_picture_url, $banner_image_url);
-- END TRANSACTION;