<?php

/**
 * @author Mariano F.co Benítez Mulet <pachico@gmail.com>
 * @copyright (c) 2016-2019, Mariano F.co Benítez Mulet
 */

namespace Pachico\Voyeur;

use League\CLImate\CLImate as CLImate;

/**
 * This class is the orchestrator that handles the Selenium
 * connection istance and shoots screen captures.
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

		$this->_logger = (true === $output_log && php_sapi_name() === 'cli')
			? new CLImate
			: null;
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

		// Starting sesion if not started yet (in short, open browser)
		if (!$this->_session->isStarted())
		{
			$this->_logger and $this->_logger->out('Starting camera session');
			$this->_session->start();
		}

		$this->_logger and $this->_logger->out('Loading ' . $shot->get_uri());

		$this->_logger and $this->_logger->out("\tResizing window to " . $shot->get_width() . 'x' . $shot->get_height());

		$this->_session->resizeWindow(
			$shot->get_width(), $shot->get_height()
		);

		// Load page
		$this->_session->visit(
			$shot->get_uri()
		);

		$this->_logger and $this->_logger->out("\tLoading finished");

		// Should I wait for something?
		if (count($shot->get_wait_for()) > 0)
		{
			foreach ($shot->get_wait_for() as $waitings)
			{
				$wait_time = key($waitings);
				$condition = is_null(current($waitings))
					? null
					: current($waitings);

				$this->_logger and $this->_logger->out("\tWaiting " . $wait_time . ' microseconds');

				$condition and $this->_logger and $this->_logger->out("\tOr until " . $condition);

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
		$this->_logger and $this->_logger->out("\tTaking screenshot");
		$picture = $this->_session->getScreenshot();

		return $picture;
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
	 *
	 * @return array
	 */
	public function get_shots()
	{
		return $this->_shots;
	}

	/**
	 *
	 * @return Camera
	 */
	public function get_camera()
	{
		return $this->_camera;
	}

	/**
	 *
	 * @return array
	 */
	public function shoot()
	{
		$this->_session = $this->_camera->get_session();

		// Iterate over shots
		foreach ($this->_shots as $shot)
		{
			/* @var $shot Shot */

			$output = $this->_shoot_shot($shot);

			$this->_logger and $this->_logger->out("\tSaving as " . $this->_film->get_root_folder() . $shot->get_destination_file());

			// Save output in folder
			if (empty($output))
			{
				$this->_logger and $this->_logger - ("\tThere was an error capturing this page. Please, check or retry. ");
				continue;
			}

			$saved_result = $this->_film->get_filesystem()->put($shot->get_destination_file(), $output);

			if (!$saved_result)
			{
				$this->_logger and $this->_logger - ("\tThere was an error saving the screenshot. Please, check or retry. ");
				continue;
			}

			// It was successful, mark it
			$shot->set_completed(true);
		}

		// If session is open (it should) just close it
		if ($this->_session->isStarted())
		{
			$this->_session->stop();
		}

		return $this->_shots;
	}

}
