CREATE TABLE IF NOT EXISTS `bookstore`.`bookinventory` (
    id INT NOT NULL AUTO_INCREMENT,
    book_title VARCHAR(255),
    isbn VARCHAR(255),
    author VARCHAR(255),
    publish_date VARCHAR(255),
    quantity INT NOT NULL,
    sub_total FLOAT NOT NULL,
    tax FLOAT NOT NULL,
    price FLOAT NOT NULL,
    total FLOAT NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS `bookstore`.`users` (
    id INT NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255),
    email VARCHAR(255)  NOT NULL,
    address VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY(id) 
);

CREATE TABLE IF NOT EXISTS `bookstore`.`orders` (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    sub_total FLOAT,
    quantity INT NOT NULL,
    tax FLOAT,
    total FLOAT,
    PRIMARY KEY(id)
);



