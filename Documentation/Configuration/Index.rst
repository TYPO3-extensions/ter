.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _configuration:

Configuration
=============

.. code-block:: typoscript

	config {
		disableCharsetHeader = 1
		disableAllHeaderCode = 1
		disablePrefixComment = 1
		debug = 0
	}

	plugin.tx_ter_pi1 {
		pid = 1320
		repositoryDir = /var/www/sites/typo3.org/fileadmin/ter/
		reviewersFrontendUsergroupUid = 123
		mirrorsFrontendUsergroupUid = 123
	}

	page = PAGE
	page.typeNum = 0
	page.10 =< plugin.tx_ter_pi1
