USE mankin;

DROP TABLE IF EXISTS cart;
DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS inventory;

CREATE TABLE inventory (
  itemNumber INT(8) NOT NULL,
  description VARCHAR(80) NOT NULL,
  price DECIMAL(9, 2) NOT NULL,
  quantity INT(8) NOT NULL,
  PRIMARY KEY (itemNumber)
);

CREATE TABLE customers (
  firstName VARCHAR(80) NOT NULL,
  lastName VARCHAR(80) NOT NULL,
  streetAddress VARCHAR(80) NOT NULL,
  city VARCHAR(80) NOT NULL,
  userState VARCHAR(80) NOT NULL,
  zip VARCHAR(80) NOT NULL,
  username VARCHAR(80) NOT NULL,
  password VARCHAR(256) NOT NULL,
  PRIMARY KEY (username)
);

CREATE TABLE cart (
  itemNumber INT(8) NOT NULL,
  quantity INT(8) NOT NULL,
  username VARCHAR(80) NOT NULL,
  PRIMARY KEY (username, itemNumber),
  FOREIGN KEY (username) REFERENCES customers(username),
  FOREIGN KEY (itemNumber) REFERENCES inventory(itemNumber)
);



INSERT INTO inventory VALUES
(1,'Cthulu Duck',1.99,12),
(2,'Sonic Duck',5.99,14),
(3,'Big Duck',99.99,20),
(4,'Pika Duck',2.99,13),
(5,'Mr. T Duck',4.99,15),
(6,'Conan Duck',5.99,16),
(7,'Naruto Duck',15.99,121),
(8,'Cowboy Duck',3.99,12),
(9,'Cool Duck',9.99,12),
(10,'Duck',0.99,102);