USE ziplogic;

# ==========
# RECORD 1
# ==========
SET @branch_name = "Ermita";
SET @company_name = "Max's";
SET @branch_address_house_building = "4/F Robinsons Place Manila";
SET @branch_address_street = "Adriatico St.";
SET @branch_address_district = "Ermita";
SET @branch_address_city = "Manila";
SET @branch_address_postalcode = "1007";
SET @branch_address_country = "Philippines";
SET @branch_hotline = "632-0970";

INSERT  INTO branches(
	branch_name, 
	company_name, 
	branch_address_house_building, 
	branch_address_street, 
	branch_address_district, 
	branch_address_city, 
	branch_address_postalcode, 
	branch_address_country, 
	branch_hotline
	)
VALUES(
	@branch_name, 
	@company_name, 
	@branch_address_house_building, 
	@branch_address_street, 
	@branch_address_district, 
	@branch_address_city, 
	@branch_address_postalcode, 
	@branch_address_country, 
	@branch_hotline
	);