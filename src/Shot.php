<?php

/**
 * @author Mariano F.co Benítez Mulet <pachico@gmail.com>
 * @copyright (c) 2016-2019, Mariano F.co Benítez Mulet
 */

namespace Pachico\Voyeur;

/**
 *
 */
class Shot
{

	/**
	 *
	 * @var string Uri to take screenshot to
	 */
	protected $_uri;

	/**
	 *
	 * @var int Browser width in pixels
	 */
	protected $_width = null;

	/**
	 *
	 * @var int Browser height in pixels
	 */
	protected $_height = null;

	/**
	 *
	 * @var array Scripts that will be executed
	 */
	protected $_scripts = null;

	/**
	 *
	 * @var string
	 */
	protected $_destination_file = null;

	/**
	 *
	 * @var array
	 */
	protected $_wait_for = [];

	/**
	 *
	 * @return string
	 */
	public function get_uri()
	{
		return $this->_uri;
	}

	/**
	 *
	 * @return int
	 */
	public function get_width()
	{
		return $this->_width;
	}

	/**
	 *
	 * @return int
	 */
	public function get_height()
	{
		return $this->_height;
	}

	/**
	 *
	 * @return string
	 */
	public function get_scripts()
	{
		return $this->_scripts;
	}

	/**
	 *
	 * @return string
	 */
	public function get_destination_file()
	{
		return $this->_destination_file;
	}

	/**
	 *
	 * @return array
	 */
	public function get_wait_for()
	{
		return $this->_wait_for;
	}

	/**
	 *
	 * @param int $width in pixels
	 * @param int $height in pixels
	 * @return \Pachico\Voyeur\Shot
	 */
	public function set_window_size($width, $height)
	{
		$this->_width = (int) $width;
		$this->_height = (int) $height;
		return $this;
	}

	/**
	 *
	 * @param int $microseconds
	 * @param string $condition_to_be_true
	 * @return \Pachico\Voyeur\Shot
	 */
	public function add_wait_for($microseconds = 3, $condition_to_be_true = null)
	{
		$this->_wait_for[] = [
			(int) $microseconds => is_null($condition_to_be_true)
				? null
				: (string) $condition_to_be_true
		];
		return $this;
	}

	/**
	 *
	 * @param string $_scripts
	 * @return \Pachico\Voyeur\Shot
	 */
	public function add_scripts($_scripts)
	{
		$this->_scripts[] = $_scripts;
		return $this;
	}

	/**
	 *
	 * @param string $uri
	 * @param string $destination_file
	 */
	public function __construct($uri, $destination_file)
	{
		$this->_uri = (string) $uri;
		$this->_destination_file = ltrim($destination_file, '/');
	}

}
