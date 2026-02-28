-- Create and use the database
CREATE DATABASE IF NOT EXISTS xvwa;
USE xvwa;

-- 1. Create and populate 'comments' table
DROP TABLE IF EXISTS comments;
CREATE TABLE comments (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user varchar(30),
    comment varchar(100),
    date varchar(30)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO comments (id, user, comment, date) VALUES
(1, 'admin', 'Keep posting your comments here ', '10 Aug 2015');

-- 2. Create and populate 'caffaine' table (the products)
DROP TABLE IF EXISTS caffaine;
CREATE TABLE caffaine (
    itemid int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    itemcode varchar(15),
    itemdisplay varchar(500),
    itemname varchar(50),
    itemdesc varchar(1000),
    categ varchar(200),
    price varchar(20)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO caffaine (itemcode, itemdisplay, itemname, itemdesc, categ, price) VALUES
('XVWA0987', '/xvwa/img/XVWA0987.png', 'Affogato', 'An affogato (Italian, "drowned") is a coffee-based beverage...', 'Espresso,Vanilla Gelato', '4.69'),
('XVWA3876', '/xvwa/img/XVWA3876.png', 'Americano', 'An affogato (Italian, "drowned") is a coffee-based beverage...', 'Espresso', '5'),
('XVWA4589', '/xvwa/img/XVWA4589.png', 'Bicerin', 'An Americano is an espresso-based drink designed...', 'Espresso, Chocolate, Milk', '8.9'),
('XVWA7619', '/xvwa/img/XVWA7619.png', 'Café Bombón', 'Cafe Bombon was made popular in Valencia, Spain...', 'Espresso, Sweetened Milk', '7.08'),
('XVWA5642', '/xvwa/img/XVWA5642.png', 'Café au lait', 'Café au lait is a French coffee drink...', 'Coffee, Milk', '10.15'),
('XVWA7569', '/xvwa/img/XVWA7569.png', 'Caffé corretto', 'Caffè corretto is an Italian beverage...', 'Espresso, Liquor Shot', '6.01'),
('XVWA3671', '/xvwa/img/XVWA3671.png', 'Caffé latte', 'In Italy, latte means milk...', 'Espresso, Milk', '6.04'),
('XVWA1672', '/xvwa/img/XVWA1672.png', 'Café mélange', 'In Italy, latte means milk...', 'White Creame', '3.06'),
('XVWA4276', '/xvwa/img/XVWA4276.png', 'Cafe mocha', 'Café mélange is a black coffee mixed...', 'Latte, Chocolate', '4.05'),
('XVWA9680', '/xvwa/img/XVWA9680.png', 'Cappuccino', 'Caffè Mocha or café mocha, is an American invention...', 'Espresso, Milk', '3.06');

-- 3. Create and populate 'users' table
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    uid int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username varchar(20),
    password varchar(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO users (username, password) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3'),
('xvwa', '570992ec4b5ad7a313f5dc8fd0825395'),
('user', '25890deab1075e916c06b9e1efc2e25f');
