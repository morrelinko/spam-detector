Spam Detector
---------------------

[UNDER DEVELOPMENT]: Not ready for production usage

## Usage

	```php
	<?php

	use Spam\SpamFilter;

	// Create a black list spam detector
	$blackListDetector = new BlackList();

	// add some text string to the black list detector
	$blackListDetector->add('example.com');
	$blackListDetector->add('127.0.0.1');

	// Create the spam filter
	$spam = new SpamFilter();

	// Register a spam detector (Like the black list we added above)
	$spam->registerDetector($blackListDetector);

	// Run the check
	$spamCheck = $spam->check("
		Hello, this is some text containing example.com
		and should fail as it has a word that is black listed
	");

	if($spamCheck->passed())
	{
		// Do stuff
	}

Each time you call the ``check()`` method on a string, it returns a ``SpamResult``
Object which holds the ... hmm ... spam check result.



