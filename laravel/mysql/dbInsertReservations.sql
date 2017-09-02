USE ziplogic;

# ==========
# RECORD 1
# ==========
SET @reservation_code = "cWDDDpmFb5hRCG8neSNHdWzzcSnzTL";
SET @customer_username = "johnvlim";
SET @orderreference_code = "cWDDDpmFb5hRCG8neSNHdWzzcSnzTL";
SET @reservation_diners_count = 5;
SET @reservation_eta = "2017-03-14 12:37:00";
SET @reservation_payment_mode = "cash";
SET @reservation_service_time = "2017-03-14 12:37:00";
SET @reservation_status = "sent";
SET @reservation_status_change_timestamp = "2017-03-14 12:37:00";

INSERT INTO reservations(
	reservation_code, 
	customer_username, 
	orderreference_code, 
	reservation_diners_count, 
	reservation_eta, 
	reservation_payment_mode, 
	reservation_service_time, 
	reservation_status, 
	reservation_status_change_timestamp
	)
VALUES(
	@reservation_code, 
	@customer_username, 
	@orderreference_code, 
	@reservation_diners_count, 
	@reservation_eta, 
	@reservation_payment_mode, 
	@reservation_service_time, 
	@reservation_status, 
	@reservation_status_change_timestamp
	);