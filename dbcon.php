<?php
	$connectionArray=array(
		"connectionMethod"=>"mysqli", //mysql, mysqli, oci8, mssql ....
		"dbPort"=>"3306",
		"dbHost"=>"self_db_host", //locahost
		"dbUser"=>"self_db_user",
		"dbPassword"=>"self_db_password",
		"dbInstanceName"=>"self_db_instance_name",
		"filePrefix"=>"self_folder",
		"loggerPath"=>dirname(__FILE__) . "/../../app_log",
		"defaultRootPath"=>dirname(__FILE__) . "/../..",
		"saveHistory"=>0 //require to gen a historylog table
	);
	
	
	/*
CREATE TABLE history_log(
	log_id INT(10) AUTO_INCREMENT NOT NULL,
	log_datetime DATETIME NOT NULL,
	
	log_table_name VARCHAR(300) CHARACTER SET UTF8 COLLATE utf8_unicode_ci NOT NULL,
	ref_id INT(10) NOT NULL,
	log_detail LONGTEXT CHARACTER SET UTF8 COLLATE utf8_unicode_ci NOT NULL,
	
	CONSTRAINT history_log_pk PRIMARY KEY(log_id)
);
	*/
?>
