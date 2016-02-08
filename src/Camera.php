<?php

/**
 * @author Mariano F.co Benítez Mulet <pachico@gmail.com>
 * @copyright (c) 2016-2019, Mariano F.co Benítez Mulet
 */

namespace Pachico\Voyeur;

use Behat\Mink\Session as Session,
	Behat\Mink\Driver\Selenium2Driver as Selenium2Driver;

/**
 * 
 */
class Camera
{

	/**
	 *
	 */
	const DRIVER_NAME_PHAMTOMJS = 'phatomjs';
	const DRIVER_NAME_FIREFOX = 'firefox';
	const DRIVER_NAME_CHROME = 'chrome';

	/**
	 *
	 * @var Session
	 */
	protected $_session = null;

	/**
	 *
	 * @var string
	 */
	protected $_driver_name = null;

	/**
	 *
	 * @param string $driver_type
	 * @param string $hub_path
	 * @param array $desired_capabilities @see https://code.google.com/p/selenium/wiki/DesiredCapabilities
	 */
	public function __construct($driver_type, $hub_path, array $desired_capabilities = null)
	{

		$driver = new Selenium2Driver(
			$driver_type, $desired_capabilities, $hub_path
		);

		$this->_session = new Session($driver);
	}

	/**
	 *
	 * @return Session
	 */
	public function get_session()
	{
		return $this->_session;
	}

}
