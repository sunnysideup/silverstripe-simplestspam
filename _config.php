<?php
/**
 * developed by www.sunnysideup.co.nz
**/


//copy the lines between the START AND END line to your /mysite/_config.php file and choose the right settings
//===================---------------- START simplestspam MODULE ----------------===================
//---------------------------- OPTION 1: INVISIBLE OPTION --------------------//
// MUST HAVE
//SpamProtectorManager::set_spam_protector('InvisibleSpamProtector');
// OPTIONAL (adjust to your preferences)
//InvisibleSpamField::set_min_seconds_completing_form(10);
//InvisibleSpamField::set_max_seconds_completing_form(600);
// VERY OPTIONAL (only use it when the current setup does not work)
//InvisibleSpamField::add_definition($key ="MYFIELD", $class="cssClassGoesHere", $name ="honeypot", $label ="enter if you are a spammer");
//InvisibleSpamField::set_used_field($key ="MYFIELD");
//InvisibleSpamField::add_css_rule("overflow", "hidden");

//--------------------------- OPTION 2: VISIBLE OPTION -----------------------//
// MUST HAVE
//SpamProtectorManager::set_spam_protector('SimplestSpamProtector');
//Object::add_extension('SiteConfig', 'SimplestSpamSiteConfigExtension');
//===================---------------- END simplestspam MODULE ----------------===================
