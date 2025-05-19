-- config/freelance_db.sql
CREATE DATABASE IF NOT EXISTS freelance_db;
USE freelance_db;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  dob DATE,
  phone VARCHAR(20),
  job_type ENUM('online','offline'),
  role ENUM('buyer','seller'),
  portfolio TEXT,
  password VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users(name,email,dob,phone,job_type,role,portfolio,password)
VALUES
('Alice Hasan','alice@example.com','1990-05-10','012345678910','offline','seller','Construction expert',SHA2('pass123',256)),
('Bob Karim','bob@example.com','1988-11-22','012345678911','online','seller','Web developer',SHA2('pass123',256)),
('Charlie Rahman','charlie@example.com','1995-07-15','012345678912','online','buyer','',SHA2('pass123',256));

CREATE TABLE gigs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  seller_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  category VARCHAR(100),
  price DECIMAL(10,2) NOT NULL,
  delivery_days INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  gig_id INT NOT NULL,
  buyer_id INT NOT NULL,
  seller_id INT NOT NULL,
  order_code VARCHAR(16) UNIQUE,
  status ENUM('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  completed_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (gig_id)    REFERENCES gigs(id)  ON DELETE CASCADE,
  FOREIGN KEY (buyer_id)  REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT,
  buyer_id INT,
  seller_id INT NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  platform_fee DECIMAL(10,2) NOT NULL DEFAULT 0,
  payment_method VARCHAR(20),
  coupon_id INT NULL,
  is_bonus TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id)  REFERENCES orders(id),
  FOREIGN KEY (buyer_id)  REFERENCES users(id),
  FOREIGN KEY (seller_id) REFERENCES users(id)
);

CREATE TABLE ratings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  rater_id INT NOT NULL,
  ratee_id INT NOT NULL,
  rating TINYINT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (rater_id) REFERENCES users(id),
  FOREIGN KEY (ratee_id) REFERENCES users(id)
);

CREATE TABLE complaints (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  user_id INT NOT NULL,
  message TEXT,
  attachment VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (user_id)  REFERENCES users(id)
);

CREATE TABLE bids (
  id INT AUTO_INCREMENT PRIMARY KEY,
  gig_id INT NOT NULL,
  buyer_id INT NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (gig_id)   REFERENCES gigs(id),
  FOREIGN KEY (buyer_id) REFERENCES users(id)
);

CREATE TABLE buyer_requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  buyer_id INT NOT NULL,
  title VARCHAR(255),
  description TEXT,
  category VARCHAR(100),
  price DECIMAL(10,2),
  job_type ENUM('online','in_person'),
  location VARCHAR(255),
  maps_link VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (buyer_id) REFERENCES users(id)
);

CREATE TABLE seller_requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  buyer_request_id INT NOT NULL,
  seller_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (buyer_request_id) REFERENCES buyer_requests(id),
  FOREIGN KEY (seller_id)            REFERENCES users(id)
);

CREATE TABLE coupons (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(50),
  discount_percent INT,
  start_date DATE,
  end_date DATE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  issued_to INT NULL,
  FOREIGN KEY (issued_to) REFERENCES users(id)
);

CREATE TABLE alerts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  message TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE subscriptions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  start_date DATETIME,
  end_date DATETIME,
  payment_method VARCHAR(20),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE referrals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  referrer_id INT NOT NULL,
  referred_id INT NOT NULL,
  used_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (referrer_id) REFERENCES users(id),
  FOREIGN KEY (referred_id) REFERENCES users(id)
);
