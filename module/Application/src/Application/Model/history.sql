/* List analysis report */
ALTER TABLE analysis_request ADD COLUMN file_created_dt_tm datetime, ADD COLUMN file_expire_dt_tm datetime AFTER extract_file_name;
ALTER TABLE  `analysis_request_report_file` ADD  `file_name` TEXT NULL AFTER  `package_has_credits_id`
ALTER TABLE  `analysis_request_report_file` ADD  `expire_date` DATETIME NULL AFTER  `file_name` ;

/* upload excel */
ALTER table risk drop column update_dt_tm;
ALTER table risk add column updated_dt_tm datetime;

ALTER table risk_has_job_function CHANGE COLUMN job_function_id job_function_id int(32) ;
ALTER table risk_has_job_function CHANGE COLUMN updated_dt_tm updated_dt_tm datetime ;

ALTER TABLE job_function ADD COLUMN rulebook_id bigint(20) NOT NULL AFTER id;
ALTER TABLE transactions ADD COLUMN rulebook_id bigint(20) NOT NULL AFTER id;

/* download utility config param */
insert into system_param(param_key,param_value)values('download_utility','{"fileName":"AuditCompanion-1.5.12.exe","version":"1.5.12","date":"5/15-2015"}');
ALTER TABLE  `system_param` CHANGE  `param_value`  `param_value` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ;

/**********************************************************************/
/**************** POST STAGING DEPLOYMENT   ON 26-may-2015  ***********/
/**********************************************************************/

/* script for notify and delete analysis request */
insert into system_param(param_key,param_value)values('analysis_request_first_notification_in_seconds','259200');
insert into system_param(param_key,param_value)values('analysis_request_second_notification_in_seconds','86400');
insert into system_param(param_key,param_value)values('analysis_request_third_notification_in_seconds','600');

CREATE TABLE `notification_log` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `analysis_request_id` int(11) NOT NULL, 
  `system_param_key` varchar(150) DEFAULT NULL,
  `status` enum('Pending','Sent') NOT NULL DEFAULT 'Pending',
  `created_dt_tm` datetime DEFAULT NULL,
  `updated_dt_tm` datetime DEFAULT NULL,
  `delete_flag` tinyint(1) NOT NULL, 
  PRIMARY KEY (`id`)
)

ALTER TABLE  `system_param` CHANGE  `param_key`  `param_key` VARCHAR( 150 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ;


/* Request analysis page */
ALTER TABLE analysis_request DROP key analysis_request_salt_id;
ALTER TABLE analysis_request DROP COLUMN system_salt_id;
ALTER TABLE analysis_request DROP COLUMN job_id;

/*manage users last login listing*/
ALTER TABLE `user` ADD `last_login` DATETIME NULL AFTER `activation_code` ;

/*Block user from admin*/
ALTER TABLE `user` ADD `is_blocked` TINYINT NOT NULL AFTER `delete_flag` ;


/* Adjust analysis credits */
INSERT INTO package(name,type)values('Custom Package','custom');
ALTER TABLE package_has_credits CHANGE COLUMN package_amount package_amount varchar(255) DEFAULT NULL;

ALTER TABLE `user` CHANGE `is_blocked` `is_blocked` TINYINT( 2 ) NULL DEFAULT '0';

/*System Activity WYK-143 */
CREATE TABLE `activity` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `type` varchar(150) NOT NULL,  
  `code` varchar(10) NOT NULL,  
  `created_dt_tm` datetime DEFAULT NULL,
  `updated_dt_tm` datetime DEFAULT NULL,
  `delete_flag` tinyint(1) NOT NULL, 
  PRIMARY KEY (`id`)
 );

CREATE TABLE `system_activity` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) NOT NULL,  
  `user_id` int(11) NOT NULL,  
  `comment` text DEFAULT NULL,
  `created_dt_tm` datetime DEFAULT NULL,
  `updated_dt_tm` datetime DEFAULT NULL,
  `delete_flag` tinyint(1) NOT NULL, 
  PRIMARY KEY (`id`)
 );

insert into activity(type,code)values('Purchase Analysis Credits','PAC');
insert into activity(type,code)values('Request Analysis','RA');
insert into activity(type,code)values('Download Analysis','DA');
insert into activity(type,code)values('Analysis credit removed','ACR');
insert into activity(type,code)values('Analysis deleted by user','ADU');
insert into activity(type,code)values('Extract deleted by user','EDU');
insert into activity(type,code)values('Analysis deleted by system','ADA');
insert into activity(type,code)values('Extract deleted by system','EDA');
insert into activity(type,code)values('Upload SAP Extract','USE');


/*Rule book changes */
ALTER table risk add column user_id int (11) after rulebook_id;
ALTER table job_function add column user_id int (11) after rulebook_id;
ALTER table transactions add column user_id int (11) after rulebook_id;

ALTER TABLE risk_has_job_function ADD COLUMN rulebook_id int(11) AFTER job_function_id;
ALTER TABLE job_function_has_transaction ADD COLUMN rulebook_id INT( 11 ) AFTER transaction_id;

ALTER TABLE analysis_request ADD source_spot_status ENUM('open', 'active', 'closed', 'canceled', 'failed');
ALTER TABLE analysis_request ADD webserver_spot_status ENUM('open', 'active', 'closed', 'canceled', 'failed');
ALTER TABLE analysis_request ADD instance_id VARCHAR(100);
ALTER TABLE analysis_request ADD spot_instance_req_id VARCHAR(100);
INSERT INTO system_param (param_key, param_value) VALUES ('process_analysis_cron_flag', '0');

/*Add phone number field for user*/
ALTER TABLE `user` ADD `phone_number` VARCHAR( 45 ) NULL DEFAULT NULL AFTER `zipcode` ;
ALTER TABLE  `user` CHANGE  `password`  `password` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ;

/*Insert activity code for completed audit analysis*/
INSERT INTO `auditcompanion`.`activity` (`id`, `type`, `code`, `created_dt_tm`, `updated_dt_tm`, `delete_flag`) VALUES (NULL, 'Audit Analysis Complete', 'AAC', NULL, NULL, '0');