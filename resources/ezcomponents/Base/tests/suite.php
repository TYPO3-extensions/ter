<?php
/**
 * @package Base
 * @subpackage Tests
 * @version 1.1.1
 * @copyright Copyright (C) 2005, 2006 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once( "base_test.php");

/**
 * @package Base
 * @subpackage Tests
 */
class ezcBaseSuite extends ezcTestSuite
{
	public function __construct()
	{
		parent::__construct();
        $this->setName("Base");
        
		$this->addTest( ezcBaseTest::suite() );
	}

    public static function suite()
    {
        return new ezcBaseSuite();
    }
}
?>
