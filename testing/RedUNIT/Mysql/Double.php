<?php
/**
 * RedUNIT_Mysql_Double
 * 
 * @file    RedUNIT/Mysql/Double.php
 * @desc    Tests handling of double precision values.
 * @author  Gabor de Mooij and the RedBeanPHP Community
 * @license New BSD/GPLv2
 *
 * (c) G.J.G.T. (Gabor) de Mooij and the RedBeanPHP Community.
 * This source file is subject to the New BSD/GPLv2 License that is bundled
 * with this source code in the file license.txt.
 */
class RedUNIT_Mysql_Double extends RedUNIT_Mysql {

	/**
	 * Begin testing.
	 * This method runs the actual test pack.
	 * 
	 * @return void
	 */
	public function run() {
		$toolbox = R::$toolbox;
		$adapter = $toolbox->getDatabaseAdapter();
		$writer  = $toolbox->getWriter();
		$redbean = $toolbox->getRedBean();
		$pdo = $adapter->getDatabase();
		$largeDouble = 999999888889999922211111; //8.88889999922211e+17;
		$page = $redbean->dispense("page");
		$page->weight = $largeDouble;
		$id = $redbean->store($page);
		$cols = $writer->getColumns("page");
		asrt($cols["weight"],"double");
		$page = $redbean->load("page", $id);
		$page->name = "dont change the numbers!";
		$redbean->store($page);
		$page = $redbean->load("page", $id);
		$cols = $writer->getColumns("page");
		asrt($cols["weight"],"double");
	}	
	
}