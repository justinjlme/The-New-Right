<?php
/*
  Plugin Name: CyberSEO
  Version: 7
  Author: CyberSEO.net
  Author URI: http://www.cyberseo.net/
  Plugin URI: http://www.cyberseo.net/
  Description: The professional content curation plugin for WordPress.
 */

if (!function_exists("add_action")) {
    @require_once("../../../wp-config.php");
    status_header(404);
    nocache_headers();
    @include(get_404_template());
    exit();
}

$cseo_message = '';

define('CXXX_REG_NAME', 'cxxx_reg_name');
define('CXXX_REG_EMAIL', 'cxxx_reg_email');
define('CXXX_XCD', 'cxxx_xcd');
define('CXXX_CORE_VERSION', 'cxxx_core_version');

function cseo_file_get_contents_np($url, $as_array = false) {
    if (@parse_url($url, PHP_URL_SCHEME) != "" && function_exists('curl_init')) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $content = curl_exec($curl);
        if (!curl_errno($curl)) {
            if (curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
                if ($as_array) {
                    $content = @file($url, FILE_IGNORE_NEW_LINES);
                } else {
                    $content = @file_get_contents($url);
                }
            } elseif ($as_array) {
                $content = @explode("\n", trim($content));
            }
        }
        curl_close($curl);
    }
    return $content;
}

$CXXX_XCD = get_option(CXXX_XCD);
if (strpos($CXXX_XCD, 'CORE BEGIN') !== false && strpos($CXXX_XCD, 'CORE END') !== false) {
    eval($CXXX_XCD);
}

function cseo_main_menu() {
    if (function_exists('cseo_xml_syndicator_menu')) {
        add_menu_page('Feed Syndicator', 'CyberSEO', 'manage_options', 'cyberseo', 'cseo_xml_syndicator_menu');
        add_submenu_page('cyberseo', 'General Settings', 'General Settings', 'manage_options', 'cyberseo_general_settings', 'cseo_options_menu');
        add_submenu_page('cyberseo', 'Post Modification Tools', 'Modification Tools', 'manage_options', 'cyberseo_tools', 'cseo_tools_menu');
        add_submenu_page('cyberseo', 'Content Spinners', 'Content Spinners', 'manage_options', 'cyberseo_synonymizer', 'cseo_synonymizer_menu');
        add_submenu_page('cyberseo', 'Duplicate Post Finder', 'Duplicate Post Finder', 'manage_options', 'cyberseo_duplicate_post_finder', 'cseo_duplicate_post_finder_menu');
        add_submenu_page('cyberseo', 'Auto Comments', 'Auto Comments', 'manage_options', 'cyberseo_auto_comments', 'cseo_auto_comments_menu');
    } else {
        add_menu_page('Registration', 'CyberSEO', 'manage_options', 'cyberseo', 'cseo_registration');
    }
}

function cseo_registration() {
    global $cseo_message;
    if (get_option(CXXX_XCD) === false || !strlen(get_option(CXXX_XCD))) {
        ?>
        <div class="wrap">
            <h1>CyberSEO Registration</h1>
        <?php echo $cseo_message; ?>
            <form method="post" name="registration">
                <table width="500" cellpadding="0" cellspacing="6" align="center" bgcolor="#EEE" style="margin-top:3em; border-width:1px; border-color:#999; border-style:solid;">
                    <tbody>
                        <tr valign="top">
                            <th align="right">Full name</th>
                            <td align="left"><input type="text" name="reg_name" value="" size="60"></td>
                        </tr>
                        <tr valign="top">
                            <th align="right">Email</th>
                            <td align="left"><input type="text" name="reg_email" value="" size="60"></td>
                        </tr>
                    </tbody>
                </table>
                <br />
                <div align="center">
                    <input type="submit" name="cseo_register" class="button-primary" value="Click to Register" />
                </div>
            </form>
        </div>
        <?php
    } else {
        echo $cseo_message;
        echo '<h1 class="step" align="center"><a href="admin.php?page=cyberseo_general_settings">Continue...</a></h1>';
    }
}

function cseo_update_xcd($disable_autoupdates = false) {
    global $cseo_message;
    $name = stripslashes(get_option(CXXX_REG_NAME));
    $email = get_option(CXXX_REG_EMAIL);
    $ver = trim(cseo_file_get_contents_np('http://www.cyberseo.net/versioncontrol/?item=cyberseo7&name=' . urlencode($name) . '&email=' . urlencode($email) . '&site=' . urlencode(site_url()) . '&action=getver'));
    if (strtoupper($ver) == 'INVALID') {
        $cseo_message = '<div id="message" class="error"><h3>Error</h3><p>Your registration info is invalid. Please enter the same name and email that you were using to purchase the plugin.</p></div>';
        delete_option(CXXX_XCD);
        delete_option(CXXX_CORE_VERSION);
    } else {
        if (get_option(CXXX_CORE_VERSION) !== false && $ver == get_option(CXXX_CORE_VERSION)) {
            $cseo_message = '<div id="message" class="updated fade"><h3>No updates available.</h3>
	      <p>Your version of the CyberSEO plugin is up to date.</p></div>';
        } else {
            if (!$disable_autoupdates) {
                $xcd = @gzuncompress(base64_decode(cseo_file_get_contents_np('http://www.cyberseo.net/versioncontrol/?item=cyberseo7&name=' . urlencode($name) . '&email=' . urlencode($email) . '&site=' . urlencode(site_url()) . '&action=getxcd')));
                if (strpos($xcd, 'CORE BEGIN') !== false && strpos($xcd, 'CORE END') !== false) {
                    update_option(CXXX_XCD, $xcd);
                    update_option(CXXX_CORE_VERSION, $ver);
                    if (isset($_POST['cseo_register'])) {
                        $cseo_message = '<div id="message" class="updated fade"><h3>Congratulations!</h3><p>The plugin has been registered. Thank you for using CyberSEO</p></div>';
                    } elseif (isset($_POST['cseo_update'])) {
                        $cseo_message = '<div id="message" class="updated fade"><h3>Congratulations!</h3><p>The plugin has upgdaded to version ' . $ver . '</p></div>';
                    }
                } else {
                    $cseo_message = '<div id="message" class="error"><h3>Error</h3><p>Something went wrong. Please try again later.</p></div>';
                }
            }
        }
    }
}

if (is_admin()) {
    if (isset($_POST['cseo_register'])) {
        update_option(CXXX_REG_NAME, $_POST['reg_name']);
        update_option(CXXX_REG_EMAIL, $_POST['reg_email']);
        delete_option(CXXX_CORE_VERSION);
        cseo_update_xcd();
    } elseif (isset($_POST['cseo_update'])) {
        cseo_update_xcd();
    }
}

if (is_admin()) {
    add_action('admin_menu', 'cseo_main_menu');
}
?>