<?php


function isSameDay(DateTimeImmutable $day1, DateTimeImmutable $day2) {
    $day1 = $day1->setTime(0,0);
    $day2 = $day2->setTime(0,0);
    return $day1->getTimestamp() === $day2->getTimestamp();
}