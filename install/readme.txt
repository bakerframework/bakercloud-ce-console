I. Installation - you can view the help folder in this source distribution for much greater detail in setting up Baker Cloud Console (CE).  You can also head over to http://docs.bakerframework.com/bakercloudce

1. Copy source code and Create Database
-  Copy all the source code in the source directory to your web root directory (or location where you want the Baker Cloud Console (CE) to reside).
-  Connect to MYSQL server, create a new database with a DB name of your choice, you will also need to create a DB user with SA authority.  Examples are provided below.

2. Configuration
-  Open source/application/config/database.php and change your DB configuration settings.

    $db['default']['hostname'] = 'localhost'; 			
    $db['default']['username'] = ‘bakerc_ce; 		// Username
    $db['default']['password'] = ‘baker’;     		// Password 
    $db['default']['database'] = ‘bakerc_cloudce; 	// Database Name
    $db['default']['dbdriver'] = 'mysqli'; 
    $db['default']['dbprefix'] = '';
    $db['default']['pconnect'] = TRUE;
    $db['default']['db_debug'] = TRUE;
    $db['default']['cache_on'] = FALSE;
    $db['default']['cachedir'] = '';
    $db['default']['char_set'] = 'utf8';
    $db['default']['dbcollat'] = 'utf8_general_ci';
    $db['default']['swap_pre'] = '';
    $db['default']['autoinit'] = TRUE;
    $db['default']['stricton'] = FALSE;

2. Installation
-  Access the application via web brower (eg) http://app.yourdomain.com/ (wherever you put your installation)
-  An installation page will display and walk you through setting up the database tables and configuration of Baker Cloud Console (CE)
-  Login with user below:

    Super User(read,edit,delete):
    account:  user 
    password: 123456

3. There is no step three for the Baker Cloud Console (CE) installation.  Install the API if you haven't already done that! :)


2014 NIN9 Creative, LLC