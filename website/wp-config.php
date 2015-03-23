<?php
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
/*define('DB_NAME', 'memorabella_com');*/
define('DB_NAME', 'memorabellacom');

/** MySQL database username */
/*define('DB_USER', 'memorabellacom');*/
define('DB_USER', 'memorabellacom');

/** MySQL database password */
/*define('DB_PASSWORD', 'FvQeu-QV');*/
define('DB_PASSWORD', 'FvQeuQV2015!');

/** MySQL hostname */
/*define('DB_HOST', 'mysql.memorabella.com');*/
define('DB_HOST', 'memorabellacom.db.5985714.hostedresource.com');

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
define('AUTH_KEY',         'JfUfTO{g+mO z/TxWv ]Z=DZZ97F-HX^~*oH+Li:aEief.b],mAUc%g@~%,,Pg_4');
define('SECURE_AUTH_KEY',  'V3l=[o;]&em.FhPgy{!Y+T+IBMZ<MK-X7EUZ9{-&`dxt;H5 _n!_2[f2f 7#Cl97');
define('LOGGED_IN_KEY',    'v[xk%Ns1-0;0p$]#@)A~a~,hMLd$O%6X0SU5f 4GlA=)2aA44h2}6[L@q+ d;+&E');
define('NONCE_KEY',        ',>]+*Ll|~|wN?,25z#kQ=qOU~yF9dd-dS`*<fg-re-m+0%ble|;fto=-fd?Zx{m!');
define('AUTH_SALT',        '#zGtLNK](=}x$22q-X-eE#_1iJRwcAvld<MT5x_o+BNoM*5n{MAgr%1Ht}t&9|DM');
define('SECURE_AUTH_SALT', '3iKYA&]3b8rvcju{>//x&rk7Q QT7S0-+qR&J8e[?C>KI{gH@>e=nf;`RKL-hx/#');
define('LOGGED_IN_SALT',   ',C`>U|tktQ<}EG#NiZZso@^oS8ZXf~<v3yM6(@%nA~LU@hMH&!/z^d&y}6|J6[fx');
define('NONCE_SALT',       '0!kJt%o3>.a9#jpl=$fy`[ %O!`n<C=cL!@R+twUMEze|dD&mN7/jLA(cyY1W]$!');


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_98rk6v_';

/**
 * Limits total Post Revisions saved per Post/Page.
 * Change or comment this line out if you would like to increase or remove the limit.
 */
define('WP_POST_REVISIONS',  10);

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

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

