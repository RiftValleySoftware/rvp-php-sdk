DROP TABLE IF EXISTS co_data_nodes;
DROP SEQUENCE IF EXISTS element_id_seq;
CREATE SEQUENCE element_id_seq;
CREATE TABLE co_data_nodes (
  id BIGINT NOT NULL DEFAULT nextval('element_id_seq'),
  access_class VARCHAR(255) NOT NULL,
  last_access TIMESTAMP NOT NULL,
  read_security_id BIGINT DEFAULT NULL,
  write_security_id BIGINT DEFAULT NULL,
  object_name VARCHAR(255) DEFAULT NULL,
  access_class_context TEXT DEFAULT NULL,
  owner BIGINT DEFAULT NULL,
  longitude DOUBLE PRECISION DEFAULT NULL,
  latitude DOUBLE PRECISION DEFAULT NULL,
  tag0 VARCHAR(255) DEFAULT NULL,
  tag1 VARCHAR(255) DEFAULT NULL,
  tag2 VARCHAR(255) DEFAULT NULL,
  tag3 VARCHAR(255) DEFAULT NULL,
  tag4 VARCHAR(255) DEFAULT NULL,
  tag5 VARCHAR(255) DEFAULT NULL,
  tag6 VARCHAR(255) DEFAULT NULL,
  tag7 VARCHAR(255) DEFAULT NULL,
  tag8 VARCHAR(255) DEFAULT NULL,
  tag9 VARCHAR(255) DEFAULT NULL,
  payload TEXT DEFAULT NULL
);

INSERT INTO co_data_nodes (access_class, last_access, read_security_id, write_security_id, object_name, access_class_context, owner, longitude, latitude, tag0, tag1, tag2, tag3, tag4, tag5, tag6, tag7, tag8, tag9, payload) VALUES
('CO_Main_DB_Record', '1970-01-02 00:00:00', -1, -1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

