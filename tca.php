<?php

	// Configuration for table "tx_ter_extensionkeys"
$TCA['tx_ter_extensionkeys'] = array (
	'ctrl' => $TCA['tx_ter_extensionkeys']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'extensionkey',
	),
	'columns' => array (
		'title' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionkeys.title',
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'max'  => '250',
				'eval' => 'trim,required',
			),
		),
		'description' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionkeys.description',
			'config' => array (
				'type' => 'text',
				'cols' => '40',
				'rows' => '5',
			),
		),
		'extensionkey' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionkeys.extensionkey',
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'max'  => '30',
				'eval' => 'trim,unique,required',
			),
		),
		'ownerusername' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionkeys.ownerusername',
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'max'  => '30',
				'eval' => 'trim',
			),
		),
		'maxstoresize' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionkeys.maxstoresize',
			'config' => array (
				'type' => 'input',
				'size' => '10',
				'max'  => '10',
				'eval' => 'int',
			),
		),
		'downloadcounter' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.downloadcounter',
			'config' => array (
				'type' => 'input',
				'size' => '5',
				'max'  => '11',
			),
		),
	),
	'types' => array (
		'1' => array('showitem' => 'title,description,extensionkey,ownerusername,maxstoresize,downloadcounter'),
	)
);

	// Configuration for table "tx_ter_extensionmembers"
$TCA['tx_ter_extensionmembers'] = array (
	'ctrl' => $TCA['tx_ter_extensionmembers']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'extensionkey,username',
	),
	'columns' => array (
		'extensionkey' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionmembers.extensionkey',
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'max'  => '30',
				'eval' => 'trim',
			),
		),
		'username' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionmembers.username',
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'max'  => '30',
				'eval' => 'trim',
			),
		),
	),
	'types' => array (
		'1' => array('showitem' => 'extensionkey,username'),
	)
);

	// Configuration for table "tx_ter_extensions"
$TCA['tx_ter_extensions'] = array (
	'ctrl' => $TCA['tx_ter_extensions']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => '',
	),
	'columns' => array (
		'extensionkey' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.extensionkey',
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'max'  => '30',
				'eval' => 'trim',
			),
		),
		'version' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.version',
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'max'  => '30',
				'eval' => 'trim',
			),
		),
		'title' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.title',
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'max'  => '30',
				'eval' => 'trim',
			),
		),
		'description' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.description',
			'config' => array (
				'type' => 'text',
				'cols' => '40',
				'rows' => '3',
			),
		),
		'state' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.state',
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'max'  => '15',
				'eval' => 'trim',
			),
		),
		'reviewstate' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.reviewstate',
			'config' => array (
				'type' => 'input',
				'size' => '5',
				'max'  => '5',
			),
		),
		'category' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.category',
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'max'  => '30',
				'eval' => 'trim',
			),
		),
		'downloadcounter' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.downloadcounter',
			'config' => array (
				'type' => 'input',
				'size' => '5',
				'max'  => '5',
			),
		),
		'ismanualincluded' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.ismanualincluded',
			'config' => array (
				'type'    => 'check',
				'default' => '0',
			),
		),
		't3xfilemd5' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.t3xfilemd5',
			'config' => array (
				'type' => 'input',
				'size' => '32',
				'max'  => '32',
				'eval' => 'trim',
			),
		),
	),
	'types' => array (
		'1' => array('showitem' => 'extensionkey,version,title,description,state,reviewstate,category,downloadcounter,ismanualincluded,t3xfilemd5'),
	)
);

	// Configuration for table "tx_ter_extensiondetails"
$TCA['tx_ter_extensiondetails'] = array (
	'ctrl' => $TCA['tx_ter_extensiondetails']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => '',
	),
	'columns' => array (
		'extensionuid' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.extensionuid',
			'config' => array (
				'type' => 'input',
				'size' => '15',
				'max'  => '11',
			),
		),
		'uploadcomment' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.uploadcomment',
			'config' => array (
				'type' => 'text',
				'cols' => '40',
				'rows' => '3',
			),
		),
		'lastuploadbyusername' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.lastuploadbyusername',
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'max'  => '30',
				'eval' => 'trim',
			),
		),
		'lastuploaddate' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.lastuploaddate',
			'config' => array (
				'type' => 'input',
				'size' => '15',
				'max'  => '11',
			),
		),
		'datasize' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.datasize',
			'config' => array (
				'type' => 'input',
				'size' => '15',
				'max'  => '11',
			),
		),
		'datasizecompressed' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.datasizecompressed',
			'config' => array (
				'type' => 'input',
				'size' => '15',
				'max'  => '11',
			),
		),
		'files' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.files',
			'config' => array (
				'type' => 'text',
				'cols' => '40',
				'rows' => '3',
			),
		),
		'codelines' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.codelines',
			'config' => array (
				'type' => 'input',
				'size' => '15',
				'max'  => '11',
			),
		),
		'codebytes' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.codebytes',
			'config' => array (
				'type' => 'input',
				'size' => '15',
				'max'  => '11',
			),
		),
		'techinfo' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.techinfo',
			'config' => array (
				'type' => 'text',
				'cols' => '40',
				'rows' => '3',
			),
		),
		'shy' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.shy',
			'config' => array (
				'type'    => 'check',
				'default' => '0',
			),
		),
		'dependencies' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.dependencies',
			'config' => array (
				'type' => 'text',
				'cols' => '40',
				'rows' => '3',
			),
		),
		'createdirs' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.createdirs',
			'config' => array (
				'type' => 'text',
				'cols' => '40',
				'rows' => '3',
			),
		),
		'priority' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.priority',
			'config' => array (
				'type' => 'input',
				'size' => '15',
				'max'  => '10',
			),
		),
		'modules' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.modules',
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'eval' => 'trim',
			),
		),
		'uploadfolder' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.uploadfolder',
			'config' => array (
				'type'    => 'check',
				'default' => '0',
			),
		),
		'modifytables' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.modifytables',
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'eval' => 'trim',
			),
		),
		'clearcacheonload' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.clearcacheonload',
			'config' => array (
				'type'    => 'check',
				'default' => '0',
			),
		),
		'locktype' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.locktype',
			'config' => array (
				'type' => 'input',
				'size' => '5',
				'max'  => '1',
			),
		),
		'authorname' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.authorname',
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'eval' => 'trim',
			),
		),
		'authoremail' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.authoremail',
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'eval' => 'trim',
			),
		),
		'authorcompany' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.authorcompany',
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'eval' => 'trim',
			),
		),
		'codingguidelinescompliance' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.codingguidelinescompliance',
			'config' => array (
				'type' => 'input',
				'size' => '15',
				'max'  => '10',
				'eval' => 'trim',
			),
		),
		'codingguidelinescompliancenote' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.codingguidelinescompliancenote',
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'eval' => 'trim',
			),
		),
		'loadorder' => array (
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.loadorder',
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'eval' => 'trim',
			),
		),
	),
	'types' => array (
		'1' => array('showitem' => 'extensionuid,uploadcomment,lastuploadbyusername,lastuploaddate,datasize,datasizecompressed,files,codelines,codebytes,techinfo,shy,dependencies,createdirs,priority,modules,uploadfolder,modifytables,clearcacheonload,locktype,authorname,authoremail,authorcompany,codingguidelinescompliance,codingguidelinescompliancenote,loadorder'),
	)
);

?>
