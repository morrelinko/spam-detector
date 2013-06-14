Spam Detector
---------------------

[UNDER DEVELOPMENT]: Not ready for production usage

## Usage

	// Create a black list spam detector
	$blackListDetector = new BlackList();

	// add some text string to the black list detector
	$blackListDetector->add('site.com');
	$blackListDetector->add('127.0.0.1');

	// Create the spam filter
	$spam = new SpamFilter();

	$spamCheck = $spam->check("__put_spam_string_here");

	if($spamCheck->passed())
	{
		// Do stuff
	}