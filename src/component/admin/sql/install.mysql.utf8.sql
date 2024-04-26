CREATE TABLE IF NOT EXISTS `#__joomnews_feeds` (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    url VARCHAR(255) NOT NULL,
    thumbnail VARCHAR(255) NOT NULL,
    owner VARCHAR(100),
    last_updated DATE,
    nb_items INT NOT NULL,
    catid INT NOT NULL,
    comment VARCHAR(255),
    language VARCHAR(5) NOT NULL,
    state TINYINT NOT NULL DEFAULT '1'
);

CREATE TABLE IF NOT EXISTS `#__joomnews_feeds_items`(
    id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    url VARCHAR(255) NOT NULL,
    thumbnail VARCHAR(255),
    description TEXT,
    date DATE,
    state TINYINT NOT NULL DEFAULT '1',
    feed_id BIGINT NOT NULL,
    keywords VARCHAR(100) 
);