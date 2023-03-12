<?php
/**
* The base configuration for WordPress
*
* The wp-config.php creation script uses this file during the
* installation. You don't have to use the web site, you can
* copy this file to "wp-config.php" and fill in the values.
*
* This file contains the following configurations:
*
* * MySQL settings
* * Secret keys
* * Database table prefix
* * ABSPATH
*
* @link https://codex.wordpress.org/Editing_wp-config.php
*
* @package WordPress
*/
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'moco');

/** MySQL database username */
define('DB_USER', 'debian-sys-maint');

/** MySQL database password */
define('DB_PASSWORD', 'UgApr5ckzy1BmjoG');
/** MySQL hostname */
define('DB_HOST', 'localhost');
/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');
/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
define( 'WP_DEBUG', false);
define( 'FS_METHOD', 'direct' );

/**#@+
* Authentication Unique Keys and Salts.
*
* Change these to different unique phrases!
* You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
* You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
*
* @since 2.6.0
*/
define('AUTH_KEY', '0byjfP9oI3t98EEJshkB5WQx1CRE/A00NjIZtVf+o6DOP9jz/sUC5zcAVZeAAfXz');
define('SECURE_AUTH_KEY', 'omfNlWXzxa0aq8ag5Go4rUFUhDzR5kuaLUU1fk3LDeVrX/pQToa8W0b2OVSIr9kp');
define('LOGGED_IN_KEY', 'wvL3H+9r4VhswvModXeJzHBPe47TU0c9WXnSnuEjQ2jOK2cHrbKqzFaCYEyhI7Hn');
define('NONCE_KEY', 'My8p5juGLy8uAYLOBWcdfns3zDJr5pGJX5qP7DLwk3hXF0jOA18s3aItyQxZDks9');
define('AUTH_SALT', 'mpzI3Weu1J6KrBOa2P4WVJQ1HgMY30xii5+DHw2Tael3X8JfJtGq92DjrX+5UX5I');
define('SECURE_AUTH_SALT', '3Fol4I/qt5OHF40S6BeLhCc+MEvva09F0fdTVzxyEE0gA5wc9/eBQBuo6LAqOkf7');
define('LOGGED_IN_SALT', 'o5ouawykBsxtdlQK7c/xHxKvPDXbc8mXgPmEqZXBIF8REeqzuQ+aRecOn6dlssKh');
define('NONCE_SALT', 'DkPpZA3zk7FD43F9vgLc0WEX2sG+7pmwfYzFIInCSgsLvDu0JUlVDp6JvP65ZvRd');
define('JWT_AUTH_SECRET_KEY', 'pZA3zk7FD43F9vgLc0WEX2sG+7pmwfYzFIInCSgsL');
define('JWT_AUTH_CORS_ENABLE', true);


/**#@-*/
/**
* WordPress Database Table prefix.
*
* You can have multiple installations in one database if you give each
* a unique prefix. Only numbers, letters, and underscores please!
*/
$table_prefix = 'crn_';
/**
* For developers: WordPress debugging mode.
*
* Change this to true to enable the display of notices during development.
* It is strongly recommended that plugin and theme developers use WP_DEBUG
* in their development environments.
*
* For information on other constants that can be used for debugging,
* visit the Codex.
*
* @link https://codex.wordpress.org/Debugging_in_WordPress
*/

/* That's all, stop editing! Happy blogging. */
/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
define('ABSPATH', dirname(__FILE__) . '/');
/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

