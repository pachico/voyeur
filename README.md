# Voyeur

[![Build Status](https://travis-ci.org/pachico/voyeur.svg?branch=master)](https://travis-ci.org/pachico/voyeur) [![codecov.io](https://codecov.io/github/pachico/voyeur/coverage.svg?branch=master)](https://codecov.io/github/pachico/voyeur?branch=master)

Voyeur is a tool that takes screenshots of websites by connecting to either Selenium or Phantomjs.
It will allow you to resize the browser (in case you want to test CSS breakpoints) and execute any Javascript before you take the shot (typically useful if you want to interact with a page, like a login process).

## Installation
I encourage you to use Composer to install it to the latest stable version, but you can also download manually. (Anyway, you will be forced to use Composer to install its dependencies.) 
	
	require pachico/voyeur dev-master

## Usage

Voyeur uses metaphoric class names so you know what each one is supposed to do.
As a **Voyeur**, you need a **Camera** (which is the Selenium/Phantomjs instance it will connect to), a **Film** (which is the storage where your screenshots are saved) and a number of **Shots** (which are the web pages you want to capture, along with the browser size, scripts to execute, etc.).

### Simple example
This is what a typical usage looks like:
	
	use	\Pachico\Voyeur\Voyeur as Voyeur,
		\Pachico\Voyeur\Shot as Shot,
		\Pachico\Voyeur\Film as Film,
		\Pachico\Voyeur\Camera as Camera;
	
	// We create a Camera indicating the path where Selenium/Phantomjs is running
	$camera = new Camera(Camera::DRIVER_NAME_PHAMTOMJS, 'http://localhost:8910/');
	
	// We indicate what is the folder path where screenshots will be saved 
	$film = new Film('/tmp/voyeur/');
	
	// We create a Voyeur instance
	$voyeur = new Voyeur($camera, $film);
	
	// We instanciate as many Shots as we want url and destination file 
	$shot = new Shot('http://www.example.com/', '/example/home.png');
	
	// Add the Shot to Voyeur and shot
	$voyeur
		->add_shot($shot)
		->shoot();

>A real complete example can be found under  *test/example/example.php*

### Resizing browser

You might want to capture the same page but with different browser sizes.
You can do this by creating multiple **Shots**, each one with a different size, and add them to **Voyeur**.

	$shot = new Shot('http://www.example.com/',  '/example/home.png');
	$shot->set_window_size(1024, 800); //width and height in pixels

### Execute Javacript

It might be a necessity to interact with a webpage before you grab a screenshot (ie, you might want to login, or press a button that will load asyncronous data).
You can tell any **Shot** to execute as many Javascript files as you want by indicating their paths before they are added to **Voyeur**.

	$shot = new Shot('http://www.example.com/',  '/example/home.png');
	$shot->add_scripts('/path/to/your/script.js'); // absolute path to the file

>**Note**: You can add multiple scripts to every **Shot**. They will be executed in the same order you added them.

### Wait before screenshot is taken

However, asyncronous content might take some time before it's loaded and you don't want to take a screenshot before all the content has been fully loaded.

#### Just wait some time

You can solve this by telling every Shot to wait a certain amount of milliseconds to take the screenshot:

	$shot = new Shot('http://www.example.com/',  '/example/home.png');
	$shot->add_wait_for(3000); // It will wait 3 seconds before the shot is done

>**Note**: You can add multiple waiting policies per **Shot**. They will be executed in the same order you added them.

#### Wait, but until...

On the other hand, you don't want to wait more than what's required. This is why this method accepts another parameter that might speed up your screen captures.

	$shot = new Shot('http://www.example.com/',  '/example/home.png');
	$shot->add_wait_for(10000, '$(".somediv").is(":visible")'); 

For this example I'm using jQuery but any Javascript assertion will work. 
This will be interpreted as:
>Wait until the element with class "somediv" is visible. It if didn't become visible in less than 10 seconds, shoot anyway. 

##Camera

Each Voyeur instance can have only one Camera instance.
You will need to provide to its constructor the name of the browser and the url of the Selenium/Phantomjs instance:

	$camera = new Camera(Camera::DRIVER_NAME_PHAMTOMJS, 'http://localhost:8910/');

###Selenium

Running Selenium is very easy and should work straight out of the box whenever, at least, Firefox is available.
Download **Selenium Server Standalone** from http://www.seleniumhq.org/download/ and run it with:

	java -jar selenium-server-standalone-[whatever-version].jar

You will see in console the url you have to provide to the Camera constructor. 
It will be something like

	http://127.0.0.1:4444/wd/hub

If you are executing Selenium and Voyeur in difference machines, make sure you set up the ip appropriately.

### Phantomjs

**Phantomjs** is a headless browser which is much faster than Firefox, Chrome or others.
Most linux distributions have a version in their repositories but you can download it from http://phantomjs.org/.
Once installed, execute it in webdriver mode

	phantomjs --webdriver=8910

Same as with Selenium, you will have to privide the url of your **Phantomjs** instance to the **Camera** constructor.

## Shot
When creating a **Shot**, you can also specify an id for it passing it as third parameter to its constructor. It can be handy to use it as reference when **Voyeur** is loaded with multiple Shots.
If not specified, it will internally create a unique one.

	$shot = new Shot('http://www.example.com/', '/example/home.png', $anystring);

##Log
If you run it via CLI (recommended) you can also enable the output log as third parameter in **Voyeur** constructor:
	
	// third parameter (true) enables CLI log
	$voyeur = new Voyeur($camera, $film, true);

## Contributing

All suggestions, bug fixes, and feature requests are very welcome!
Thanks in advance!
## License

Copyright (c) 2016-2019 Mariano F.co Benítez Mulet

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
