# Open Film Tools - Web Frontend package

This is a simple web frontend to upload camera measurements to be calculated by the [main Matlab framework](https://github.com/Open-Film-Tools/HDM_OFT). 

More general information about the Open Film Tools project can be found on the [project webpage](https://www.hdm-stuttgart.de/open-film-tools/english).


## Warning

The code is intended for simple processing, testing and demoing. No authentication is implemented yet and serverside processing (cron jobs) may be optimized for production use.


## Installation

Suggestions for installing this framework as a basic guideline are shown here. The procedures for Mac and Windows are however intended for development work. XAMP/MAMP shouldn't be considered for productive use.


### General notes

The web frontend does most work on the client side in Java Script so the server only has to handle the uploaded file storing so a basic PHP installation is sufficient.

The process for installing [ctlrender](https://github.com/ampas/CTL) to process preview images with the generated IDT will only be shown on Linux. Not having ctlrender doesn't affect the processing of the characterization process, only the preview won't be available.

As most dependencies can be easily installed via [homebrew](http://brew.sh), installing ctlrender on Mac is very straight forward. Compiling on Windows wasn't tried.


## Installation on Linux

The steps for installing on Linux are shown with [Debian](https://www.debian.org) as an example. However it should work on other distributions with simple path adjustments.

A standard Debian installation is assumed here and Apache will be used as the webserver. Other webservers should work the same. We have Nginx with FastCGI in a test environment running fine.

For installing the needed packages just execute:
```apt-get install apache2 libapache2-mod-php5 git imagemagick```

A few settings are needed for PHP to allow upload of bigger files as the default setting is very low not intended for big, uncompressed image files. Therefor two settings in the *php.ini* are needed. For Apache is it usually in ```/etc/php5/apache2/php.ini```, while with php-fpm/nginx it is ```/etc/php5/fpm/php.ini```.

The two options are already in the php.ini, just the values have to be changed:

```
post_max_size = 256M
upload_max_filesize = 256M
```

Now the code has to be installed. For this example the following folder structure will be used:

* ```/srv/oftp``` Main Folder for the files related to the Open Film Tools project
* ```/srv/oftp/matlab``` Matlab toolset
* ```/srv/oftp/uploads``` Folder for upload storage
* ```/srv/oftp/www``` Web frontend

To set up the needed structure just use the following set of commands:

```
mkdir /srv/oftp
cd /srv/oftp
git clone https://github.com/Open-Film-Tools/Open-Film-Tools-WebServer.git www
mkdir uploads
cp www/serverside/task_list.layout.xml 	uploads/task_list.xml
chmod -R a+w uploads
cd www/serverside/
cp config.sample.php config.php
```

A few options are needed for this config file. A most basic file could look like this:

```
<?php
define('UPLOADER_URL', 'http://your-host/oftp/serverside/uploader.php');
define('STATUS_URL',   'http:///your-host/oftp/serverside/status.php');
define('UPLOAD_DIR',   '/srv/oftp/uploads');
define('TASKLIST_XML', '/srv/oftp/uploads/task_list.xml');

define('USE_CTLRENDER', false);
?>
```

To just add this web page in the /oftp directory of your Apache, just add the following to ```/etc/apache2/sites-enabled/000-default``` before the closing ```</VirtualHost>```:

```
Alias /oftp /srv/oftp/www
<Directory /srv/oftp/www>
	Order allow,deny
	Allow from all
</Directory>
```

And restart Apache (```/etc/init.d/apache2 restart```).

For updating the server code just execute ```git pull``` in ```/srv/oftp/www```. 


## Installation on Windows

**This setup is intended for development work. Don't use XAMPP in a productive environment.**

### 1. Install the basic software packages

Download a git package for Windows like [msysGit](http://msysgit.github.io) and [XAMPP](https://www.apachefriends.org/index.html) following their installer routines.

### 2. Configure PHP for larger uploads

In the control panel of XAMPP click on *Config* in the Apache row and set higher values for the upload limits.:

```
post_max_size = 256M
upload_max_filesize = 256M
```

(Just search this two options in the Editor via Ctrl+F and modify them)

### 3. Get the web frontend code

Click on the *Explorer* button in the XAMPP control, go to the *htdocs* folder and right click there and choose *Git Gui*. Select *Clone Existing repository* and clone *https://github.com/Open-Film-Tools/Open-Film-Tools-WebServer.git* to *oftp* as the *Target Directory* for example.

Also create the upload directory where you want to store the uploads and make it accessible for all users (right click it, *Properties*, *Security* and add everyone for full access). Also create a copy of the file *serverside/task_list.layout.xml* and somewhere and make it writeable for everyone.

Enter the *serverside* directory, copy *config.sample.php* to *config.php* and open it in your editor. Set your URLs and paths. It should look like this:

```
<?php
define('UPLOADER_URL', 	'http://your-host/oftp/serverside/uploader.php');
define('STATUS_URL', 	'http:///your-host/oftp/serverside/status.php');
define('UPLOAD_DIR',    'C:\PATH\TO\YOUR\UPLOADS');
define('TASKLIST_XML',  'C:\PATH\TO\YOUR\UPLOADS\task_list.layout.xml');

define('USE_CTLRENDER', false);
?>
```

The web frontend should now be available via [http://localhost/oftp/](http://localhost/oftp/)


### 4. Update

To upgrade just browse to your XAMPP *htdocs*-Folder and do a git update in the context menu of msysgit.


## Installation on Mac OS

The how to for mac will be added here soon. Basically the Debian guide works when using [MAMP](https://www.mamp.info/en/) or [XAMP](https://www.apachefriends.org/index.html), but paths differ.