
#
# Table structure for table 'tx_ter_extensionkeys'
#
CREATE TABLE tx_ter_extensionkeys ( 
  uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
  pid int(11) unsigned DEFAULT '0' NOT NULL,
  tstamp int(11) unsigned DEFAULT '0' NOT NULL,
  crdate int(11) unsigned DEFAULT '0' NOT NULL,
  title varchar(50) DEFAULT '' NOT NULL,
  description text NOT NULL,
  extensionkey varchar(30) DEFAULT '' NOT NULL,
  ownerusername varchar(30) DEFAULT '' NOT NULL,
  maxstoresize int(11) DEFAULT '0' NOT NULL,
  downloadcounter int(11) DEFAULT '0' NOT NULL,

  PRIMARY KEY (uid),
  KEY extkey (extensionkey,pid),
  KEY exttitle (title,pid)
);

#
# Table structure for table 'tx_ter_extensionmembers'
#
CREATE TABLE tx_ter_extensionmembers ( 
  uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,
  extensionkey varchar(30) DEFAULT '' NOT NULL,
  username varchar(30) DEFAULT '' NOT NULL,

  PRIMARY KEY (uid),
  KEY extkey (extensionkey),
  KEY usern (username)
);


#
# Table structure for table 'tx_ter_extensions'
#
CREATE TABLE tx_ter_extensions (
  uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,
  tstamp int(11) unsigned DEFAULT '0' NOT NULL,
  crdate int(11) unsigned DEFAULT '0' NOT NULL,
  extensionkey varchar(30) DEFAULT '' NOT NULL,
  version varchar(11) DEFAULT '' NOT NULL,
  title varchar(50) DEFAULT '' NOT NULL,
  description text NOT NULL,
  state varchar(15) DEFAULT '' NOT NULL,
  reviewstate int(11) DEFAULT '0' NOT NULL,
  category varchar(30) DEFAULT '' NOT NULL,
  downloadcounter int(11) DEFAULT '0' NOT NULL,
  ismanualincluded int(11) DEFAULT '0' NOT NULL,
  t3xfilemd5 varchar(32) DEFAULT '' NOT NULL,
  
  PRIMARY KEY (uid),
  KEY extkey (extensionkey,pid),
  KEY extcat (category,pid),
  KEY exttitle (title,pid)
);

#
# Table structure for table 'tx_ter_extensiondetails'
#
CREATE TABLE tx_ter_extensiondetails (
  uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
  pid int(11) unsigned DEFAULT '0' NOT NULL,
  extensionuid int(11) unsigned DEFAULT '0' NOT NULL,
  uploadcomment varchar(255) DEFAULT '' NOT NULL,
  lastuploadbyusername varchar(30) DEFAULT '0' NOT NULL,
  lastuploaddate int(11) DEFAULT '0' NOT NULL,
  datasize int(11) DEFAULT '0' NOT NULL,
  datasizecompressed int(11) DEFAULT '0' NOT NULL,
  files text NOT NULL,
  codelines int(11) DEFAULT '0' NOT NULL,
  codebytes int(11) DEFAULT '0' NOT NULL,
  techinfo text NOT NULL,
  composerinfo text NOT NULL,
  shy tinyint(4) DEFAULT '0' NOT NULL,
  dependencies text NOT NULL,
  createdirs text NOT NULL,
  priority varchar(10) DEFAULT '' NOT NULL,
  modules tinytext NOT NULL,
  uploadfolder tinyint(4) DEFAULT '0' NOT NULL,
  modifytables tinytext NOT NULL,
  clearcacheonload tinyint(4) DEFAULT '0' NOT NULL,
  locktype char(1) DEFAULT '' NOT NULL,
  authorname tinytext NOT NULL,
  authoremail tinytext NOT NULL,
  authorcompany tinytext NOT NULL,
  codingguidelinescompliance varchar(10) DEFAULT '' NOT NULL,
  codingguidelinescompliancenote tinytext NOT NULL,
  loadorder tinytext NOT NULL,
  
  PRIMARY KEY (uid),
  KEY extuid (extensionuid)
);  

#
# Table structure for table 'tx_ter_extensionqueue'
#
CREATE TABLE tx_ter_extensionqueue (
  uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,
  extensionkey varchar(30) DEFAULT '' NOT NULL,
  extensionuid int(11) DEFAULT '0' NOT NULL,
  deleted tinyint(4) DEFAULT '0' NOT NULL,
  tstamp int(11) unsigned DEFAULT '0' NOT NULL,
  crdate int(11) unsigned DEFAULT '0' NOT NULL,
  imported_to_fe tinyint(4) DEFAULT '0' NOT NULL,

  PRIMARY KEY (uid),
  KEY imported (imported_to_fe)
);

