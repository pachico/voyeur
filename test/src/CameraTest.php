<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016-2019, Mariano F.co Benítez Mulet
 */

namespace Pachico\Voyeur;

/**
 *
 */
class CameraTest extends \PHPUnit_Framework_TestCase
{

	/**
	 *
	 */
	const HUB_URI = 'http://localhost:8910/';

	/**
	 * @var Camera
	 */
	protected $_camera;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->_camera = new Camera(Camera::DRIVER_NAME_PHAMTOMJS, static::HUB_URI);
	}

	/**
	 * @covers Pachico\Voyeur\Camera::__construct
	 * @covers Pachico\Voyeur\Camera::get_session
	 */
	public function testGet_session()
	{
		$this->assertInstanceOf(
			'Behat\Mink\Session', $this->_camera->get_session()
		);
	}

	/**
	 * @covers Pachico\Voyeur\Camera::get_driver_name
	 */
	public function testGet_driver_name()
	{
		$this->assertSame(
			Camera::DRIVER_NAME_PHAMTOMJS, $this->_camera->get_driver_name()
		);
	}

	/**
	 * @covers Pachico\Voyeur\Camera::get_hub_path
	 */
	public function testGet_hub_path()
	{
		$this->assertSame(
			static::HUB_URI, $this->_camera->get_hub_path()
		);
	}

}
