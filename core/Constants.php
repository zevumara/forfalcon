<?php
// Public URL
define('PUBLIC_URL', ROOT_URL . 'public/');
// Application URL
define('APPLICATION_URL', ROOT_URL . APPLICATION . '/');
// Internal thumbs
define('INTERNAL_THUMBNAILS_PATH', APPLICATION_PATH . 'uploads' . DIRECTORY_SEPARATOR . 'thumbnails' . DIRECTORY_SEPARATOR);
// Public images
define('IMAGES_PATH', ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR);
// Public thumbs 1
define('THUMBNAILS_1_PATH', IMAGES_PATH . 'thumbnails1' . DIRECTORY_SEPARATOR);
// Public thumbs 2
define('THUMBNAILS_2_PATH', IMAGES_PATH . 'thumbnails2' . DIRECTORY_SEPARATOR);
// Public thumbs 3
define('THUMBNAILS_3_PATH', IMAGES_PATH . 'thumbnails3' . DIRECTORY_SEPARATOR);
// Public thumbs 4
define('THUMBNAILS_4_PATH', IMAGES_PATH . 'thumbnails4' . DIRECTORY_SEPARATOR);
// Public thumbs 5
define('THUMBNAILS_5_PATH', IMAGES_PATH . 'thumbnails5' . DIRECTORY_SEPARATOR);
// Public files
define('FILES_PATH', ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR);
// Public videos
define('VIDEOS_PATH', ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR);
// Server configuration
ini_set('max_execution_time', '300');
ini_set('post_max_size', '64M');
ini_set('upload_max_filesize', '64M');
?>