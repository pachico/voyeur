<?php

/**
 * @author Mariano F.co Benítez Mulet <pachico@gmail.com>
 * @copyright (c) 2016-2019, Mariano F.co Benítez Mulet
 */

namespace Pachico\Voyeur;

/**
 *
 */
class CameraTest extends \PHPUnit_Framework_TestCase
{

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
		$this->_camera = new Camera(Camera::DRIVER_NAME_PHAMTOMJS, 'http://localhost:8910/');
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

}
