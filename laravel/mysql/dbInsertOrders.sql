USE ziplogic;

# ==========
# RECORD 1
# ==========
SET @menuitem_id = "1";
SET @orderreference_code = "cWDDDpmFb5hRCG8neSNHdWzzcSnzTL";
SET @order_status = "queue";
SET @order_status_change_timestamp = "2017-03-14 12:37:00";

INSERT INTO orders(
	menuitem_id, 
	orderreference_code, 
	order_status, 
	order_status_change_timestamp
	)
VALUES(
	@menuitem_id, 
	@orderreference_code, 
	@order_status, 
	@order_status_change_timestamp
	);