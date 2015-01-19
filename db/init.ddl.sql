/**
 * Copyright Mhd Sulhan (ms@kilabit.info) 2014
 */

CREATE TABLE images (
	id INTEGER NOT NULL
,	mime VARCHAR
,	size INTEGER
,	width INTEGER
,	height INTEGER
,	image BLOB
,	description VARCHAR
);

CREATE TABLE feed_category (
	id INTEGER NOT NULL,
,	name VARCHAR NOT NULL
,	description VARCHAR NULL
,	logo_id INTEGER
);

CREATE TABLE feed (
	id INTEGER NOT NULL
,	feed_category_id INTEGER
,	url VARCHAR NOT NULL
,	last_fetch INTEGER NOT NULL
,	etag VARCHAR
,	last_mod INTEGER
,	description VARCHAR
,	logo BLOB
);

CREATE TABLE feed_item (
	id VARCHAR NOT NULL
,	feed_id INTEGER NOT NULL
,	url VARCHAR
,	date INTEGER
,	title VARCHAR
,	content BLOB NOT NULL
,	description VARCHAR
,	author VARCHAR
,	cover_image INTEGER
);
