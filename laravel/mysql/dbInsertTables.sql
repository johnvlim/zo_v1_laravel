USE ziplogic;

# ==========
# RECORD 1
# ==========
SET @table_number = 1;
SET @branch_id = 1;
SET @table_capacity = 5;
SET @table_status = "vacant";
SET @table_status_change_timestamp = "2017-03-14 12:37:00";

INSERT INTO tables(
	table_number, 
	branch_id, 
	table_capacity, 
	table_status, 
	table_status_change_timestamp
	)
VALUES(
	@table_number, 
	@branch_id, 
	@table_capacity, 
	@table_status, 
	@table_status_change_timestamp
	);
	
# ==========
# RECORD 2
# ==========
SET @table_number = 2;
SET @branch_id = 1;
SET @table_capacity = 5;
SET @table_status = "vacant";
SET @table_status_change_timestamp = "2017-03-14 12:37:00";

INSERT INTO tables(
	table_number, 
	branch_id, 
	table_capacity, 
	table_status, 
	table_status_change_timestamp
	)
VALUES(
	@table_number, 
	@branch_id, 
	@table_capacity, 
	@table_status, 
	@table_status_change_timestamp
	);