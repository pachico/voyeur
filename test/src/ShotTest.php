<?php

/**
 * @author Mariano F.co Benítez Mulet <pachico@gmail.com>
 * @copyright (c) 2016-2019, Mariano F.co Benítez Mulet
 */

namespace Pachico\Voyeur;

/**
 *
 */
class ShotTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var Shot
	 */
	protected $_shot;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->_shot = new Shot(TEST_URI, TEST_DESTINATION_FILE_NAME);
	}

	/**
	 * @covers Pachico\Voyeur\Shot::__construct
	 * @covers Pachico\Voyeur\Shot::get_uri
	 */
	public function testGet_uri()
	{
		$this->assertSame(
			TEST_URI, $this->_shot->get_uri()
		);
	}

	/**
	 * @covers Pachico\Voyeur\Shot::set_window_size
	 * @covers Pachico\Voyeur\Shot::get_width
	 * @covers Pachico\Voyeur\Shot::get_height
	 */
	public function testGet_width_and_height()
	{
		$this->_shot->set_window_size(1024, 800);
		$this->assertSame(1024, $this->_shot->get_width());
		$this->assertSame(800, $this->_shot->get_height());

		$this->_shot->set_window_size('1024', '800');
		$this->assertSame(1024, $this->_shot->get_width());
		$this->assertSame(800, $this->_shot->get_height());
	}

	/**
	 * @covers Pachico\Voyeur\Shot::get_scripts
	 * @covers Pachico\Voyeur\Shot::add_scripts
	 */
	public function testGet_scripts()
	{
		$this->_shot->add_scripts(TEST_SCRIPTS_FOLDER . 'banner1.js');
		$this->_shot->add_scripts(TEST_SCRIPTS_FOLDER . 'banner2.js');

		$this->assertInternalType('array', $this->_shot->get_scripts());
		$this->assertSame(
			[TEST_SCRIPTS_FOLDER . 'banner1.js', TEST_SCRIPTS_FOLDER . 'banner2.js']
			, $this->_shot->get_scripts()
		);
	}

	/**
	 * @covers Pachico\Voyeur\Shot::__construct
	 * @covers Pachico\Voyeur\Shot::get_destination_file
	 */
	public function testGet_destination_file()
	{
		$this->assertSame(
			TEST_DESTINATION_FILE_NAME, $this->_shot->get_destination_file()
		);
	}

	/**
	 * @covers Pachico\Voyeur\Shot::get_wait_for
	 * @covers Pachico\Voyeur\Shot::add_wait_for
	 */
	public function testGet_wait_for()
	{
		$this->_shot->add_wait_for(3000);
		$this->_shot->add_wait_for(5000, '1 === 1');

		$this->assertSame([
			0 => [3000 => NULL],
			1 => [5000 => '1 === 1']
			], $this->_shot->get_wait_for()
		);
	}

	/**
	 * @covers Pachico\Voyeur\Shot::__toString
	 */
	public function test__toString()
	{
		$this->assertSame(
			TEST_DESTINATION_FILE_NAME, (string) $this->_shot
		);
	}

	/**
	 * @covers Pachico\Voyeur\Shot::is_completed
	 * @covers Pachico\Voyeur\Shot::set_completed
	 */
	public function testSetIsCompleted()
	{
		$this->assertFalse($this->_shot->is_completed());

		$this->_shot->set_completed(true);
		$this->assertTrue($this->_shot->is_completed());

		$this->_shot->set_completed(0);
		$this->assertFalse($this->_shot->is_completed());

		$this->_shot->set_completed(1);
		$this->assertTrue($this->_shot->is_completed());
	}

}
