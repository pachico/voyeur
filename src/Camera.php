<?php

/**
 * @author Mariano F.co Benítez Mulet <pachico@gmail.com>
 * @copyright (c) 2016-2019, Mariano F.co Benítez Mulet
 */

namespace Pachico\Voyeur;

use Behat\Mink\Session as Session,
	Behat\Mink\Driver\Selenium2Driver as Selenium2Driver;

/**
 * Camera class is a Selenium2Driver wrapper.
 * It will allow you to connect to Selenium Webdriver or Phantomjs,
 * which uses the same (sort of) API
 */
class Camera
{

	/**
	 * Just handy constants to avoid misspellings
	 */
	const DRIVER_NAME_PHAMTOMJS = 'phatomjs';
	const DRIVER_NAME_FIREFOX = 'firefox';
	const DRIVER_NAME_CHROME = 'chrome';

	/**
	 *
	 * @var Session Mink Session that interact with browser
	 */
	protected $_session = null;

	/**
	 *
	 * @param string $driver_type Or name of the Browser. Use constants to avoid misspellings
	 * @param string $hub_path Path to the Selenium/Phantomjs hub
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
	 * @return Session Returns the Mink Session
	 */
	public function get_session()
	{
		return $this->_session;
	}

}
