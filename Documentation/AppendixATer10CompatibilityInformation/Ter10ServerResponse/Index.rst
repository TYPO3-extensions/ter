.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


TER 1.0 server response
-----------------------

The TER 1.0 returns a serialized array on the different requests. The
following table shows the keys and values of [FIXME]

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Key
         Key

   Description
         Description

   Example
         Example


.. container:: table-row

   Key
         uid

   Description
         UID of the extension upload (version) in the central extension
         repository

   Example
         8794


.. container:: table-row

   Key
         extension\_key

   Description
         The extension key

   Example
         templavoila


.. container:: table-row

   Key
         extension\_uid

   Description
         UID of the extension key in the central extension repository

   Example
         880


.. container:: table-row

   Key
         tstamp

   Description
         Unix timestamp of the extension upload

   Example
         1106274583


.. container:: table-row

   Key
         version

   Description
         Clear text version number of the extension upload

   Example
         0.4.0


.. container:: table-row

   Key
         version\_int

   Description
         Integer representation of the extension upload version

   Example
         4000


.. container:: table-row

   Key
         codelines

   Description
         Number of PHP codelines

   Example
         13574


.. container:: table-row

   Key
         codebytes

   Description
         Number of PHP code bytes

   Example
         532102


.. container:: table-row

   Key
         datasize

   Description
         Size in bytes of the whole extension file

   Example
         2061830


.. container:: table-row

   Key
         datasize\_gz

   Description
         Size in bytes of the whole extension file, gz compressed

   Example
         801359


.. container:: table-row

   Key
         emconf\_description

   Description
         The extension's description

   Example
         A new template mapping tool which ...


.. container:: table-row

   Key
         emconf\_title

   Description
         The extension title

   Example
         TemplaVoila!


.. container:: table-row

   Key
         emconf\_category

   Description
         One or more categories the extension falls into

   Example
         module


.. container:: table-row

   Key
         emconf\_dependencies

   Description
         Comma separated list of extension keys the extension depends on

   Example
         cms,lang,static\_info\_tables


.. container:: table-row

   Key
         emconf\_state

   Description
         State of the extension upload

   Example
         alpha


.. container:: table-row

   Key
         em\_modify\_tables

   Description
         Foreign tables the extension alters

   Example
         tt\_content


.. container:: table-row

   Key
         emconf\_author

   Description
         Author of the extension

   Example
         Kasper Skårhøj / Robert Lemke


.. container:: table-row

   Key
         emconf\_author\_company

   Description
         Company which financed the development of the extension

   Example
         The TYPO3 Association


.. container:: table-row

   Key
         emconf\_CGLcompliance

   Description
         n/a


.. container:: table-row

   Key
         emconf\_CGLcompliance\_note

   Description
         n/a


.. container:: table-row

   Key
         emconf\_TYPO3\_version\_min

   Description
         n/a

   Example
         3008000.00


.. container:: table-row

   Key
         emconf\_TYPO3\_version\_max

   Description
         n/a

   Example
         0.00


.. container:: table-row

   Key
         emconf\_PHP\_version\_min

   Description
         n/a

   Example
         0.00


.. container:: table-row

   Key
         emconf\_PHP\_version\_max

   Description
         n/a


.. container:: table-row

   Key
         emconf\_loadOrder

   Description
         n/a


.. container:: table-row

   Key
         upload\_typo3\_version

   Description
         n/a

   Example
         3.8.0-dev


.. container:: table-row

   Key
         upload\_php\_version

   Description
         n/a

   Example
         5.0.1


.. container:: table-row

   Key
         emconf\_internal

   Description
         n/a

   Example
         0


.. container:: table-row

   Key
         emconf\_uploadfolder

   Description
         n/a

   Example
         0


.. container:: table-row

   Key
         emconf\_createDirs

   Description
         n/a

   Example
         uploads/tx\_templavoila/


.. container:: table-row

   Key
         emconf\_private

   Description
         n/a

   Example
         0


.. container:: table-row

   Key
         emconf\_download\_password

   Description
         n/a

   Example
         mypassword


.. container:: table-row

   Key
         emconf\_shy

   Description
         n/a

   Example
         0


.. container:: table-row

   Key
         emconf\_module

   Description
         n/a

   Example
         cm1,cm2,mod1,mod2


.. container:: table-row

   Key
         emconf\_priority

   Description
         n/a


.. container:: table-row

   Key
         emconf\_clearCacheOnLoad

   Description
         n/a

   Example
         1


.. container:: table-row

   Key
         emconf\_lockType

   Description
         n/a


.. container:: table-row

   Key
         download\_counter

   Description
         n/a

   Example
         13828


.. container:: table-row

   Key
         tx\_extrepmgm\_appr\_fe\_user

   Description
         n/a

   Example
         0


.. container:: table-row

   Key
         tx\_extrepmgm\_appr\_status

   Description
         n/a

   Example
         0


.. container:: table-row

   Key
         crdate

   Description
         n/a

   Example
         1106274583


.. container:: table-row

   Key
         is\_manual\_included

   Description
         If a manual was found in the doc/ directory, a decimalized hash of the
         manual file will be stored according to the following formula:

         hexdec(substr(md5($d['content\_md5']),0,7));

   Example
         237628834


.. container:: table-row

   Key
         upload\_comment

   Description
         n/a

   Example
         Update with a new start-site wizard. Notice: Runs on ....


.. container:: table-row

   Key
         \_STAT\_IMPORT -> extension\_thisversion

   Description
         n/a

   Example
         13828


.. container:: table-row

   Key
         \_STAT\_IMPORT -> extension\_allversions

   Description
         n/a

   Example
         30735


.. container:: table-row

   Key
         \_MEMBERS\_ONLY

   Description
         n/a

   Example
         0


.. container:: table-row

   Key
         \_ACCESS

   Description
         n/a

   Example
         all


.. container:: table-row

   Key
         \_ICON

   Description
         n/a

   Example
         <img src=”http://ter.typo3.com/typo3temp/tx\_extrep\_52d0640b5f.gif”
         width=”21” height=”18” alt=”” />


.. container:: table-row

   Key
         \_EXTKEY\_ROW

   Description
         n/a


.. container:: table-row

   Key
         FILES

   Description
         Array of files (see table below)


.. container:: table-row

   Key
         \_MESSAGES

   Description
         An array of strings which hold messages of the TER.

   Example
         Request successful!


.. ###### END~OF~TABLE ######

When downloading an extension, all files are delivered in the
serialized array in the FILES section where the key always represents
the file name (including relative path if neccessary). By accessing
that key you get an array of meta information and the actual content
of the file, the following keys and values are available:

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Key
         Key

   Description
         Description

   Example
         Example


.. container:: table-row

   Key
         name

   Description
         The file name, same as the key of the outer array

   Example
         doc/wizard\_form.html


.. container:: table-row

   Key
         size

   Description
         Size of the file in bytes

   Example
         6595


.. container:: table-row

   Key
         mtime

   Description
         Unix timestamp of the last modification of the file

   Example
         1041932334


.. container:: table-row

   Key
         is\_executable

   Description
         If the file is (or should be) executable

   Example
         1


.. container:: table-row

   Key
         content

   Description
         The actual file content

   Example
         ...


.. container:: table-row

   Key
         content\_md5

   Description
         MD5 hash of the file content

   Example
         1d35aa13317346ba8422858107d0ee5d


.. ###### END~OF~TABLE ######
