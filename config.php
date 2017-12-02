<?php
define('ROOT_NAME', 'ZINES');
define('ABS_URL', DIRECTORY_SEPARATOR.ROOT_NAME.DIRECTORY_SEPARATOR);

define('ASSETS_DIR', 'assets'.DIRECTORY_SEPARATOR);
define('UPLOADS_DIR', ASSETS_DIR.'medias'.DIRECTORY_SEPARATOR);

// Define where all the packages folders are
define('PACKAGES_DIR', 'src/packages'.DIRECTORY_SEPARATOR);

// Default image to display when none is set
define('DEFAULT_AVATAR', UPLOADS_DIR.'default_avatar.jpg');
