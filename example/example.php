<?php

require_once __DIR__.'/../autoload.php';

use Linko\Spam\SpamFilter;
use Linko\Spam\Detector\BlackList;
use Linko\Spam\Detector\LinkRife;

// setup black list detector
$blackListDetector = new BlackList();
$blackListDetector->setListFile(__DIR__.'/banned.txt');
$blackListDetector->add('some-manual-site.com');

// setup link rife detector
$linkRife = new LinkRife();
$linkRife->setMaxLinkAllowed(2);

// setup spam filter father
$spamFilter = new Linko\Spam\SpamFilter();

// register children (o_o )
$spamFilter->registerDetector($blackListDetector);
$spamFilter->registerDetector($linkRife);

$comment = "Hey dude, your face is. www.example.com ";
// $comment .= "some-manual-site.com is added at some point";

if($spamFilter->check($comment)->passed()) {
    echo '<h4>Passed</h4>';
}
else {
    echo '<h4>The system has rejected your comment</h4>';
}

// You could also add more information
$dataToCheck = array(
    'name' => 'SomeSpamUsername',
    'text' => $comment
);

if ($spamFilter->check($comment)->passed()) {
    // do something
}