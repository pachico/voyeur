<?php

/**
 * @author Mariano F.co Benítez Mulet <pachico@gmail.com>
 * @copyright (c) 2016-2019, Mariano F.co Benítez Mulet
 */

namespace Pachico\Voyeur;

use League\CLImate\CLImate as CLImate;

/**
 *
 */
class Voyeur
{

	/**
	 *
	 * @var Camera
	 */
	protected $_camera;

	/**
	 *
	 * @var Film
	 */
	protected $_film;

	/**
	 *
	 * @var CLImate
	 */
	protected $_logger;

	/**
	 *
	 * @var array
	 */
	protected $_shots;

	/**
	 *
	 * @var \Behat\Mink\Session
	 */
	protected $_session;

	/**
	 *
	 * @param \Pachico\Voyeur\Camera $camera
	 * @param \Pachico\Voyeur\Film $film
	 * @param bool $output_log
	 */
	public function __construct(Camera $camera, Film $film, $output_log = false)
	{
		$this->_camera = $camera;
		$this->_film = $film;


		$this->_logger = true === $output_log
			? new CLImate
			: null;
	}

	/**
	 *
	 * @param \Pachico\Voyeur\Shot $shot
	 * @return \Pachico\Voyeur\Voyeur
	 */
	public function add_shot(Shot $shot)
	{
		$this->_shots[] = $shot;
		return $this;
	}

	/**
	 * @todo Handle case script doesn't exist or cannot be read
	 * @param string $script_path
	 * @return string
	 */
	protected function _get_script_file_content($script_path)
	{
		return file_get_contents($script_path);
	}

	/**
	 *
	 * @param \Pachico\Voyeur\Shot $shot
	 * @return string
	 */
	protected function _shoot_shot(Shot $shot)
	{

		$this->_logger and $this->_logger->out('Loading ' . $shot->get_uri());


		$this->_logger and $this->_logger->out('Resizing window to ' . $shot->get_width() . 'x' . $shot->get_height());

		$this->_session->resizeWindow(
			$shot->get_width(), $shot->get_height()
		);

		// Load page
		$this->_session->visit(
			$shot->get_uri()
		);

		$this->_logger and $this->_logger->out('Loading finished');

		// Should I wait for something?
		if (count($shot->get_wait_for()) > 0)
		{
			foreach ($shot->get_wait_for() as $waitings)
			{
				$wait_time = key($waitings);
				$condition = is_null(current($waitings))
					? null
					: current($waitings);

				$this->_logger and $this->_logger->out('Waiting ' . $wait_time . ' microseconds');

				$condition and $this->_logger and $this->_logger->out('Or until ' . $condition);

				$this->_session->wait($wait_time, $condition);
			}
		}

		// Any scripts to execute?
		if (count($shot->get_scripts()) > 0)
		{
			foreach ($shot->get_scripts() as $script)
			{
				$script = $this->_get_script_file_content($script);
				$this->_session->executeScript($script);
			}
		}

		// Finally take the screenshot and return it
		$this->_logger and $this->_logger->out('Taking screenshot');
		$picture = $this->_session->getScreenshot();

		return $picture;
	}

	/**
	 *
	 * @return array
	 */
	public function shoot()
	{
		$result = [];

		$this->_session = $this->_camera->get_session();

		// Starting sesion if not started yet (in short, open browser)
		if (!$this->_session->isStarted())
		{
			$this->_logger and $this->_logger->out('Starting camera session');
			$this->_session->start();
		}

		// Iterate over shots
		foreach ($this->_shots as $shot)
		{
			/* @var $shot Shot */

			$output = $this->_shoot_shot($shot);

			$this->_logger and $this->_logger->out('Saving as ' . $this->_film->get_root_folder() . $shot->get_destination_file());

			// Save output in folder
			$this->_film->get_filesystem()->put($shot->get_destination_file(), $output);

			$result[] = $this->_film->get_root_folder() . $shot->get_destination_file();
		}

		// If session is open (it should) just close it
		if ($this->_session->isStarted())
		{
			$this->_session->stop();
		}

		return $result;
	}

}
