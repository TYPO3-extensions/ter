<?php


$TCA['tx_ter_extensionkeys'] = Array (
	'ctrl' => $TCA['tx_ter_extensionkeys']['ctrl'],
	'interface' => Array (
		'showRecordFieldList' => 'extensionkey'
	),
	'columns' => Array (	
		'title' => Array (
			'label' => 'Title',
			'config' => Array (
				'type' => 'input',
				'size' => '40',
				'max' => '256',
				'eval' => 'trim'
			)
		),
		'description' => Array (
			'label' => 'LLL:EXT:ter/locallang_tca.php:tx_ter_keytable.description',
			'config' => Array (
				'type' => 'text',
				'cols' => '40',	
				'rows' => '5'
			)
		),
		'extensionkey' => Array (
			'label' => 'LLL:EXT:ter/locallang_tca.php:tx_ter_keytable.extensionkey',
			'config' => Array (
				'type' => 'input',
				'size' => '40',
				'max' => '30',
				'eval' => 'trim,unique,required'
			)
		),
		'uploadpassword' => Array (
			'label' => 'LLL:EXT:ter/locallang_tca.php:tx_ter_keytable.uploadpassword',
			'config' => Array (
				'type' => 'input',
				'size' => '40',
				'max' => '30',
				'eval' => 'trim'
			)
		),
		'maxstoresize' => Array (
			'label' => 'LLL:EXT:ter/locallang_tca.php:tx_ter_keytable.maxstoresize',
			'config' => Array (
				'type' => 'input',
				'size' => '10',
				'max' => '10',
				'eval' => 'int'
			)
		),
	),
	'types' => Array (	
		'1' => Array('showitem' => 'hidden;;;;1-1-1, title,description,ownerusername,uploadpassword,maxstoressize')
	)
);

?>