USE ziplogic;

# ==========
# RECORD 1
# ==========
SET @customer_username = "johnvlim";
SET @company_name = "Max's";
SET @branch_id = 1;

INSERT INTO customers_companies_branches(
	customer_username, 
	company_name, 
	branch_id
	)
VALUES(
	@customer_username, 
	@company_name, 
	@branch_id
	);