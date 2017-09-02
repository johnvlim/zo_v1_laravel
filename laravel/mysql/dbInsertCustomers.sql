USE ziplogic;

# ==========
# RECORD 1
# ==========
SET @customer_username = "johnvlim";
SET @customer_password = "XxabcxX";
SET @customer_name_fname = "John Victor";
SET @customer_name_mname = "M";
SET @customer_name_lname = "Lim";
SET @customer_role = "administrator";
SET @customer_gender = "male";
SET @customer_address_house_building = "Unit 202, 2F, GKK Building";
SET @customer_address_street = "Sto. Sepulcro St.";
SET @customer_address_district = "Paco";
SET @customer_address_city = "Manila";
SET @customer_address_postalcode = "1007";
SET @customer_address_country = "Philippines";
SET @customer_mobile = "09499200458";
SET @customer_email = "johnvictorlim01@gmail.com";
SET @customer_birthday_month = "October";
SET @customer_birthday_date = 12;
SET @customer_birthday_year = 1992;
SET @customer_device_token = null;

INSERT INTO customers(
	customer_username, 
	customer_password, 
	customer_name_fname, 
	customer_name_mname, 
	customer_name_lname, 
	customer_role, 
	customer_gender, 
	customer_address_house_building, 
	customer_address_street, 
	customer_address_district, 
	customer_address_city, 
	customer_address_postalcode, 
	customer_address_country, 
	customer_mobile, 
	customer_email, 
	customer_birthday_month, 
	customer_birthday_date, 
	customer_birthday_year, 
	customer_device_token
	)
VALUES(
	@customer_username, 
	@customer_password, 
	@customer_name_fname, 
	@customer_name_mname, 
	@customer_name_lname, 
	@customer_role, 
	@customer_gender, 
	@customer_address_house_building, 
	@customer_address_street, 
	@customer_address_district, 
	@customer_address_city, 
	@customer_address_postalcode, 
	@customer_address_country, 
	@customer_mobile, 
	@customer_email, 
	@customer_birthday_month, 
	@customer_birthday_date, 
	@customer_birthday_year, 
	@customer_device_token
	);
	
# ==========
# RECORD 2
# ==========
SET @customer_username = "XxabcxX";
SET @customer_password = "johnvlim";
SET @customer_name_fname = "John Victor";
SET @customer_name_mname = "M";
SET @customer_name_lname = "Lim";
SET @customer_role = "customer";
SET @customer_gender = null;
SET @customer_address_house_building = null;
SET @customer_address_street = null;
SET @customer_address_district = null;
SET @customer_address_city = null;
SET @customer_address_postalcode = null;
SET @customer_address_country = null;
SET @customer_mobile = null;
SET @customer_email = null;
SET @customer_birthday_month = null;
SET @customer_birthday_date = null;
SET @customer_birthday_year = null;
SET @customer_device_token = null;

INSERT INTO customers(
	customer_username, 
	customer_password, 
	customer_name_fname, 
	customer_name_mname, 
	customer_name_lname, 
	customer_role, 
	customer_gender, 
	customer_address_house_building, 
	customer_address_street, 
	customer_address_district, 
	customer_address_city, 
	customer_address_postalcode, 
	customer_address_country, 
	customer_mobile, 
	customer_email, 
	customer_birthday_month, 
	customer_birthday_date, 
	customer_birthday_year, 
	customer_device_token
	)
VALUES(
	@customer_username, 
	@customer_password, 
	@customer_name_fname, 
	@customer_name_mname, 
	@customer_name_lname, 
	@customer_role, 
	@customer_gender, 
	@customer_address_house_building, 
	@customer_address_street, 
	@customer_address_district, 
	@customer_address_city, 
	@customer_address_postalcode, 
	@customer_address_country, 
	@customer_mobile, 
	@customer_email, 
	@customer_birthday_month, 
	@customer_birthday_date, 
	@customer_birthday_year, 
	@customer_device_token
	);