<?php
use JoeFallon\KissTest\UnitTest;

require('config/main.php');

new \tests\JoeFallon\PhpFlash\FlashMessagesTests();

UnitTest::getAllUnitTestsSummary();
