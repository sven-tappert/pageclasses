#
# Table structure for table 'pages'
#
CREATE TABLE pages (
	tx_sventbodyclass_css_classes varchar(150) DEFAULT '' NOT NULL,
	tx_sventbodyclass_pass_on_css tinyint(3) DEFAULT '0' NOT NULL,
	tx_sventbodyclass_stop_css_inheritance tinyint(3) DEFAULT '0' NOT NULL
);

