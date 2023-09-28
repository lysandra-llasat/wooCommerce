<?php
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
define( 'DB_NAME', 'tp_wordpress' );

/** Database username */
define( 'DB_USER', 'admin' );

/** Database password */
define( 'DB_PASSWORD', 'admin' );

/** Database hostname */
define( 'DB_HOST', 'database' );

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
define( 'AUTH_KEY',          '<82j)[$M:,zunO5StubQ|!q4$+#},yH[7b,$5F{gmszz*5Zy!R?ioaK%@a4=;i?c' );
define( 'SECURE_AUTH_KEY',   'w/;5kTR~|>=$A9thM#^2br[,d(S2pg#[]&c}$7p UFm=bG@VKw/-?H`L =bK|!lb' );
define( 'LOGGED_IN_KEY',     '>W$%/}? }~UN`^<|:>FOzX:uhWAFR&3/>$WE jT3G^|A&V2r#;s.Hgweg|fYv:,K' );
define( 'NONCE_KEY',         '37c3EA,`Xy[@_kJ,r&=?=nBU=H.kWN-5q{*#V?Gd!#~d~*0;Xeztf@I})G{6e(9i' );
define( 'AUTH_SALT',         '?&bnHju]h|,gIx-A*$zT_uUxd2]|Y`@J<S>3Ic/I:#Hi{f/L=%aHH6&Q,-iY;.xj' );
define( 'SECURE_AUTH_SALT',  't#au.;<12AB g&j%d6j@k]Pcx@Ky^z mbQjwu=r@$,Kd _07mHvnL 2J,_Y,+J06' );
define( 'LOGGED_IN_SALT',    'nur[7GLD)$5bV]v0[0v_Z?bqj.D1S}-nN=L%P8Js6}ec,$3mK]v{=)ihcwzMv4xY' );
define( 'NONCE_SALT',        'l1IUkGVfA.4@!NUhYg[?mpxE6m[9O.1aqpw=fe~)j*KGh%_9erb``0)^Fg$i(J@@' );
define( 'WP_CACHE_KEY_SALT', '*F+A0)l_Jh@l9@E5{L{X@ M!B^-}<b&0)m(F,2m@-L-UI)]-EB6.H>,<x^b>!R^@' );


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
