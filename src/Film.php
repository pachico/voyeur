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
class Film
{

	/**
	 *
	 * @var Filesystem
	 */
	protected $_filesystem = null;

	/**
	 *
	 * @var string
	 */
	protected $_root_folder = null;

	/**
	 * @todo handle if doesn't exist or is not writtable
	 * @param string $root_folder Path to root folder where screenshots will be saved
	 */
	public function __construct($root_folder)
	{
		$sanitized_path = rtrim($root_folder, '/') . '/';

		$this->_root_folder = $sanitized_path;

		$this->_filesystem = new Filesystem(
			new Local($sanitized_path)
		);
	}

	/**
	 *
	 * @param Filesystem $filesystem
	 * @return \Pachico\Voyeur\Film
	 */
	public function set_filesystem(Filesystem $filesystem)
	{
		$this->_filesystem = $filesystem;
		return $this;
	}

	/**
	 *
	 * @return Filesystem
	 */
	public function get_filesystem()
	{
		return $this->_filesystem;
	}

	/**
	 *
	 * @return string
	 */
	public function get_root_folder()
	{
		return $this->_root_folder;
	}

}
