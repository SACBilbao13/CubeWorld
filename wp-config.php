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
define('DB_NAME', 'cubeworld');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '12345');

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
define('AUTH_KEY',         'd4|kotRxn.78(=Jg+t8;3(`x#A_l(T[pcpqnW@JJE(ro?.+HxzeFy_KsuxZDn0No');
define('SECURE_AUTH_KEY',  'bD|}83~bc?%[<l<@IUyH/?oEtL}|D{zcp|59 E9&<|K@D+rwqQuR+6GUI~Mb1,lR');
define('LOGGED_IN_KEY',    'JG{s&2~e:zp!o~%cdC;B}+$@4tw [_0HbF-W-+IZ+t+@]S<8rQw`93=IO<&A7h2|');
define('NONCE_KEY',        '.l6!y=#wg^w:2O~u6AaQvQ+DBc+{ZER9CH^to]p4@zL9zoXN/_BgwLZxfr)LF6bl');
define('AUTH_SALT',        '5@SG}$:s-dJ,WX@D(Q#:j_s<KEea>aFG+gVg(uukVTmK1`_+r-Ywj{yXVK6M|-Ma');
define('SECURE_AUTH_SALT', '7W>K1xvP*llZ^ss88jv:);r?wg^}mRId*i/s~7<S;+|d|5)}j7G+ll}=?,5CwtC+');
define('LOGGED_IN_SALT',   'X$3:<[a~m?x;5!+CtGfPL$bB|CT+x;E7bnbo0Y=h5Vled.V[Q|Y6+D53/TFOVa-b');
define('NONCE_SALT',       '6s4VZ-6V u<<y?k6_?mDw,34=X^[k -OvsoOC_&y-])^^Uc#l=odAi)y%.@QD2dF');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'cw_';

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