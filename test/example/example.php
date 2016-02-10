<?php

/**
 * @author Mariano F.co Benítez Mulet <pachico@gmail.com>
 * @copyright (c) 2016-2019, Mariano F.co Benítez Mulet
 *
 * This is a real working example.
 * To run it make sure sure you have started PhantomJS
 * in the same machine (or change the hub address when instanciating
 * the Camera class.
 *
 */
use \Pachico\Voyeur\Voyeur as Voyeur,
	\Pachico\Voyeur\Shot as Shot,
	\Pachico\Voyeur\Film as Film,
	\Pachico\Voyeur\Camera as Camera;

require __DIR__ . '/../bootstrap.php';

// We create a Camera indicating the path where Selenium/Phantomjs is running
$camera = new Camera(Camera::DRIVER_NAME_PHAMTOMJS, 'http://localhost:8910');

// We indicate what is the folder path where screenshots will be saved
$film = new Film(TEST_PICTURE_FOLDER);

// We create a Voyeur instance
$voyeur = new Voyeur($camera, $film, true);

// We instanciate as many Shots as we want url and destination file

$shot1 = (new Shot('http://www.example.com/', uniqid() . '.png'))
	->add_scripts(TEST_SCRIPTS_FOLDER . 'banner1.js')
	->set_window_size(400, 300)
;

$shot2 = (new Shot('http://locallhost.com/', uniqid() . '.png'))
	->add_scripts(TEST_SCRIPTS_FOLDER . 'banner2.js')
	->set_window_size(1024, 800)
;

// Add the Shot to Voyeur and shot
$saved_files = $voyeur
	->add_shot($shot1)
	->add_shot($shot2)
	->shoot();

echo 'Saved files: ' . print_r($saved_files);
