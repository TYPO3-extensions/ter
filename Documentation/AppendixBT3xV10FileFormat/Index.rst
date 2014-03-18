.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


Appendix B: T3X V1.0 file format
================================

.T3X files are use to store all information including the code of an extension in a single file. For compatibility
reasons, TER 2.0 does not come up with a completely new format but uses the existing one, supported by many TYPO3
versions.

Basically two variants exist, an uncompressed and a gzipped file format.

The file format is quite simple: It consists of a big serialized array which holds all information and is prepended by
an MD5 hash of that serialized data followed by two colons (``::``). In case of the gzipped variant, the serialized
array is zipped and the keyword "gzcompress" is inserted between the two colons.


.. toctree::
	:maxdepth: 5
	:titlesonly:
	:glob:

	((generated))/Index

