#
# Table structure for table 'be_users'
#
CREATE TABLE be_users
(
	tx_bedefender_auth_code    varchar(255) DEFAULT '' NOT NULL,
	tx_bedefender_auth_code_tstamp int(11) DEFAULT '0' NOT NULL,
	tx_bedefender_auth_code_use_tstamp int(11) DEFAULT '0' NOT NULL,

);
