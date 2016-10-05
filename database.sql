CREATE TABLE restaurant
(
	id int(20) AUTO_INCREMENT PRIMARY KEY,
    title varchar(115) not null,
    cuisineName varchar(115) not null,
    openTime timestamp,
    minOrder int(20),
    description varchar(225),
    image_path text,
    address varchar(225) not null,
    cartType varchar(115) not null
)


CREATE TABLE menu
(
	id int(20) AUTO_INCREMENT PRIMARY KEY,
    menuTitle varchar(115) not null,
    restaurant_id int(20),
	FOREIGN KEY (restaurant_id) REFERENCES restaurant(id)
)

CREATE TABLE food
(
	id int(20) AUTO_INCREMENT PRIMARY KEY,
	foodTitle varchar(115) not null,
	price int(20) not null,
	menu_id int(20) not null,
	description varchar(225),
	FOREIGN KEY (menu_id) REFERENCES menu(id),
)


ALTER TABLE food
add menu_id int(20) not null,
add CONSTRAINT FOREIGN KEY (menu_id) REFERENCES menu(id),



CREATE TABLE customer
(
	id int(20) AUTO_INCREMENT PRIMARY KEY,
    name varchar(115) not null,
    email varchar(115) not null,
    password varchar(225) not null,
    address varchar(225) not null
)


CREATE TABLE orders
(
	id int(20) AUTO_INCREMENT PRIMARY KEY,
    customer_id int(20) not null,
    FOREIGN KEY (customer_id) REFERENCES customer(id)
)

CREATE TABLE orderItem
(
	id int(20) AUTO_INCREMENT PRIMARY KEY,
	order_id int(20) not null,
	food_id int(20) not null,
	quantity int(20) not null,
	FOREIGN KEY (food_id) REFERENCES food(id),
	FOREIGN KEY (order_id) REFERENCES orders(id)
	
)

-- CREATE TABLE owner
-- (
-- 	id int(20) AUTO_INCREMENT PRIMARY KEY,
--     firstName varchar(115) not null,
--     lastName varchar(115) not null,
--     email varchar(115) not null,
--     restaurant_id int(20) not null,
--     validationCode text,
--     activate tinyint(1),
--     FOREIGN KEY (restaurant_id) REFERENCES restaurant(id)
-- )