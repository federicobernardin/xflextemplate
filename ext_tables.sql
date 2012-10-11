#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (
	xtemplate varchar(255) DEFAULT '' NOT NULL,
	xflextemplate text NOT NULL,
	xft_files text NOT NULL,
);

#
# Table structure for table 'tx_xflextemplate_template'
#
CREATE TABLE tx_xflextemplate_template (
	uid int(11) NOT NULL auto_increment,
	title varchar(255) DEFAULT '' NOT NULL,
	html text NOT NULL,
	description text NOT NULL,
	xml text NOT NULL,
	typoscript text NOT NULL,
	palettes text NOT NULL,
  	version varchar(255) DEFAULT '' NOT NULL,
	enablegroup text NOT NULL,
  	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
  	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
  	deleted tinyint(3) unsigned DEFAULT '0' NOT NULL,
  	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	showinwizard tinyint(4) unsigned DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid)
);