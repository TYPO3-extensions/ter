<?php

// Configuration for table "tx_ter_extensionkeys"
$TCA['tx_ter_extensionkeys'] = [
	'ctrl' => $TCA['tx_ter_extensionkeys']['ctrl'],
	'interface' => [
		'showRecordFieldList' => 'extensionkey',
	],
	'columns' => [
		'title' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionkeys.title',
			'config' => [
				'type' => 'input',
				'size' => '40',
				'max' => '250',
				'eval' => 'trim,required',
			],
		],
		'description' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionkeys.description',
			'config' => [
				'type' => 'text',
				'cols' => '40',
				'rows' => '5',
			],
		],
		'extensionkey' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionkeys.extensionkey',
			'config' => [
				'type' => 'input',
				'size' => '40',
				'max' => '30',
				'eval' => 'trim,unique,required',
			],
		],
		'ownerusername' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionkeys.ownerusername',
			'config' => [
				'type' => 'input',
				'size' => '40',
				'max' => '30',
				'eval' => 'trim',
			],
		],
		'maxstoresize' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionkeys.maxstoresize',
			'config' => [
				'type' => 'input',
				'size' => '10',
				'max' => '10',
				'eval' => 'int',
			],
		],
		'downloadcounter' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.downloadcounter',
			'config' => [
				'type' => 'input',
				'size' => '5',
				'max' => '11',
			],
		],
	],
	'types' => [
		'1' => ['showitem' => 'title,description,extensionkey,ownerusername,maxstoresize,downloadcounter'],
	]
];

// Configuration for table "tx_ter_extensionmembers"
$TCA['tx_ter_extensionmembers'] = [
	'ctrl' => $TCA['tx_ter_extensionmembers']['ctrl'],
	'interface' => [
		'showRecordFieldList' => 'extensionkey,username',
	],
	'columns' => [
		'extensionkey' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionmembers.extensionkey',
			'config' => [
				'type' => 'input',
				'size' => '40',
				'max' => '30',
				'eval' => 'trim',
			],
		],
		'username' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionmembers.username',
			'config' => [
				'type' => 'input',
				'size' => '40',
				'max' => '30',
				'eval' => 'trim',
			],
		],
	],
	'types' => [
		'1' => ['showitem' => 'extensionkey,username'],
	]
];

// Configuration for table "tx_ter_extensions"
$TCA['tx_ter_extensions'] = [
	'ctrl' => $TCA['tx_ter_extensions']['ctrl'],
	'interface' => [
		'showRecordFieldList' => '',
	],
	'columns' => [
		'extensionkey' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.extensionkey',
			'config' => [
				'type' => 'input',
				'size' => '40',
				'max' => '30',
				'eval' => 'trim',
			],
		],
		'version' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.version',
			'config' => [
				'type' => 'input',
				'size' => '40',
				'max' => '50',
				'eval' => 'trim',
			],
		],
		'title' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.title',
			'config' => [
				'type' => 'input',
				'size' => '40',
				'max' => '50',
				'eval' => 'trim',
			],
		],
		'description' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.description',
			'config' => [
				'type' => 'text',
				'cols' => '40',
				'rows' => '3',
			],
		],
		'state' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.state',
			'config' => [
				'type' => 'input',
				'size' => '40',
				'max' => '15',
				'eval' => 'trim',
			],
		],
		'reviewstate' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.reviewstate',
			'config' => [
				'type' => 'input',
				'size' => '5',
				'max' => '5',
			],
		],
		'category' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.category',
			'config' => [
				'type' => 'input',
				'size' => '40',
				'max' => '30',
				'eval' => 'trim',
			],
		],
		'downloadcounter' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.downloadcounter',
			'config' => [
				'type' => 'input',
				'size' => '5',
				'max' => '5',
			],
		],
		'ismanualincluded' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.ismanualincluded',
			'config' => [
				'type' => 'check',
				'default' => '0',
			],
		],
		't3xfilemd5' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions.t3xfilemd5',
			'config' => [
				'type' => 'input',
				'size' => '32',
				'max' => '32',
				'eval' => 'trim',
			],
		],
	],
	'types' => [
		'1' => ['showitem' => 'extensionkey,version,title,description,state,reviewstate,category,downloadcounter,ismanualincluded,t3xfilemd5'],
	]
];

// Configuration for table "tx_ter_extensiondetails"
$TCA['tx_ter_extensiondetails'] = [
	'ctrl' => $TCA['tx_ter_extensiondetails']['ctrl'],
	'interface' => [
		'showRecordFieldList' => '',
	],
	'columns' => [
		'extensionuid' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.extensionuid',
			'config' => [
				'type' => 'input',
				'size' => '15',
				'max' => '11',
			],
		],
		'uploadcomment' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.uploadcomment',
			'config' => [
				'type' => 'text',
				'cols' => '40',
				'rows' => '3',
			],
		],
		'lastuploadbyusername' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.lastuploadbyusername',
			'config' => [
				'type' => 'input',
				'size' => '40',
				'max' => '30',
				'eval' => 'trim',
			],
		],
		'lastuploaddate' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.lastuploaddate',
			'config' => [
				'type' => 'input',
				'size' => '15',
				'max' => '11',
			],
		],
		'datasize' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.datasize',
			'config' => [
				'type' => 'input',
				'size' => '15',
				'max' => '11',
			],
		],
		'datasizecompressed' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.datasizecompressed',
			'config' => [
				'type' => 'input',
				'size' => '15',
				'max' => '11',
			],
		],
		'files' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.files',
			'config' => [
				'type' => 'text',
				'cols' => '40',
				'rows' => '3',
			],
		],
		'codelines' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.codelines',
			'config' => [
				'type' => 'input',
				'size' => '15',
				'max' => '11',
			],
		],
		'codebytes' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.codebytes',
			'config' => [
				'type' => 'input',
				'size' => '15',
				'max' => '11',
			],
		],
		'techinfo' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.techinfo',
			'config' => [
				'type' => 'text',
				'cols' => '40',
				'rows' => '3',
			],
		],
		'composerinfo' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.composerinfo',
			'config' => [
				'type' => 'text',
				'cols' => '40',
				'rows' => '3',
			],
		],
		'shy' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.shy',
			'config' => [
				'type' => 'check',
				'default' => '0',
			],
		],
		'dependencies' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.dependencies',
			'config' => [
				'type' => 'text',
				'cols' => '40',
				'rows' => '3',
			],
		],
		'createdirs' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.createdirs',
			'config' => [
				'type' => 'text',
				'cols' => '40',
				'rows' => '3',
			],
		],
		'priority' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.priority',
			'config' => [
				'type' => 'input',
				'size' => '15',
				'max' => '10',
			],
		],
		'modules' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.modules',
			'config' => [
				'type' => 'input',
				'size' => '40',
				'eval' => 'trim',
			],
		],
		'uploadfolder' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.uploadfolder',
			'config' => [
				'type' => 'check',
				'default' => '0',
			],
		],
		'modifytables' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.modifytables',
			'config' => [
				'type' => 'input',
				'size' => '40',
				'eval' => 'trim',
			],
		],
		'clearcacheonload' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.clearcacheonload',
			'config' => [
				'type' => 'check',
				'default' => '0',
			],
		],
		'locktype' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.locktype',
			'config' => [
				'type' => 'input',
				'size' => '5',
				'max' => '1',
			],
		],
		'authorname' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.authorname',
			'config' => [
				'type' => 'input',
				'size' => '40',
				'eval' => 'trim',
			],
		],
		'authoremail' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.authoremail',
			'config' => [
				'type' => 'input',
				'size' => '40',
				'eval' => 'trim',
			],
		],
		'authorcompany' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.authorcompany',
			'config' => [
				'type' => 'input',
				'size' => '40',
				'eval' => 'trim',
			],
		],
		'codingguidelinescompliance' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.codingguidelinescompliance',
			'config' => [
				'type' => 'input',
				'size' => '15',
				'max' => '10',
				'eval' => 'trim',
			],
		],
		'codingguidelinescompliancenote' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.codingguidelinescompliancenote',
			'config' => [
				'type' => 'input',
				'size' => '40',
				'eval' => 'trim',
			],
		],
		'loadorder' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails.loadorder',
			'config' => [
				'type' => 'input',
				'size' => '40',
				'eval' => 'trim',
			],
		],
	],
	'types' => [
		'1' => ['showitem' => 'extensionuid,uploadcomment,lastuploadbyusername,lastuploaddate,datasize,datasizecompressed,files,codelines,codebytes,techinfo,shy,dependencies,createdirs,priority,modules,uploadfolder,modifytables,clearcacheonload,locktype,authorname,authoremail,authorcompany,codingguidelinescompliance,codingguidelinescompliancenote,loadorder'],
	]
];

// Configuration for table "tx_ter_extensionqueue"
$TCA['tx_ter_extensionqueue'] = [
	'ctrl' => $TCA['tx_ter_extensionqueue']['ctrl'],
	'interface' => [
		'showRecordFieldList' => 'hidden, extensionkey, extensionuid, imported_to_fe',
	],
	'columns' => [
		'hidden' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => [
				'type' => 'check',
			],
		],
		'extensionkey' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionqueue.extensionkey',
			'config' => [
				'type' => 'input',
				'size' => '20',
				'max' => '40',
			],
		],
		'extensionuid' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionqueue.extensionuid',
			'config' => [
				'type' => 'input',
				'size' => '15',
				'max' => '11',
			],
		],
		'imported_to_fe' => [
			'label' => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionqueue.imported_into_fe',
			'config' => [
				'type' => 'checkbox'
			],
		],
	],
	'types' => [
		'1' => ['showitem' => 'extensionuid,extensionkey,imported_into_fe'],
	]
];

?>
