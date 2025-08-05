<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

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
define( 'DB_NAME', 'wp_botphonic' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         '^y3?w+#%irt-5vzx>9T+ls2M])iha62%c~+{Q/P;CxBz;,HO]o)]9Sd/*-7y9$I#' );
define( 'SECURE_AUTH_KEY',  '8|or7Hi.3~4o|uXjE-J1sT(#!S~hpfH_aP/e1yZQCUd29#w]cZ# oFJN3aN|69S ' );
define( 'LOGGED_IN_KEY',    'jf?qTc(`,4GH[w^qZQ7S!lv}.H?W.cI}?>r|oK_fi[,67A?=iwMR7fNV707WdQ@^' );
define( 'NONCE_KEY',        'ye4s&G 5{/SR@v^7yKwnG2NI(nn%dh3vX9C&! +onINWb6$;<6xz+#MNelM!~|/#' );
define( 'AUTH_SALT',        'J||T^RsB{*?cJ7D Z1>:mGtG:6+-[lGqW}fI?XGPJx8U3O&[P!1k5s[/(O^(&T:J' );
define( 'SECURE_AUTH_SALT', 'GAfO0j@~;M >B@*D+cPS4SOAqP/Z@$24l4!gK/GY@D`2 N-r%&cn&Gqq#/MiEpfg' );
define( 'LOGGED_IN_SALT',   '&W9*nG*Xq5p~j8V7Gr|;{k+]TZ =hLg**u-pX:fSr(xGV1JY8Q9 G60~E$Ye:0M7' );
define( 'NONCE_SALT',       'S-HwFoVJ$lI5?z,7-JF6S&,f.U*IOL@D561dS8&=8(kjr? {(bZ97x*NjMEHx+N1' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

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
define( 'WPCF7_AUTOP', false);


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
