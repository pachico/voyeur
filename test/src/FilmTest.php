<?php

/**
 * @author Mariano F.co Benítez Mulet <pachico@gmail.com>
 * @copyright (c) 2016-2019, Mariano F.co Benítez Mulet
 */

namespace Pachico\Voyeur;

use League\Flysystem\Filesystem as Filesystem,
	League\Flysystem\Adapter\Local as Local;

/**
 *
 */
class StorageTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var Film
	 */
	protected $_storage;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->_storage = new Film(TEST_PICTURE_FOLDER);
	}

	/**
	 * @covers Pachico\Voyeur\Film::set_filesystem
	 */
	public function testSet_filesystem()
	{
		$filesystem = new Filesystem(
			new Local(TEST_PICTURE_FOLDER)
		);

		$this->_storage->set_filesystem($filesystem);

		$this->assertSame(
			$filesystem, $this->_storage->get_filesystem()
		);
	}

	/**
	 * @covers Pachico\Voyeur\Film::get_filesystem
	 */
	public function testGet_filesystem()
	{
		$this->assertInstanceOf(
			'League\Flysystem\Filesystem', $this->_storage->get_filesystem()
		);
	}

	/**
	 * @covers Pachico\Voyeur\Film::__construct
	 * @covers Pachico\Voyeur\Film::get_root_folder
	 */
	public function testGet_root_folder()
	{
		$this->assertSame(
			TEST_PICTURE_FOLDER, $this->_storage->get_root_folder()
		);

		$this->_storage = new Film(TEST_PICTURE_FOLDER . '///');

		$this->assertSame(
			TEST_PICTURE_FOLDER, $this->_storage->get_root_folder()
		);
	}

}
