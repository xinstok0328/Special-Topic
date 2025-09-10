DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  car_brand VARCHAR(100) NOT NULL,
  car_model VARCHAR(100) NOT NULL
);
INSERT INTO users (name, email, password, car_brand, car_model)
VALUES (
  '管理員',
  'admin@gmail.com',
  '$2y$10$XvH7CkToLk9mRkThm1UtwOt6quCOE9FTqa7nJtPHuqPeDTbcsABq6',
  'Tesla',
  'Model_3'
);
