AirMail 4.0

phpVMS module to create a messaging system your phpVMS based virtual airline.

Released under the following license:
Creative Commons Attribution-Noncommercial-Share Alike 3.0 Unported License

----------------------------------------------
A visible link to http://www.simpilotgroup.com must be provided on any site utilizing this script for the license to be valid.
----------------------------------------------

Developed by:
simpilot - David Clark
www.simpilotgroup.com
www.david-clark.net

Developed on:
phpVMS v-dev
php 5.3.13
mysql 5.5.24
apache 2.2.22

Contributions by:
Oxymoron290 - Timothy Sturm


This system is not compatible with any earlier versions of AirMail

New Features:

-Delete All function in inbox and all message folders
-Individual pilot setting to have email sent to pilot when new message is received
-Threaded messages

Install Using Simpilotgroup Plugin Manager

-Download the package
-Upload the package to your site using the plugin manager
-Use the auto-install from the plugin manager

Install Manually:

-Download the package.
-Unzip the package and place the files as structured in your root phpVMS install.
-Use the airmail.sql file to create the tables needed in your sql database using phpmyadmin or similar.

Options:

To Use the "You Have Mail" function place the following code where you would like the notice to appear, it will only appear if the pilot is logged in.

<?php MainController::Run('Mail', 'checkmail'); ?>

-Create a link on your site for your pilots to access their AIRMail

<a href="<?php echo url('/Mail'); ?>">AIRMail</a>