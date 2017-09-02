USE ziplogic;

# ==========
# RECORD 1
# ==========
SET @orderreference_code = "cWDDDpmFb5hRCG8neSNHdWzzcSnzTL";
SET @customer_username= "johnvlim";
SET @table_id = 1;
SET @orderreference_status = "sent";
SET @orderreference_status_change_timestamp = "2017-03-14 12:37:00";

INSERT INTO orderreferences(
	orderreference_code, 
	customer_username, 
	table_id, 
	orderreference_status, 
	orderreference_status_change_timestamp
	)
VALUES(
	@orderreference_code, 
	@customer_username, 
	@table_id, 
	@orderreference_status, 
	@orderreference_status_change_timestamp
	);