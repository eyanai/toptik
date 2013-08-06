<?php // Modified for Hebrew translation
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'new_topTik');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'a*gdC%m]p#o6dmlO!h[aU<Rmc3Mq:b5il7R5Cn&Qq.YS4s7PKSx()Y7(y>;^OD9O');
define('SECURE_AUTH_KEY',  '{MAJ:%$pEw`jYRH#TBOH/W*;RR)82aaqn/:PP* U|BG&ig/>NXQ_i7AZF4RiWcP;');
define('LOGGED_IN_KEY',    'ez{[sgU&_{~xnX(5}fqwM5P-Bx<EVT6Te1/JA83 U$U7%=)/QF=jl6{,fWxISa^|');
define('NONCE_KEY',        'P:3MiA`6okw~0`6o!w:U@F!w1Z@}R%cp]O$eLKE^N^`bsYfZl ^P:D#c&94k$V0H');
define('AUTH_SALT',        'qZKekZ1V%Z[4-s?y<m17xu>XTC>?FfpYMF.Cg+VzYl$<@-?y(7foyCY+$L}^*f#J');
define('SECURE_AUTH_SALT', '$ Qv>v&$2x@>3,Dj><x`yOGA) nzE<EW?KhqQr`g)@F0lWbbcn5S:Sk2LpB<g+JL');
define('LOGGED_IN_SALT',   'aqAk)bp[qZCr<cVgEL2T!x4Bf}Z$MSr>8>|%{z6Q6>%(~a~J5BW_0Q8!s& <J>o_');
define('NONCE_SALT',       'ENoylu=(`]1TvUSFOI) lGu=^!<%e#PK:-t7=`PPMj75K5Vx~C(WC~t8d*=r^a%v');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 * By default, the Hebrew locale is used.
 */
define('WPLANG', 'he_IL');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
