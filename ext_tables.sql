#
# Table structure for table 'pages'
#
CREATE TABLE pages (
	tx_pageclasses_css_classes varchar(150) DEFAULT '' NOT NULL,
	tx_pageclasses_css_classes_subpages varchar(150) DEFAULT '' NOT NULL,
	tx_pageclasses_pass_on_css tinyint(3) DEFAULT '0' NOT NULL,
	tx_pageclasses_stop_css_inheritance tinyint(3) DEFAULT '0' NOT NULL
);

