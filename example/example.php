<?php

require_once __DIR__.'/../autoload.php';

use Linko\Spam\SpamFilter;
use Linko\Spam\Detector\BlackList;
use Linko\Spam\Detector\LinkRife;

// setup black list detector
$blackListDetector = new BlackList();
$blackListDetector->add('superSpammingWebsite.com');
$blackListDetector->add('kill|suck', true); // regex
$blackListDetector->add('\d{3}\.\d{3}\.\d{3}\.\d{3}', true); // regex

// setup link rife detector
$linkRife = new LinkRife();

// setup spam filter father
$spamFilter = new Linko\Spam\SpamFilter();

// register children (o_o )
$spamFilter->registerDetector($blackListDetector);
$spamFilter->registerDetector($linkRife);

$comment = "Hey dude, your face is. ";

if($spamFilter->check($comment)->passed()) {
    echo '<h4>Passed</h4>';
}
else {
    echo '<h4>The system has rejected your comment</h4>';
}