<?php

use SpamDetector\Detector\BlackList;
use SpamDetector\Detector\LinkRife;

require_once __DIR__ . '/../autoload.php';

// setup black list detector
$blackListDetector = new BlackList();
$blackListDetector->setListFile(__DIR__ . '/banned.txt');
$blackListDetector->add('some-manual-site.com');

// setup link rife detector
$linkRife = new LinkRife();
$linkRife->setMaxLinkAllowed(2);

// setup spam filter father
$spamDetector = new SpamDetector\SpamDetector();

// register children (o_o )
$spamDetector->registerDetector($blackListDetector);
$spamDetector->registerDetector($linkRife);

$comment = "Hey dude, this should pass spam test";
// $comment .= "some-manual-site.com is added at some point which will fail spam test.";
$comment .= " 127.0.0.1 was blocked";

if ($spamDetector->check($comment)->passed()) {
    echo '<h4>Passed</h4>';
} else {
    echo '<h4>The system has rejected your comment</h4>';
}

// You may add more information
$dataToCheck = array(
    'name' => 'some-username', // Can be author username or full name
    'email' => 'user@domain.tld',
    'text' => $comment
);

if ($spamDetector->check($dataToCheck)->passed()) {
    // do something
}
