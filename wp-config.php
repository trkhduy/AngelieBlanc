<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'furniture' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '123456' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

if ( !defined('WP_CLI') ) {
    define( 'WP_SITEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
    define( 'WP_HOME',    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
}



/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'QdWfSs9H0UL14WiraWjVP74bRNVwk95TMxwuhPk9h84d5kbNHezpWr1bcCbUyFXR' );
define( 'SECURE_AUTH_KEY',  'lvz4b7DZULDo0g8zSayiQWJLJquUFAzduycQNktZRwxJF8ArNezWQ7ARRXfLCrj2' );
define( 'LOGGED_IN_KEY',    'yegJmwAVMIQi1AT0FGoF0ZsQr653VfnA302vqgv3gP1zQ52kreeNciTGBJ6Vysrj' );
define( 'NONCE_KEY',        'HihgLDRrymf1lucuzo8jbiZeOt7ZhuPvLLq9hjaQFqqfbvIZcxAIJLOMQDGw92Om' );
define( 'AUTH_SALT',        'r9M9J30Wkt93ixWG3PnYZcISlsXXfAXdkBHADduW8OKNrt5UMYgEA8PCKL49Kkxk' );
define( 'SECURE_AUTH_SALT', 'Iw0XIJNijJ88IjbvKaj6B1CGno7epE63I0Vh52UewQfLsXg4jFdsL3w0Zp9S5MrS' );
define( 'LOGGED_IN_SALT',   '5oyYTnXhrYh1qJ8dNIVjEqh4UKAfVjd3kaLwGEVSWWksIK6xDV8GF3gJWl61spwj' );
define( 'NONCE_SALT',       'namOXJrw7gPB2x0MRbv6LVRZFDAK0hcvq19lkVL6ZraFo3Alb568gNeY1GL4vWmI' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'bz_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';