USE ziplogic;

# ==========
# RECORD 1
# ==========
SET @company_name = "Max's";
SET @company_desc = "Max's Restaurant";
SET @company_category = "Fine Dining";
SET @company_logo = "https://upload.wikimedia.org/wikipedia/en/d/de/Max's_Restaurant_logo.jpeg";

INSERT  INTO companies(
	company_name, 
	company_desc, 
	company_category, 
	company_logo
	)
VALUES(
	@company_name, 
	@company_desc, 
	@company_category, 
	@company_logo
	);