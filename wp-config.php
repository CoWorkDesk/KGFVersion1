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
define('DB_NAME', 'sdgsaior_4ct5');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'FALILAT@2011#');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '*Jo5q2iA<&$x%Z^8}YYuEYVMu8+bkd:DXr6.$2F@XYvt4 LNWuHVyuLyOfP5k9j(');
define('SECURE_AUTH_KEY',  '(4P?|w]U}qHvSBhF= *8sEr`s-E1C|ZPC<xC+*>N9_zIt+N1TC*Covn2x-v2BB9o');
define('LOGGED_IN_KEY',    'iesl_~6&x|F365}{0@73jUr0yGEG-E2i>/ha4k0mo)z_*W1~)e|,V(MgkuY350a}');
define('NONCE_KEY',        '5g2oB5+b>`Eb9}M!CKnda=F&4m7Fm}JCjMYjBG4,8&WmC]fr4F_q/9y}/QW.|,f+');
define('AUTH_SALT',        '6)rTk/A;XxUY1Eu@.I(4X&h&BX%~<=gZ2F5)ml_8,WJoFvo0E8`Of&}b~yTtqukR');
define('SECURE_AUTH_SALT', 'i/|L2#cr+F{Bd&pj#PXngMNMS<+0qF`TM*4 U{i%en~0*sfqYnt`yovG/%hi;}0?');
define('LOGGED_IN_SALT',   '&>,ahL}_d.<-hkh`iAY8-[]=,U rm-{|GDz91*9I@BZ)d9!uNQ/aC{-SIXf_?O,o');
define('NONCE_SALT',       'l#DZv#cq3mX`G#zEHym~#Ti3fOCoK<s1~%(sU_LWv!l0cr_ij`}n.?AhlXr*?J{I');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'w473r_';

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
define('WP_DEBUG', false);
set_time_limit(3000);
define('WP_MEMORY_LIMIT', '512M');
define('AUTOSAVE_INTERVAL', 300 ); // seconds
define('WP_POST_REVISIONS', false );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
