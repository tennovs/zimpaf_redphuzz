<?php
define('WP_AUTO_UPDATE_CORE', false);
define('WP_AUTO_UPDATE_PLUGIN', false);
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'all_db_user' );

/** Database password */
define( 'DB_PASSWORD', 'password' );

/** Database hostname */
define( 'DB_HOST', 'db_host' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',          'Yx{*`Zu#YOs5FpInc $jmBHkwzKAUu~<TXJ#Vqskps3JqAd7C0kWVt,i8PJx[l=%' );
define( 'SECURE_AUTH_KEY',   '^$+qiA6PfJD5xL]llg!nT oO^/?Y}^(bDm;7yTHQ||F@s*,CI#:ohKeHVH];HOm%' );
define( 'LOGGED_IN_KEY',     'ifE},U8ZG{$J7? 55tgXHO#iJjGX.F:>lgfToeh-8.J)`6bB.!Aqm3j<lJ=NOh4$' );
define( 'NONCE_KEY',         '&M0qy>5xxNAAs~wcQtaONzgs5<M:(g@_88zEe$g,Kj!a$@%)uTh^JXR>0PL$aP,j' );
define( 'AUTH_SALT',         'D=vd0>N`4 !4Li>^T4?4bZm}!<*7?9Z,ODO|[Gxe=r^{oz%{km5E6,_uw-j)b2bs' );
define( 'SECURE_AUTH_SALT',  'uKypo7fC6kT:935s=YqWHK/~D>41l{=B=&Tgoq@X&AVg5Z&K8(9KDGHP;=4+>g[@' );
define( 'LOGGED_IN_SALT',    'pXNmKwP.qRBp.=7VD z3^bzVR94`f&y>K! cQBIguuzpAN),c&)(0VH8ysQ^Cy$}' );
define( 'NONCE_SALT',        '1a}rD?7ex8HlpY5^cj&E$yTJA`R=~E{8q4%33VT-;U({7:v*4>ia|5!:,=ezv7IS' );
define( 'WP_CACHE_KEY_SALT', '^JpMFXXmprIaP8d*Nj5>(@=&stIrHybzkO}W@=QIa`}^7vT08{TpP.5meM9&bUlo' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
