<?php

/**
 * @author Mariano F.co Benítez Mulet <pachico@gmail.com>
 * @copyright (c) 2016-2019, Mariano F.co Benítez Mulet
 */

namespace Pachico\Voyeur;

use \Mockery as m;

/**
 *
 */
class VoyeurTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var Voyeur
	 */
	protected $_voyeur;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->_voyeur = new Voyeur(
			$this->_get_mocked_camera(), $this->_get_mocked_film(), true
		);
	}

	/**
	 *
	 * @return string
	 */
	protected function _get_mocked_screenshot()
	{
		return file_get_contents(TEST_MOCKED_SCREENSHOT_PATH);
	}

	/**
	 *
	 * @return Behat\Mink\Session
	 */
	protected function _get_mocked_session()
	{
		$session = m::mock('Behat\Mink\Session');
		$session
			->shouldReceive('isStarted')->times(3)->andReturn(false)
			->shouldReceive('isStarted')->andReturn(true)
			->shouldReceive('start')->andReturn(true)
			->shouldReceive('stop')->andReturn(true)
			->shouldReceive('visit')->andReturn(true)
			->shouldReceive('wait')->andReturn(true)
			->shouldReceive('resizeWindow')->andReturn(true)
			->shouldReceive('executeScript')->andReturn(true)
			->shouldReceive('getScreenshot')->times(1)->andReturn($this->_get_mocked_screenshot())
			->shouldReceive('getScreenshot')->times(1)->andReturn(null)
		;
		return $session;
	}

	/**
	 *
	 * @return League\Flysystem\Filesystem
	 */
	protected function _get_mocked_file_system()
	{

		$filesystem = m::mock('League\Flysystem\Filesystem');
		$filesystem
			->shouldReceive('put')->times(1)->andReturn(true)
			->shouldReceive('put')->andReturn(false)
		;
		return $filesystem;
	}

	/**
	 *
	 * @return Pachico\Voyeur\Film
	 */
	protected function _get_mocked_film()
	{
		$film = m::mock('Pachico\Voyeur\Film');
		$film
			->shouldReceive('get_root_folder')->andReturn(TEST_PICTURE_FOLDER)
			->shouldReceive('get_filesystem')->andReturn($this->_get_mocked_file_system())
		;
		return $film;
	}

	/**
	 *
	 * @return Pachico\Voyeur\Camera
	 */
	protected function _get_mocked_camera()
	{
		$camera = m::mock('Pachico\Voyeur\Camera');
		$camera
			->shouldReceive('get_session')->andReturn($this->_get_mocked_session())
		;
		return $camera;
	}

	/**
	 * @covers Pachico\Voyeur\Voyeur::__construct
	 */
	public function test__construct()
	{
		$camera = m::mock('Pachico\Voyeur\Camera');
		$film = m::mock('Pachico\Voyeur\Film');
		$this->_voyeur = new Voyeur($camera, $film);
	}

	/**
	 * @covers Pachico\Voyeur\Voyeur::add_shot
	 */
	public function testAdd_shot()
	{
		$shot = new Shot(TEST_URI, TEST_PICTURE_FOLDER);
		$this->assertInstanceOf(
			'Pachico\Voyeur\Voyeur', $this->_voyeur->add_shot($shot)
		);
	}

	/**
	 * @covers Pachico\Voyeur\Voyeur::shoot
	 * @covers Pachico\Voyeur\Voyeur::_get_script_file_content
	 * @covers Pachico\Voyeur\Voyeur::_shoot_shot
	 */
	public function testShoot()
	{

		$this->_voyeur = new Voyeur(
			$this->_get_mocked_camera(), $this->_get_mocked_film(), false
		);

		$valid_shot = new Shot(TEST_URI, TEST_DESTINATION_FILE_NAME);
		$valid_shot
			->add_scripts(TEST_SCRIPTS_FOLDER . 'banner1.js')
			->set_window_size(1024, 800)
			->add_wait_for(1000)
			->add_wait_for(1000, '1 === 1')
		;

		$this->assertInstanceOf(
			'Pachico\Voyeur\Voyeur', $this->_voyeur->add_shot($valid_shot)
		);

		$invalid_shot = new Shot('whatever', 'whatever');
		$cannot_be_saved_shot = new Shot('whatever', 'whatever');

		$this->_voyeur->add_shot($invalid_shot)->add_shot($cannot_be_saved_shot);

		$shots = $this->_voyeur->shoot();

		$this->assertInternalType('array', $shots);

		$this->assertCount(3, $shots);

		foreach ($shots as $shot)
		{
			$this->assertInstanceOf(
				'Pachico\Voyeur\Shot', $shot
			);
		}
	}

}
