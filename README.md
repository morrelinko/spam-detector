Spam Detector
---------------------

Spam Filter is a simple library for detecting spam messages. It follows the open closed principle by introducing
Spam Detectors which are just separate classes used to extend the spam filter detecting capabilities.

[![Build Status](https://travis-ci.org/morrelinko/spam-detector.png?branch=master)](https://travis-ci.org/morrelinko/spam-detector)

## Installation

Spam Filter library can be loaded into your projects using [Composer](http://getcomposer.org) or by loading
the inbuilt autoloader.

##### Composer Installation

You can define the spam filter as a dependency in your project. Below is a minimal setup required

```json
{
	"require" : {
		"morrelinko/spam-detector": "0.2.0"
	}
}
```

##### Using autoload.php

If you are not using composer for your dependency (which you should) there is a simple autoloader packaged with
this library which you can just 'include()' into your project files

```php
	require_once '/path/to/spam-detector/autoload.php';
```

## Setup

This should be done once throughout your app

```php

use SpamDetector\SpamDetector;

// Create a black list spam detector
$blackListDetector = new BlackList();

// add some text string to the black list detector
$blackListDetector->add('example.com');
$blackListDetector->add('127.0.0.1');

// Create the spam filter
$spamDetector = new SpamDetector();

// Register the spam detector
$spamDetector->registerDetector($blackListDetector);
```

## Usage

```php

// Run the check
$spamCheckResult = $spamDetector->check("
	Hello, this is some text containing example.com
	and should fail as it has a word that is black-listed
");

if($spamCheckResult->passed()) {
	// Do stuff
}
```

Each time you call the ``check()`` method on a string, it returns a ``SpamResult``
Object which holds the ... hmm ... spam check result.

You could provide more information about the entity trying to perform the action you are checking against
the spam detector.

    <?php

    $check = $spamDetector->check(array(
        'name' => 'johndoe',
        'email' => 'johndoe@gmail.com',
        'text' => 'Hello, this is some clean comment John Doe is trying to post'
    ));

    if ($check->passed()) {
        // Post comment
    }

Some detectors will require these extra information to \`perform`...

## Currently Supported Spam Detectors

###### 1. BlackList Detector:

The black list detector flags a string as a spam if it contains
any of one or more words that has been added to the black list.
Strings could be formed from Regular Expressions or a Character Sequence.

###### 2. LinkRife Detector:

The link rifle detector checks if a text contains too many links based on the max links allowed
and the percentage ratio of links to words.. You can also modify these values to your taste.

## Creating your own custom Detector

You create a detector simply by creating a class that implements the ``SpamDetectorInterface``
which defines the following contract.

    interface SpamDetectorInterface
    {
        public function detect($data);
    }

The prepared data passed as the argument is made up of an array with these values.

* 'name' => Optional name of the user. Could be username or full name [This is provided by you].
* 'email' => Optional e-mail address of the user [This is provided by you]
* 'text' => The content of the message [This is provided by you]
* 'ip' => The IP address of the user
* 'user_agent': The browser user-agent of the user

If your detector returns ``true`` then the text is flagged as spam otherwise not spam if false is returned.

Below is an example of a "fantastic" spam detector that checks if a text is above 200 words and flags it as spam.

Its not usable, its just an example.

```php

class LengthTooLong implements SpamDetectorInterface
{
	public function detect($string)
	{
		if (str_word_count($string) > 200) {
			return true;
		}

		return false;
	}
}
```

After creating your spam detector, you add it using the ``registerDetector()`` method in the SpamFilter

```php
...

$lengthTooLong = new LengthTooLong();

$spamFilter->registerDetector($lengthTooLong);
```

## Licence

The MIT License (MIT). Please see [License File](https://github.com/morrelinko/simple-photo/blob/master/LICENSE) for more information.

Enjoy!!
