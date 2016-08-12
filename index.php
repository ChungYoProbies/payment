<?php

require_once 'DB.php';
require_once 'PersonalBank.php';

$bank = new PersonalBank(1);

$bank->deposit(1);
