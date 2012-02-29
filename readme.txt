AirMail 3.0

phpVMS module to create a messaging system your phpVMS based virtual airline.

Released under the following license:
Creative Commons Attribution-Noncommercial-Share Alike 3.0 Unported License

Developed by:
simpilot - David Clark
www.simpilotgroup.com
www.david-clark.net

Developed on:
phpVMS v2.1.934-158
php 5.3.4
mysql 5.0.7
apache 2.2.17

This system is not compatible with any earlier versions of AirMail

New Features:

-Delete All function in inbox and all message folders
-Individual pilot setting to have email sent to pilot when new message is received
-Threaded messages

Install:

-Download the attached package.
-unzip the package and place the files as structured in your root phpVMS install.
-use the airmail.sql file to create the tables needed in your sql database using phpmyadmin or similar.

To Use the "You Have Mail" function place the following code where you would like the notice to appear, it will only appear if the pilot is logged in.

<?php MainController::Run('Mail', 'checkmail'); ?>

-Create a link on your site for your pilots to access their AIRMail

<a href="<?php echo url('/Mail'); ?>">AIRMail</a>