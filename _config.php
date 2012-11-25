<?php

if (isset($_GET['flush']) && $_GET['flush']) {

	Director::direct('HamlSilverStripeController');

}