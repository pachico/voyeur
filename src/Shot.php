<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016-2019, Mariano F.co Benítez Mulet
 */

namespace Pachico\Voyeur;

/**
 * This is the page that will be screen captured,
 * plus the browser dimensions, the js to execute
 * and the time to wait before the shot is taken
 */
class Shot
{

	/**
	 *
	 * @var string A unique identifier for this shot
	 */
	protected $_id;

	/**
	 *
	 * @var string Uri to take screenshot to
	 */
	protected $_uri;

	/**
	 *
	 * @var int Browser width in pixels
	 */
	protected $_width = 1366;

	/**
	 *
	 * @var int Browser height in pixels
	 */
	protected $_height = 768;

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
	 * @var bool Whether or not the shot has been executed successfully
	 */
	protected $_completed = false;

	/**
	 *
	 * @var string
	 */
	protected $_screencapture_path;

	/**
	 *
	 * @param string $uri Uri to take screenshot of
	 * @param string $destination_file Name of the image that will be generated
	 * @param string $shot_id Idenfifier of this shot
	 */
	public function __construct($uri, $destination_file, $shot_id = null)
	{
		$this->_uri = (string) $uri;
		$this->_destination_file = ltrim($destination_file, '/');
		$this->_id = $shot_id ?
			: uniqid('', true);
	}

	/**
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->_destination_file;
	}

	/**
	 *
	 * @return string
	 */
	function get_id()
	{
		return $this->_id;
	}

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
	 * @return bool
	 */
	function is_completed()
	{
		return true === $this->_completed;
	}

	/**
	 *
	 * @param int $width In pixels
	 * @param int $height In pixels
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
	 * @param int $microseconds Time to wait in milliseconds
	 * @param string $condition_to_be_true Condition to be true after which it doesn't wait anymore
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
	 * @param string $_scripts Script that will be executed
	 * @return \Pachico\Voyeur\Shot
	 */
	public function add_scripts($_scripts)
	{
		$this->_scripts[] = $_scripts;
		return $this;
	}

	/**
	 *
	 * @param bool $_completed Mark as if completed or not
	 */
	function set_completed($_completed)
	{
		$this->_completed = (bool) $_completed;
	}

}
