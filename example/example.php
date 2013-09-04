<?php

use Linko\Spam\Detector\BlackList;
use Linko\Spam\Detector\LinkRife;
use Linko\Spam\SpamFilter;

require_once __DIR__ . '/../autoload.php';

// setup black list detector
$blackListDetector = new BlackList();
$blackListDetector->setListFile(__DIR__ . '/banned.txt');
$blackListDetector->add('some-manual-site.com');

// setup link rife detector
$linkRife = new LinkRife();
$linkRife->setMaxLinkAllowed(2);

// setup spam filter father
$spamFilter = new Linko\Spam\SpamFilter();

// register children (o_o )
$spamFilter->registerDetector($blackListDetector);
$spamFilter->registerDetector($linkRife);

$comment = "Hey dude, your face is. this should pass spam test";
$comment .= "some-manual-site.com is added at some point which will fail spam test.";

if ($spamFilter->check($comment)->passed()) {
    echo '<h4>Passed</h4>';
}
else {
    echo '<h4>The system has rejected your comment</h4>';
}

// You may add more information
$dataToCheck = array(
    'name'  => 'some-username', // Can be author username or full name
    'email' => 'user@domain.tld',
    'text'  => $comment
);

if ($spamFilter->check($dataToCheck)->passed()) {
    // do something
}
