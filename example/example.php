<?php

require_once __DIR__.'/../autoload.php';

use Linko\Spam\SpamFilter;
use Linko\Spam\Detector\BlackList;

$blackListDetector = new BlackList();
$blackListDetector->add('superSpammingWebsite.com');
$blackListDetector->add('[kill|suck]', true);
$blackListDetector->add('\d{3}\.\d{3}\.\d{3}\.\d{3}', true);

$spamFilter = new Linko\Spam\SpamFilter();
$spamFilter->registerDetector($blackListDetector);

$comment = "Hey dude, your face is suckulent.";

if($spamFilter->check($comment)->passed()) {
    echo '<h4>Passed</h4>';
}
else {
    echo '<h4>The system has rejected your comment</h4>';
}