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
define('DB_NAME', 'u312518386_hotels');

/** MySQL database username */
define('DB_USER', 'u312518386_hotels');

/** MySQL database password */
define('DB_PASSWORD', 'Ah2o@[dDzTG=');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('FS_METHOD','direct');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '`R]-`ZC=Q_C-0&6+V>`ir:X/B#>>)Orr<DOv.xJFF5KL+><]|wDwf6}M=N>g&%(b');
define('SECURE_AUTH_KEY',  'F4QeW=2d!+nsnu.r}YsAUg5#/k&V~k`6|>xt7F.d4*rAaIao+*y+tA}}:66d>x&?');
define('LOGGED_IN_KEY',    'E2#|p)dw/+P=&+||3T^xIj&jL~2uL{Fg#3m/VTW2-r88-h*6/;zKry`<]g0*u7_u');
define('NONCE_KEY',        'c YOXf|+L,u=C)r#oSA5,U8!~g5#9-8s<<@C=1b$(KuNl=&6-meWj|nO:(o@{E6B');
define('AUTH_SALT',        'YT;L?fVf1#1h>?dLNO?z3PyD}7&XUCohQC/,0FdLb;S<{p/SgGe3<-%2`4wq;o4h');
define('SECURE_AUTH_SALT', 'p4?s]Sf:;=P4zH;vc2a[JF2i|HwO-VJke|gvTQ#rzQs>Xrh/2Ej( /!juHfZvAfL');
define('LOGGED_IN_SALT',   '^SR_IF}6OI>||?-4~$INeCp|V3/#vVZj1?04}+4j]!U{rY*+vDQx%BFL(;[Fnw%{');
define('NONCE_SALT',       '*,TvCC[UmU}_#-UC{a0[7S+5(HXOq`]U*EU]K!9JG eH8oB3rip$2O6jpm[~=t_o');


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'hotels_';

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
