<?php
/**
 * RedUNIT_Base_Null
 * 
 * @file    RedUNIT/Base/Null.php
 * @desc    Tests handling of NULL values.
 * @author  Gabor de Mooij and the RedBeanPHP Community
 * @license New BSD/GPLv2
 *
 * (c) G.J.G.T. (Gabor) de Mooij and the RedBeanPHP Community.
 * This source file is subject to the New BSD/GPLv2 License that is bundled
 * with this source code in the file license.txt.
 */
class RedUNIT_Base_Null extends RedUNIT_Base {

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
		//NULL can change bean
		$bean = R::dispense('bean');
		$bean->bla = 'a';
		R::store($bean);
		$bean = $bean->fresh();
		asrt($bean->hasChanged('bla'), false);
		$bean->bla = null;
		asrt($bean->hasChanged('bla'), true);
		//NULL test
		$page = R::dispense('page');
		$book = R::dispense('book');
		$page->title = 'a null page';
		$page->book = $book;
		$book->title = 'Why NUll is painful..';
		R::store($page);
		$bookid = $page->book->id;
		unset($page->book);
		$id = R::store($page);
		$page = R::load('page',$id);
		$page->title = 'another title';
		R::store($page);
		pass();
		$page = R::load('page',$id);
		$page->title = 'another title';
		$page->book_id = null;
		R::store($page);
		pass();
		droptables();
		/*
		Here we test whether the column type is set correctly. Normally if you store NULL, the smallest
		type (bool/set) will be selected. However in case of a foreign key type INT should be selected because
		fks columns require matching types.
		*/
		$book=R::dispense('book');
		$page=R::dispense('page');
		$book->ownPage[] = $page;
		R::store($book);
		pass(); //survive?
		asrt($page->getMeta('cast.book_id'),'id'); //check cast
		droptables(); //again, but now the other way around
		$book=R::dispense('book');
		$page=R::dispense('page');
		$page->book = $book;
		R::store($page);
		pass();
		asrt($page->getMeta('cast.book_id'),'id');
	}

}