<?php
# Database Configuration
define( 'DB_NAME', 'snapshot_thenewright' );
define( 'DB_USER', 'thenewright' );
define( 'DB_PASSWORD', 'TUmlRdJBslHVg2dsZmQN' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_HOST_SLAVE', '127.0.0.1' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'wp_';

# Security Salts, Keys, Etc
define('AUTH_KEY',         'jUA%XA[c7gMV,?:Uqm1kp!u~#!)em}}Q8eEr^+N/-*1`Gj?EKkYgn%#+9,XrxN_H');
define('SECURE_AUTH_KEY',  '+{sUQniNQ[JO5bC|g0KN&}=|Bp#?a`bdN!/B|n80`l:3ADY5@gFD[=Im,`}x8_i{');
define('LOGGED_IN_KEY',    'KouVn I{Gd~|%uW)L{H_{)i7;6^xgsY?|G$4H2bz41^@G%BqRERao%9E3>n=QCYN');
define('NONCE_KEY',        ';-mdY}=]yg+R?bu*m(}J2LG,Y,5rkINPzs;QFSMM0Q=4Y~+jR#Zx~x-P]1GRVh6%');
define('AUTH_SALT',        '+x&-_HK)+ldN-%C*]+-4j;[0GyOfG/8O)2i9~y[>wquUe=[tSjW-|Rq?|u5 Sfez');
define('SECURE_AUTH_SALT', 'tGQ0^]+8MG9<Q>`Dwg~1{pj$QB{6R*t0v)olF[ga++0z1BABz6J<&x!Y*+F2D})1');
define('LOGGED_IN_SALT',   '2Dz<.)+}iGe8P[Ue-]}-s;&]b)w_( z@X|-mCTMDR3ON65<+N!D`Yj~mau^/.Zb4');
define('NONCE_SALT',       'Uc>/$mPA$f/gwi.?=,i>!1x3~jPU|nB!NKy:Y(5A=+Q85t{cc$W+a_I6%=Fc|bqX');


# Localized Language Stuff

define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'thenewright' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'PWP_ROOT_DIR', '/nas/wp' );

define( 'WPE_APIKEY', '405e4aede16db95c12a7883c2dc75e08457cb569' );

define( 'WPE_CLUSTER_ID', '100676' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 2222 );

define( 'WPE_LBMASTER_IP', '' );

define( 'WPE_CDN_DISABLE_ALLOWED', true );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISABLE_WP_CRON', false );

define( 'WPE_FORCE_SSL_LOGIN', false );

define( 'FORCE_SSL_LOGIN', false );

/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/

define( 'WPE_EXTERNAL_URL', false );

define( 'WP_POST_REVISIONS', FALSE );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

umask(0002);

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );

$wpe_all_domains=array ( 0 => 'thenewright.news', 1 => 'thenewright.wpengine.com', 2 => 'www.thenewright.news', );

$wpe_varnish_servers=array ( 0 => 'pod-100676', );

$wpe_special_ips=array ( 0 => '104.154.57.156', );

$wpe_ec_servers=array ( );

$wpe_largefs=array ( );

$wpe_netdna_domains=array ( 0 =>  array ( 'zone' => '20fccd3omfm13yfwn4vynfga', 'match' => 'thenewright.wpengine.com', 'secure' => true, 'enabled' => true, ), 1 =>  array ( 'zone' => '3yvyry34ci8m114x1l4t6kap', 'match' => 'thenewright.news', 'secure' => true, 'enabled' => true, ), );

$wpe_netdna_domains_secure=array ( 0 =>  array ( 'zone' => '20fccd3omfm13yfwn4vynfga', 'match' => 'thenewright.wpengine.com', 'secure' => true, 'enabled' => true, ), 1 =>  array ( 'zone' => '3yvyry34ci8m114x1l4t6kap', 'match' => 'thenewright.news', 'secure' => true, 'enabled' => true, ), );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( 'default' =>  array ( 0 => 'unix:///tmp/memcached.sock', ), );

define( 'WP_SITEURL', 'http://thenewright.staging.wpengine.com' );

define( 'WP_HOME', 'http://thenewright.staging.wpengine.com' );
define('WPLANG','');

# WP Engine ID


# WP Engine Settings






# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');

$_wpe_preamble_path = null; if(false){}
