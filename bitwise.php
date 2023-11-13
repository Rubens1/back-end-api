<?php
$currentDateTime = new DateTime();
$currentDateTime2 = new DateTime();

$interval = new DateInterval('PT2H');

$currentDateTime->add($interval);

$formattedDateTime = $currentDateTime->format('d-m-Y H:i:s');

if($formattedDateTime > $currentDateTime2->format("d-m-Y H:i:s")) {
    echo true;
} else {
    echo false;
}
