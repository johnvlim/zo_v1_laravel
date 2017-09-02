DROP DATABASE IF EXISTS ziplogic;

CREATE DATABASE IF NOT EXISTS ziplogic;

#customers
CREATE TABLE IF NOT EXISTS ziplogic.customers(
 	customer_username VARCHAR(30) NOT NULL, 
 	customer_password VARCHAR(100) NOT NULL, 
 	customer_name_fname VARCHAR(30) NOT NULL, 
 	customer_name_mname VARCHAR(30) NOT NULL, 
 	customer_name_lname VARCHAR(30) NOT NULL, 
 	customer_role varchar(30) NOT NULL, 
 	customer_gender VARCHAR(10), 
 	customer_address_house_building VARCHAR(30), 
 	customer_address_street VARCHAR(30), 
 	customer_address_district VARCHAR(30), 
 	customer_address_city VARCHAR(30), 
 	customer_address_postalcode VARCHAR(30), 
 	customer_address_country VARCHAR(30), 
 	customer_mobile VARCHAR(30), 
 	customer_email VARCHAR(30), 
 	customer_birthday_month VARCHAR(10), 
 	customer_birthday_date INT, 
 	customer_birthday_year INT, 
 	customer_last_change_timestamp DATETIME NOT NULL DEFAULT NOW(), 
 	customer_device_token VARCHAR(1000), 
 	PRIMARY KEY(customer_username)
 	);
 	
#companies
CREATE TABLE IF NOT EXISTS ziplogic.companies(
	company_name VARCHAR(30) NOT NULL, 
 	company_desc VARCHAR(500) NOT NULL, 
 	company_category VARCHAR(30) NOT NULL, 
 	company_logo VARCHAR(500) NOT NULL, 
 	company_last_change_timestamp DATETIME NOT NULL DEFAULT NOW(), 
 	PRIMARY KEY(company_name)
 	);
 	
 #branches
CREATE TABLE IF NOT EXISTS ziplogic.branches(
	branch_id INT NOT NULL AUTO_INCREMENT, 
 	branch_name VARCHAR(30) NOT NULL, 
 	company_name VARCHAR(30) NOT NULL, 
 	branch_address_house_building VARCHAR(30) NOT NULL, 
 	branch_address_street VARCHAR(30) NOT NULL, 
 	branch_address_district VARCHAR(30) NOT NULL, 
 	branch_address_city VARCHAR(30) NOT NULL, 
 	branch_address_postalcode VARCHAR(30) NOT NULL, 
 	branch_address_country VARCHAR(30) NOT NULL, 
 	branch_hotline VARCHAR(10) NOT NULL, 
 	branch_last_change_timestamp DATETIME NOT NULL DEFAULT NOW(), 
 	PRIMARY KEY(branch_id), 
 	UNIQUE(branch_name, company_name), 
 	FOREIGN KEY(company_name) REFERENCES ziplogic.companies(company_name)
 	);
 	
#menus
CREATE TABLE IF NOT EXISTS ziplogic.menus(
 	menu_id INT NOT NULL AUTO_INCREMENT, 
 	menu_name VARCHAR(30) NOT NULL, 
 	company_name VARCHAR(30) NOT NULL, 
 	menu_desc VARCHAR(500) NOT NULL, 
 	menu_image VARCHAR(1000) NOT NULL, 
 	menu_last_change_timestamp DATETIME NOT NULL DEFAULT NOW(), 
 	PRIMARY KEY(menu_id), 
 	UNIQUE(menu_name, company_name), 
 	FOREIGN KEY(company_name) REFERENCES ziplogic.companies(company_name)
 	);
 	
#tables
CREATE TABLE IF NOT EXISTS ziplogic.tables(
 	table_id INT NOT NULL AUTO_INCREMENT, 
 	table_number INT NOT NULL, 
 	branch_id INT NOT NULL, 
 	table_capacity INT NOT NULL, 
 	table_status VARCHAR(30), 
 	table_status_change_timestamp DATETIME NOT NULL, 
 	table_last_change_timestamp DATETIME NOT NULL DEFAULT NOW(), 
 	PRIMARY KEY(table_id), 
 	UNIQUE(table_number, branch_id), 
 	FOREIGN KEY(branch_id) REFERENCES ziplogic.branches(branch_id)
 	);
 	
#menuitems
CREATE TABLE IF NOT EXISTS ziplogic.menuitems(
 	menuitem_id INT NOT NULL AUTO_INCREMENT, 
 	menuitem_code VARCHAR(10) NOT NULL, 
 	menu_id INT NOT NULL, 
 	menuitem_name VARCHAR(30) NOT NULL, 
 	menuitem_desc VARCHAR(500) NOT NULL, 
 	menuitem_price DOUBLE NOT NULL, 
 	menuitem_featured BOOLEAN NOT NULL, 
 	menuitem_image VARCHAR(500) NOT NULL, 
 	menuitem_last_change_timestamp DATETIME NOT NULL DEFAULT NOW(), 
 	PRIMARY KEY(menuitem_id), 
 	UNIQUE(menuitem_code, menu_id), 
	FOREIGN KEY(menu_id) REFERENCES ziplogic.menus(menu_id)
	);
	
#orderreferences
CREATE TABLE IF NOT EXISTS ziplogic.orderreferences(
	orderreference_id INT NOT NULL AUTO_INCREMENT, 
	orderreference_code VARCHAR(40) NOT NULL, 
	customer_username VARCHAR(30) NOT NULL, 
	table_id INT NOT NULL, 
	orderreference_status VARCHAR(30) NOT NULL, 
	orderreference_status_change_timestamp DATETIME NOT NULL, 
	orderreference_last_change_timestamp DATETIME NOT NULL DEFAULT NOW(), 
	PRIMARY KEY(orderreference_id), 
	UNIQUE(orderreference_code), 
	FOREIGN KEY(customer_username) REFERENCES ziplogic.customers(customer_username), 
	FOREIGN KEY(table_id) REFERENCES ziplogic.tables(table_id)
	);
	
#orders
CREATE TABLE IF NOT EXISTS ziplogic.orders(
	order_id INT NOT NULL AUTO_INCREMENT, 
	menuitem_id INT NOT NULL, 
	orderreference_code VARCHAR(40) NOT NULL, 
	order_status VARCHAR(30) NOT NULL, 
	order_status_change_timestamp DATETIME NOT NULL, 
	order_last_change_timestamp DATETIME NOT NULL DEFAULT NOW(), 
	PRIMARY KEY(order_id), 
	FOREIGN KEY(menuitem_id) REFERENCES ziplogic.menuitems(menuitem_id), 
	FOREIGN KEY(orderreference_code) REFERENCES ziplogic.orderreferences(orderreference_code)
	);
	
#reservations
CREATE TABLE IF NOT EXISTS ziplogic.reservations(
	reservation_id INT NOT NULL AUTO_INCREMENT, 
	reservation_code VARCHAR(40) NOT NULL, 
	customer_username VARCHAR(30) NOT NULL, 
	orderreference_code VARCHAR(40) NOT NULL, 
	reservation_diners_count INT NOT NULL, 
	reservation_eta DATETIME NOT NULL, 
	reservation_payment_mode VARCHAR(30) NOT NULL, 
	reservation_service_time DATETIME NOT NULL, 
	reservation_status VARCHAR(30) NOT NULL, 
	reservation_status_change_timestamp DATETIME NOT NULL, 
	reservation_last_change_timestamp DATETIME NOT NULL DEFAULT NOW(), 
	PRIMARY KEY(reservation_id), 
	UNIQUE(reservation_code), 
	FOREIGN KEY(customer_username) REFERENCES ziplogic.customers(customer_username), 
	FOREIGN KEY(orderreference_code) REFERENCES ziplogic.orderreferences(orderreference_code)
	);
	
#customers_companies_branches
CREATE TABLE IF NOT EXISTS ziplogic.customers_companies_branches(
 	customer_username VARCHAR(30) NOT NULL, 
 	company_name VARCHAR(30) NOT NULL, 
 	branch_id INT NOT NULL, 
 	customerCompanyBranch_last_change_timestamp DATETIME NOT NULL DEFAULT NOW(), 
 	PRIMARY KEY(customer_username), 
 	FOREIGN KEY(customer_username) REFERENCES ziplogic.customers(customer_username), 
 	FOREIGN KEY(company_name) REFERENCES ziplogic.companies(company_name), 
 	FOREIGN KEY(branch_id) REFERENCES ziplogic.branches(branch_id)
 	);
 	
#advertisements
CREATE TABLE IF NOT EXISTS ziplogic.advertisements(
 	advertisement_id INT NOT NULL AUTO_INCREMENT, 
 	company_name VARCHAR(30) NOT NULL, 
 	advertisement_title VARCHAR(100) NOT NULL, 
 	advertisement_content VARCHAR(1000) NOT NULL, 
 	advertisement_price DOUBLE NOT NULL, 
 	advertisement_image VARCHAR(500) NOT NULL, 
 	advertisement_url VARCHAR(500) NOT NULL, 
 	advertisement_last_change_timestamp DATETIME NOT NULL DEFAULT NOW(), 
 	PRIMARY KEY(advertisement_id), 
 	FOREIGN KEY(company_name) REFERENCES ziplogic.companies(company_name)
 	);
 	
#blogs
CREATE TABLE IF NOT EXISTS ziplogic.blogs(
 	blog_id INT NOT NULL AUTO_INCREMENT, 
 	blog_title VARCHAR (100) NOT NULL, 
 	blog_author VARCHAR(50) NOT NULL, 
 	blog_content VARCHAR(1000) NOT NULL, 
 	blog_image VARCHAR(500) NOT NULL, 
 	blog_url VARCHAR(500) NOT NULL, 
 	blog_last_change_timestamp DATETIME NOT NULL DEFAULT NOW(), 
	PRIMARY KEY(blog_id)
	);