DROP TABLE IF EXISTS users CASCADE;
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  login VARCHAR(50) NOT NULL UNIQUE,
  password_hash CHAR(32) NOT NULL
);
-- admin / admin123
-- test  / qwerty
-- demo  / demo123
INSERT INTO users (login, password_hash) VALUES
  ('admin', '0192023a7bbd73250516f069df18b500'),
  ('test', 'd8578edf8458ce06fbc5bb76a58c5ca4'),
  ('demo', '62cc2d8b4bf2d8728120d052163a77df');
