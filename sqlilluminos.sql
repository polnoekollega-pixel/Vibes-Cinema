CREATE DATABASE illuminos CHARACTER SET utf8mb4;
USE illuminos;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('user','subscriber','admin') DEFAULT 'user',
  subscription_until DATE NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE movies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  description TEXT,
  category ENUM('драма','комедия','ужасы','фантастика','боевик','триллер') NOT NULL,
  type ENUM('movie','series') DEFAULT 'movie',
  year INT,
  rating DECIMAL(3,1) DEFAULT 0,
  poster VARCHAR(255),
  trailer_url VARCHAR(255),
  is_new TINYINT(1) DEFAULT 0,
  views INT DEFAULT 0
);

CREATE TABLE actors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150),
  role ENUM('actor','director') DEFAULT 'actor',
  photo VARCHAR(255)
);

CREATE TABLE movie_actors (
  movie_id INT,
  actor_id INT,
  FOREIGN KEY (movie_id) REFERENCES movies(id),
  FOREIGN KEY (actor_id) REFERENCES actors(id)
);

CREATE TABLE episodes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  series_id INT,
  season INT,
  episode INT,
  title VARCHAR(200),
  FOREIGN KEY (series_id) REFERENCES movies(id)
);

CREATE TABLE favorites (
  user_id INT,
  movie_id INT,
  PRIMARY KEY (user_id, movie_id)
);

CREATE TABLE history (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  movie_id INT,
  watched_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tariffs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50),
  price DECIMAL(10,2),
  duration_days INT,
  features TEXT
);

INSERT INTO tariffs (name,price,duration_days,features) VALUES
('Базовый', 299, 30, 'HD 720p, 1 устройство'),
('Стандарт', 499, 30, 'Full HD, 2 устройства'),
('Премиум', 799, 30, '4K Ultra HD, 4 устройства, без рекламы');