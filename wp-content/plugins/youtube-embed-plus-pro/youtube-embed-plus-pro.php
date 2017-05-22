<?php
/*
  Plugin Name: YouTube Pro
  Plugin URI: http://www.embedplus.com/dashboard/pro-easy-video-analytics.aspx
  Description: YouTube Pro. Customize and embed a responsive video, YouTube channel gallery, playlist gallery, or live stream from YouTube.com
  Version: 11.7.2
  Author: EmbedPlus Team
  Author URI: http://www.embedplus.com
 */

/*
  YouTube Pro
  Copyright (C) 2017 EmbedPlus.com

 */

//define('WP_DEBUG', true);

class YouTubePrefsPro
{

    public static $curltimeout = 20;
    public static $version = '11.7.2';
    public static $opt_version = 'version';
    public static $opt_free_migrated = 'free_migrated';
    public static $optembedwidth = null;
    public static $optembedheight = null;
    public static $defaultheight = null;
    public static $defaultwidth = null;
    public static $oembeddata = null;
    public static $opt_center = 'centervid';
    public static $opt_glance = 'glance';
    public static $opt_autoplay = 'autoplay';
    public static $opt_debugmode = 'debugmode';
    public static $opt_old_script_method = 'old_script_method';
    public static $opt_cc_load_policy = 'cc_load_policy';
    public static $opt_iv_load_policy = 'iv_load_policy';
    public static $opt_loop = 'loop';
    public static $opt_modestbranding = 'modestbranding';
    public static $opt_rel = 'rel';
    public static $opt_showinfo = 'showinfo';
    public static $opt_playsinline = 'playsinline';
    public static $opt_autohide = 'autohide';
    public static $opt_controls = 'controls';
    public static $opt_theme = 'theme';
    public static $opt_color = 'color';
    public static $opt_listType = 'listType';
    public static $opt_wmode = 'wmode';
    public static $opt_vq = 'vq';
    public static $opt_html5 = 'html5';
    public static $opt_dohl = 'dohl';
    public static $opt_hl = 'hl';
    public static $opt_ssl = 'ssl';
    public static $opt_ogvideo = 'ogvideo';
    public static $opt_nocookie = 'nocookie';
    public static $opt_playlistorder = 'playlistorder';
    public static $opt_acctitle = 'acctitle';
    public static $opt_pro = 'pro';
    public static $opt_oldspacing = 'oldspacing';
    public static $opt_responsive = 'responsive';
    public static $opt_responsive_all = 'responsive_all';
    public static $opt_origin = 'origin';
    public static $opt_widgetfit = 'widgetfit';
    public static $opt_evselector_light = 'evselector_light';
    public static $opt_stop_mobile_buffer = 'stop_mobile_buffer';
    public static $opt_defaultdims = 'defaultdims';
    public static $opt_defaultwidth = 'width';
    public static $opt_defaultheight = 'height';
    public static $opt_defaultvol = 'defaultvol';
    public static $opt_vol = 'vol';
    public static $opt_apikey = 'apikey';
    public static $opt_schemaorg = 'schemaorg';
    public static $opt_ftpostimg = 'ftpostimg';
    public static $opt_spdc = 'spdc';
    public static $opt_spdcab = 'spdcab';
    public static $opt_spdcexp = 'spdcexp';
    public static $opt_dashpre = 'dashpre';
    public static $opt_migrate = 'migrate';
    public static $opt_migrate_youtube = 'migrate_youtube';
    public static $opt_migrate_embedplusvideo = 'migrate_embedplusvideo';
    public static $spdcprefix = 'ytpref';
    public static $spdcall = 'youtubeprefs_spdcall';
    public static $opt_dynload = 'dynload';
    public static $opt_dyntype = 'dyntype';
    public static $opt_gallery_pagesize = 'gallery_pagesize';
    public static $opt_gallery_columns = 'gallery_columns';
    public static $opt_gallery_collapse_grid = 'gallery_collapse_grid';
    public static $opt_gallery_collapse_grid_breaks = 'gallery_collapse_grid_breaks';
    public static $opt_gallery_style = 'gallery_style';
    public static $opt_gallery_scrolloffset = 'gallery_scrolloffset';
    public static $opt_gallery_showtitle = 'gallery_showtitle';
    public static $opt_gallery_showpaging = 'gallery_showpaging';
    public static $opt_gallery_thumbplay = 'gallery_thumbplay';
    public static $opt_gallery_autonext = 'gallery_autonext';
    public static $opt_gallery_channelsub = 'gallery_channelsub';
    public static $opt_gallery_channelsublink = 'gallery_channelsublink';
    public static $opt_gallery_channelsubtext = 'gallery_channelsubtext';
    public static $opt_gallery_customarrows = 'gallery_customarrows';
    public static $opt_gallery_customprev = 'gallery_customprev';
    public static $opt_gallery_customnext = 'gallery_customnext';
    public static $opt_gallery_showdsc = 'gallery_showdsc';
    public static $opt_gallery_thumbcrop = 'gallery_thumbcrop';
    public static $opt_gallery_disptype = 'gallery_disptype';
    public static $opt_not_live_content = 'not_live_content';
    public static $opt_admin_off_scripts = 'admin_off_scripts';
    public static $opt_alloptions = 'youtubeprefspro_alloptions';
    public static $alloptions = null;
    public static $yt_options = array();
    public static $dft_bpts = array(array('bp' => array('min' => 0, 'max' => 767), 'cols' => 1));
    //public static $epbase = 'http://localhost:2346';
    public static $epbase = '//www.embedplus.com';
    public static $double_plugin = false;
    public static $scriptsprinted = 0;
    public static $min = '.min';
    public static $badentities = array('&#215;', '×', '&#8211;', '–', '&amp;', '&#038;', '&#38;');
    public static $goodliterals = array('x', 'x', '--', '--', '&', '&', '&');
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    //public static $ytregex = '@^[\r\n]{0,1}[[:blank:]]*https?://(?:www\.)?(?:(?:youtube.com/watch\?)|(?:youtu.be/))([^\s"]+)[[:blank:]]*[\r\n]{0,1}$@im';
    public static $oldytregex = '@^\s*https?://(?:www\.)?(?:(?:youtube.com/(?:(?:watch)|(?:embed)|(?:playlist))/{0,1}\?)|(?:youtu.be/))([^\s"]+)\s*$@im';
    public static $ytregex = '@^[\r\t ]*https?://(?:www\.)?(?:(?:youtube.com/(?:(?:watch)|(?:embed)|(?:playlist))/{0,1}\?)|(?:youtu.be/))([^\s"]+)[\r\t ]*$@im';
    public static $justurlregex = '@https?://(?:www\.)?(?:(?:youtube.com/(?:(?:watch)|(?:embed)|(?:playlist))/{0,1}\?)|(?:youtu.be/))([^\[\s"]+)@i';

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////

    public function __construct()
    {

        add_action('admin_init', array(get_class(), 'check_double_plugin_warning'));
        add_action('admin_notices', array(get_class(), 'check_free_version'));

        $active_plugins = get_option('active_plugins', array());
        if (!in_array('youtube-embed-plus/youtube.php', $active_plugins))
        {
            self::$alloptions = get_option(self::$opt_alloptions);
            
            if ((defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) || self::$alloptions[self::$opt_debugmode] == 1)
            {
                self::$min = '';
            }

            if (self::$alloptions == false || version_compare(self::$alloptions[self::$opt_version], self::$version, '<'))
            {
                self::initoptions();
            }

            if (self::$alloptions[self::$opt_oldspacing] == 1)
            {
                self::$ytregex = self::$oldytregex;
            }

            self::$optembedwidth = intval(get_option('embed_size_w'));
            self::$optembedheight = intval(get_option('embed_size_h'));

            self::$yt_options = array(
                self::$opt_autoplay,
                self::$opt_cc_load_policy,
                self::$opt_iv_load_policy,
                self::$opt_loop,
                self::$opt_modestbranding,
                self::$opt_rel,
                self::$opt_showinfo,
                self::$opt_playsinline,
                self::$opt_autohide,
                self::$opt_controls,
                self::$opt_html5,
                self::$opt_hl,
                self::$opt_theme,
                self::$opt_color,
                self::$opt_listType,
                //self::$opt_wmode,
                //self::$opt_vq,
                'index',
                'list',
                'start',
                'end'
            );

            add_action('media_buttons', array(get_class(), 'media_button_wizard'), 11);


            self::do_ytprefs();
            add_action('admin_menu', array(get_class(), 'ytprefs_plugin_menu'));
            add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(get_class(), 'my_plugin_action_links'));



            if (self::$alloptions[self::$opt_pro] && strlen(trim(self::$alloptions[self::$opt_pro])) > 8)
            {
                add_action('admin_bar_menu', array(get_class(), 'ytprefs_admin_bar'), 100);
                add_action('wp_enqueue_scripts', array(get_class(), 'ytprefs_admin_bar_scripts'));
                add_action('admin_enqueue_scripts', array(get_class(), 'ytprefs_admin_bar_scripts'));
            }


            if (!is_admin())
            {


                if (self::$alloptions[self::$opt_old_script_method] == 1)
                {
                    add_action('wp_print_scripts', array(get_class(), 'jsvars'));
                    add_action('wp_enqueue_scripts', array(get_class(), 'jsvars'));
                }

                add_action('wp_enqueue_scripts', array(get_class(), 'ytprefsscript'), 100);
                add_action('wp_enqueue_scripts', array(get_class(), 'fitvids'), 101);



                if (self::$alloptions[self::$opt_pro] && strlen(trim(self::$alloptions[self::$opt_pro])) > 0 && self::$alloptions[self::$opt_ogvideo] == 1)
                {
                    add_action('wp_head', array(get_class(), 'do_ogvideo'));
                }
            }

            add_action("wp_ajax_my_embedplus_pro_record", array(get_class(), 'my_embedplus_pro_record'));
            add_action("wp_ajax_my_embedplus_dashpre", array(get_class(), 'my_embedplus_dashpre'));
            add_action("wp_ajax_my_embedplus_clearspdc", array(get_class(), 'my_embedplus_clearspdc'));
            add_action("wp_ajax_my_embedplus_glance_vids", array(get_class(), 'my_embedplus_glance_vids'));
            add_action("wp_ajax_my_embedplus_glance_count", array(get_class(), 'my_embedplus_glance_count'));
            add_action("wp_ajax_my_embedplus_dismiss_double_plugin_warning", array(get_class(), 'my_embedplus_dismiss_double_plugin_warning'));
            add_action("wp_ajax_my_embedplus_gallery_page", array(get_class(), 'my_embedplus_gallery_page'));
            add_action("wp_ajax_nopriv_my_embedplus_gallery_page", array(get_class(), 'my_embedplus_gallery_page'));
            add_action('admin_enqueue_scripts', array(get_class(), 'admin_enqueue_scripts'));
        }
    }

    public static function check_free_version()
    {
        $active_plugins = get_option('active_plugins', array());
        if (in_array('youtube-embed-plus/youtube.php', $active_plugins))
        {
            $class = 'notice notice-error is-dismissible';
            $message = __('For YouTube Pro to work, please deactivate the free version.', 'sample-text-domain');

            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), wp_kses_post($message));
        }
    }

    public static function my_plugin_action_links($links)
    {
        $links[] = '<a href="' . esc_url(admin_url('admin.php?page=youtube-my-preferences')) . '">Settings</a>';

        return $links;
    }

    public static function ytprefs_admin_bar_scripts()
    {
        if (current_user_can('edit_posts'))
        {
            wp_enqueue_script('__ytprefs__bar', plugins_url('scripts/ytprefs-bar' . self::$min . '.js', __FILE__), array('jquery'));
            wp_localize_script('__ytprefs__bar', '_EPYTB_', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'security' => wp_create_nonce('embedplus-nonce'),
                'pluginurl' => plugins_url('/', __FILE__)
            ));
        }
    }

    public static function ytprefs_admin_bar($wp_admin_bar)
    {

        if (current_user_can('edit_posts') && self::$alloptions[self::$opt_spdc] == 1 && self::$alloptions[self::$opt_spdcab] == 1)
        {
            $args = array(
                'id' => 'ytprefs-bar-cache',
                'title' => 'Clear YouTube Cache',
                'href' => '#',
                'meta' => array(
                    'class' => ''
                )
            );
            $wp_admin_bar->add_node($args);
        }
    }

    public static function show_glance_list()
    {
        $glancehref = self::show_glance();
        $cnt = self::get_glance_count();

        //display via list
        return
                '<li class="page-count">
            <a href="' . $glancehref . '" class="thickbox ytprefs_glance_button" id="ytprefs_glance_button" title="YouTube Embeds At a Glance">
                ' . number_format_i18n($cnt) . ' With YouTube
            </a>
        </li>';
    }

    public static function show_glance_table()
    {
        $glancehref = self::show_glance();
        $cnt = self::get_glance_count();
        return
                '<tr>
            <td class="first b"><a title="YouTube Embeds At a Glance" href="' . $glancehref . '" class="thickbox ytprefs_glance_button">' . number_format_i18n($cnt) . '</a></td>
            <td class="t"><a title="YouTube Embeds At a Glance" href="' . $glancehref . '" id="ytprefs_glance_button" class="thickbox ytprefs_glance_button">With YouTube</a></td>
        </tr>';
    }

    public static function get_glance_count()
    {
        global $wpdb;
        $query_sql = "
                SELECT count(*) as mytotal
                FROM $wpdb->posts
                WHERE (post_content LIKE '%youtube.com/%' OR post_content LIKE '%youtu.be/%')
                AND post_status = 'publish'";

        $query_result = $wpdb->get_results($query_sql, OBJECT);

        return intval($query_result[0]->mytotal);
    }

    public static function show_glance()
    {
        $glancehref = admin_url('admin.php?page=youtube-ep-glance') . '&random=' . rand(1, 1000) . '&TB_iframe=true&width=780&height=800';
        return $glancehref;
    }

    public static function glance_page()
    {
        ?>
        <div class="wrap">
            <style type="text/css">
                #wphead {display:none;}
                #wpbody{margin-left: 0px;}
                .wrap {font-family: Arial; padding: 0px 10px 0px 10px; line-height: 180%;}
                .bold {font-weight: bold;}
                .orange {color: #f85d00;}
                #adminmenuback {display: none;}
                #adminmenu, adminmenuwrap {display: none;}
                #wpcontent, .auto-fold #wpcontent {margin-left: 0px;}
                #wpadminbar {display:none;}
                html.wp-toolbar {padding: 0px;}
                #footer, #wpfooter, .auto-fold #wpfooter {display: none;}
                #wpfooter {clear: both}
                .acctitle {background-color: #dddddd; border-radius: 5px; padding: 7px 15px 7px 15px; cursor: pointer; margin: 10px; font-weight: bold; font-size: 12px;}
                .acctitle:hover {background-color: #cccccc;}
                .accbox {display: none; position: relative; margin:  5px 8px 30px 15px; clear: both; line-height: 180%;}
                .accclose {position: absolute; top: -38px; right: 5px; cursor: pointer; width: 24px; height: 24px;}
                .accloader {padding-right: 20px;}
                .accthumb {display: block; width: 300px; float: left; margin-right: 25px;}
                .accinfo {width: 300px; float: left;}
                .accvidtitle {font-weight: bold; font-size: 16px;}
                .accthumb img {width: 300px; height: auto; display: block;}
                .clearboth {clear: both;}
                .pad20 {padding: 20px;}
                .center {text-align: center;}
            </style>
            <script type="text/javascript">
                function accclose(ele)
                {
                    jQuery(ele).parent('.accbox').hide(400);
                }

                (function ($j)
                {
                    $j(document).ready(function () {


                        $j('.acctitle').click(function () {
                            var $acctitle = $j(this);
                            var $accbox = $j(this).parent().children('.accbox');
                            var pid = $accbox.attr("data-postid");

                            $acctitle.prepend('<img alt="loading" class="accloader" src="<?php echo plugins_url('images/ajax-loader.gif', __FILE__) ?>" />');
                            jQuery.ajax({
                                type: "post",
                                dataType: "json",
                                timeout: 30000,
                                url: _EPYTA_.wpajaxurl,
                                data: {action: 'my_embedplus_glance_vids', postid: pid},
                                success: function (response) {
                                    if (response.type == "success") {
                                        $accbox.html(response.data),
                                                $accbox.show(400);
                                    }
                                    else {
                                    }
                                },
                                error: function (xhr, ajaxOptions, thrownError) {

                                },
                                complete: function () {
                                    $acctitle.children('.accloader').remove();
                                }

                            });


                        });
                    });
                })(jQuery);


            </script>
            <?php
            global $wpdb;
            $query_sql = "
                SELECT SQL_CALC_FOUND_ROWS *
                FROM $wpdb->posts
                WHERE (post_content LIKE '%youtube.com/%' OR post_content LIKE '%youtu.be/%')
                AND post_status = 'publish'
                order by post_date DESC LIMIT 0, 10";

            $query_result = $wpdb->get_results($query_sql, OBJECT);

            if ($query_result !== null)
            {
                $total = $wpdb->get_var("SELECT FOUND_ROWS();");
                global $post;
                echo '<h2><img alt="YouTube Plugin Icon" src="' . plugins_url('images/youtubeicon16.png', __FILE__) . '" /> 10 Latest Posts/Pages with YouTube Videos (' . $total . ' Total)</h2>';
                ?>

                We recommend using this page as an easy way to check the results of the global default settings you make (e.g. hide annotations) on your recent embeds. Or, simply use it as an index to jump right to your posts that contain YouTube embeds.

                <?php
                if ($total > 0)
                {
                    echo '<ul class="accord">';
                    foreach ($query_result as $post)
                    {
                        echo '<li>';
                        setup_postdata($post);
                        the_title('<div class="acctitle">', ' &raquo;</div>');
                        echo '<div class="accbox" data-postid="' . $post->ID . '"></div><div class="clearboth"></div></li>';
                    }
                    echo '</ul>';
                }
                else
                {
                    echo '<p class="center bold orange">You currently do not have any YouTube embeds yet.</p>';
                }
            }

            wp_reset_postdata();
            ?>
            To remove this feature from your dashboard, simply uncheck <i>Show "At a Glance" Embed Links</i> in the <a target="_blank" href="<?php echo admin_url('admin.php?page=youtube-my-preferences#jumpdefaults') ?>">plugin settings page &raquo;</a>.

        </div>
        <?php
    }

    public static function my_embedplus_glance_vids()
    {
        $result = array();
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        {
            $postid = intval($_REQUEST['postid']);
            $currpost = get_post($postid);

            $thehtml = '<img alt="close" class="accclose" onclick="accclose(this)" src="' . plugins_url('images/accclose.png', __FILE__) . '" />';

            $matches = Array();
            $ismatch = preg_match_all(self::$justurlregex, $currpost->post_content, $matches);

            if ($ismatch)
            {
                foreach ($matches[0] as $match)
                {
                    $link = trim(preg_replace('/&amp;/i', '&', $match));
                    $link = preg_replace('/\s/', '', $link);
                    $link = trim(str_replace(self::$badentities, self::$goodliterals, $link));

                    $linkparamstemp = explode('?', $link);

                    $linkparams = array();
                    if (count($linkparamstemp) > 1)
                    {
                        $linkparams = self::keyvalue($linkparamstemp[1], true);
                    }
                    if (strpos($linkparamstemp[0], 'youtu.be') !== false && !isset($linkparams['v']))
                    {
                        $vtemp = explode('/', $linkparamstemp[0]);
                        $linkparams['v'] = array_pop($vtemp);
                    }

                    $vidid = $linkparams['v'];

                    if ($vidid != null)
                    {
                        try
                        {
                            $odata = self::get_oembed('https://youtube.com/watch?v=' . $vidid, 1920, 1280);
                            $postlink = get_permalink($postid);
                            if ($odata != null && !is_wp_error($odata))
                            {
                                $_name = esc_attr(sanitize_text_field($odata->title));
                                $_description = esc_attr(sanitize_text_field($odata->author_name));
                                $_thumbnailUrl = esc_url("https://i.ytimg.com/vi/" . $vidid . "/0.jpg");

                                $thehtml .= '<a target="_blank" href="' . $postlink . '" class="accthumb"><img alt="YouTube Video" src="' . $_thumbnailUrl . '" /></a>';
                                $thehtml .= '<div class="accinfo">';
                                $thehtml .= '<a target="_blank" href="' . $postlink . '" class="accvidtitle">' . $_name . '</a>';
                                $thehtml .= '<div class="accdesc">' . (strlen($_description) > 400 ? substr($_description, 0, 400) . "..." : $_description) . '</div>';
                                $thehtml .= '</div>';
                                $thehtml .= '<div class="clearboth pad20"></div>';
                            }
                            else
                            {
                                $thehtml .= '<p class="center bold orange">This <a target="_blank" href="' . $postlink . '">post/page</a> contains a video that has been removed from YouTube.';

                                if (!(self::$alloptions[self::$opt_pro] && strlen(trim(self::$alloptions[self::$opt_pro])) > 0))
                                {
                                    $thehtml .='<br><a target="_blank" href="https://www.embedplus.com/dashboard/pro-easy-video-analytics.aspx">Activate delete video tracking to catch these cases &raquo;</a>';
                                }
                                $thehtml .= '</strong>';
                            }
                        }
                        catch (Exception $ex)
                        {
                            
                        }
                    }
                    else if (isset($linkparams['list']))
                    {
                        // if playlist
                        try
                        {
                            $odata = self::get_oembed('https://youtube.com/playlist?list=' . $linkparams['list'], 1920, 1280);
                            $postlink = get_permalink($postid);
                            if ($odata != null && !is_wp_error($odata))
                            {
                                $_name = esc_attr(sanitize_text_field($odata->title));
                                $_description = esc_attr(sanitize_text_field($odata->author_name));
                                $_thumbnailUrl = esc_url($odata->thumbnail_url);

                                $thehtml .= '<a target="_blank" href="' . $postlink . '" class="accthumb"><img alt="YouTube Video" src="' . $_thumbnailUrl . '" /></a>';
                                $thehtml .= '<div class="accinfo">';
                                $thehtml .= '<a target="_blank" href="' . $postlink . '" class="accvidtitle">' . $_name . '</a>';
                                $thehtml .= '<div class="accdesc">' . (strlen($_description) > 400 ? substr($_description, 0, 400) . "..." : $_description) . '</div>';
                                $thehtml .= '</div>';
                                $thehtml .= '<div class="clearboth pad20"></div>';
                            }
                            else
                            {
                                $thehtml .= '<p class="center bold orange">This <a target="_blank" href="' . $postlink . '">post/page</a> contains a video that has been removed from YouTube.';

                                if (!(self::$alloptions[self::$opt_pro] && strlen(trim(self::$alloptions[self::$opt_pro])) > 0))
                                {
                                    $thehtml .='<br><a target="_blank" href="https://www.embedplus.com/dashboard/pro-easy-video-analytics.aspx">Activate delete video tracking to catch these cases &raquo;</a>';
                                }
                                $thehtml .= '</strong>';
                            }
                        }
                        catch (Exception $ex)
                        {
                            
                        }
                    }
                }
            }



            if ($currpost != null)
            {
                $result['type'] = 'success';
                $result['data'] = $thehtml;
            }
            else
            {
                $result['type'] = 'error';
            }
            echo json_encode($result);
        }
        else
        {
            $result['type'] = 'error';
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
        die();
    }

    public static function my_embedplus_glance_count()
    {
        $result = array();
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        {
            $thehtml = '';

            try
            {
                if (version_compare(get_bloginfo('version'), '3.8', '>='))
                {
                    $result['container'] = '#dashboard_right_now ul';
                    $thehtml .= self::show_glance_list();
                }
                else
                {
                    $result['container'] = '#dashboard_right_now .table_content table tbody';
                    $thehtml .= self::show_glance_table();
                }
                $result['type'] = 'success';
                $result['data'] = $thehtml;
            }
            catch (Exception $e)
            {
                $result['type'] = 'error';
            }

            echo json_encode($result);
        }
        else
        {
            $result['type'] = 'error';
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
        die();
    }

    public static function media_button_wizard()
    {
        add_thickbox();

        $wizhref = self::$epbase . '/wpembedcode-simple-search-' . self::$version . '.aspx?pluginversion=' . self::$version .
                '&wpversion=' . get_bloginfo('version') .
                '&settingsurl=' . urlencode(admin_url('admin.php?page=youtube-my-preferences#jumpdefaults')) .
                '&dashurl=' . urlencode(admin_url('admin.php?page=youtube-ep-analytics-dashboard')) .
                '&blogwidth=' . self::get_blogwidth() .
                '&domain=' . urlencode(site_url()) .
                '&prokey=' . urlencode(self::$alloptions[self::$opt_pro]) .
                '&myytdefaults=' . urlencode(http_build_query(self::$alloptions)) .
                '&random=' . rand(1, 1000) .
                '&TB_iframe=true&width=950&height=800';
        ?>
        <a href="<?php echo $wizhref; ?>" class="thickbox button ytprefs_media_link" id="ytprefs_wiz_button" title="Visual YouTube Search Tool and Wizard - An easier embedding option"><span></span> YouTube</a>
        <?php
    }

    public static function check_double_plugin_warning()
    {
        if (is_plugin_active('embedplus-for-wordpress/embedplus.php'))
        {
            add_action('admin_notices', array(get_class(), "double_plugin_warning"));
            //self::$double_plugin = true;
        }
    }

    public static function double_plugin_warning()
    {
        global $pagenow;
        $user_id = get_current_user_id();
        if ($pagenow != 'plugins.php' || get_user_meta($user_id, 'embedplus_double_plugin_warning', true) != 1)
        {
            //echo '<div class="error">' . $_SERVER['QUERY_STRING'] .'</div>';
            if ($pagenow == 'plugins.php' || strpos($_SERVER['QUERY_STRING'], 'youtube-my-preferences') !== false ||
                    strpos($_SERVER['QUERY_STRING'], 'embedplus-video-analytics-dashboard') !== false ||
                    strpos($_SERVER['QUERY_STRING'], 'youtube-ep-analytics-dashboard') !== false ||
                    strpos($_SERVER['QUERY_STRING'], 'embedplus-official-options') !== false)
            {
                ?>
                <style type="text/css">
                    .embedpluswarning img
                    {
                        vertical-align: text-bottom;
                    }
                    div.bgyellow {background-color: #FCFC94; position: relative;}
                    a.epxout, a.epxout:hover {font-weight: bold; color: #ffffff; background-color: #ff8888; text-decoration: none;
                                              border-radius: 20px; font-size: 15px; position: absolute; top: 3px; right: 3px;
                                              line-height: 20px; text-align: center; width: 20px; height: 20px; display: block; cursor: pointer;}
                    </style>
                    <div class="error bgyellow embedpluswarningbox">
                    <p class="embedpluswarning">
                        <?php
                        if ($pagenow == 'plugins.php')
                        {
                            echo '<a class="epxout">&times;</a>';
                        }
                        ?>
                        Seems like you have two different YouTube plugins by the EmbedPlus Team installed: <b><img alt="YouTube Icon" src="<?php echo plugins_url('images/youtubeicon16.png', __FILE__) ?>" /> YouTube</b> and <b><img alt="YouTube Icon" src="<?php echo plugins_url('images/btn_embedpluswiz.png', __FILE__) ?>" /> Advanced YouTube Embed.</b> We strongly suggest keeping only the one you prefer, so that they don't conflict with each other while trying to create your embeds.</p>
                </div>
                <iframe allowTransparency="true" src="<?php echo self::$epbase . '/both-plugins-conflict.aspx' ?>" style="width:2px; height: 2px;" ></iframe>
                <script type="text/javascript">
                    (function ($) {
                        $(document).ready(function () {
                            $('.epxout').click(function () {
                                $.ajax({
                                    type: "post",
                                    dataType: "json",
                                    timeout: 30000,
                                    url: _EPYTA_.wpajaxurl,
                                    data: {action: 'my_embedplus_dismiss_double_plugin_warning'},
                                    success: function (response) {
                                        if (response.type == "success") {
                                            $(".embedpluswarningbox").hide();
                                        }
                                    },
                                    error: function (xhr, ajaxOptions, thrownError) {
                                    },
                                    complete: function () {
                                    }
                                });
                            });

                        });
                    })(jQuery);
                </script>
                <?php
            }
        }
    }

    public static function my_embedplus_dismiss_double_plugin_warning()
    {
        $result = array();
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        {
            $user_id = get_current_user_id();
            update_user_meta($user_id, 'embedplus_double_plugin_warning', 1);
            $result['type'] = 'success';
            echo json_encode($result);
        }
        else
        {
            $result['type'] = 'error';
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
        die();
    }

    public static function jsvars()
    {
        $loggedin = current_user_can('edit_posts');
        if (!($loggedin && self::$alloptions[self::$opt_admin_off_scripts]))
        {
            ?>
            <script data-cfasync="false">
                window._EPYT_ = window._EPYT_ || {
                    ajaxurl: "<?php echo admin_url('admin-ajax.php'); ?>",
                    security: "<?php echo wp_create_nonce('embedplus-nonce'); ?>",
                    gallery_scrolloffset: <?php echo intval(self::$alloptions[self::$opt_gallery_scrolloffset]) ?>,
                    eppathtoscripts: "<?php echo plugins_url('scripts/', __FILE__); ?>",
                    epresponsiveselector: <?php echo self::get_responsiveselector(); ?>,
                    version: "<?php echo self::$alloptions[self::$opt_version] ?>",
                    epdovol: true,
                    evselector: '<?php echo self::get_evselector(); ?>',
            <?php
            if (isset(self::$alloptions[self::$opt_pro]) && strlen(trim(self::$alloptions[self::$opt_pro])) > 8 && isset(self::$alloptions[self::$opt_dashpre]) && self::$alloptions[self::$opt_dashpre] == '1')
            {
                ?> dshpre:true,<?php } ?>
                    stopMobileBuffer: <?php echo self::$alloptions[self::$opt_stop_mobile_buffer] == '1' ? 'true' : 'false' ?>
                };
            </script>
            <?php
        }
    }

    public static function fitvids()
    {
        $loggedin = current_user_can('edit_posts');
        if (!($loggedin && self::$alloptions[self::$opt_admin_off_scripts]))
        {
            wp_enqueue_script('__ytprefsfitvids__', plugins_url('scripts/fitvids' . self::$min . '.js', __FILE__), array('__ytprefs__'), false, true);
        }
    }

    public static function initoptions()
    {
        //vanilla defaults
        $_center = 0;
        $_glance = 1;
        $_autoplay = 0;
        $_cc_load_policy = 0;
        $_iv_load_policy = 1;
        $_loop = 0;
        $_modestbranding = 0;
        $_rel = 1;
        $_showinfo = 1;
        $_html5 = 0;
        $_theme = 'dark';
        $_color = 'red';
        $_vq = '';
        $_autohide = 2;
        $_pro = '';
        $_ssl = 0;
        $_nocookie = 0;
        $_playlistorder = 0;
        $_acctitle = 0;
        $_ogvideo = 0;
        $_migrate = 0;
        $_migrate_youtube = 0;
        $_migrate_embedplusvideo = 0;
        $_controls = 2;
        $_oldspacing = 1;
        $_responsive = 0;
        $_responsive_all = 1;
        $_widgetfit = 1;
        $_evselector_light = 0;
        $_stop_mobile_buffer = 1;
        $_schemaorg = 0;
        $_ftpostimg = 0;
        $_spdc = 0;
        $_spdcexp = 24;
        $_dashpre = 0;
        $_spdcab = 1;
        $_dynload = 0;
        $_dyntype = '';
        $_wmode = 'opaque';
        $_defaultdims = 0;
        $_defaultwidth = '';
        $_defaultheight = '';
        $_playsinline = 0;
        $_origin = 0;
        $_defaultvol = 0;
        $_vol = '';
        $_apikey = '';
        $_hl = '';
        $_dohl = 0;
        $_gallery_columns = 3;
        $_gallery_collapse_grid = 0;
        $_gallery_collapse_grid_breaks = self::$dft_bpts;
        $_gallery_scrolloffset = 20;
        $_gallery_showtitle = 1;
        $_gallery_showpaging = 1;
        $_gallery_autonext = 0;
        $_gallery_thumbplay = 1;
        $_gallery_channelsub = 0;
        $_gallery_channelsublink = '';
        $_gallery_channelsubtext = 'Subscribe to my channel';
        $_gallery_customarrows = 0;
        $_gallery_customprev = 'Prev';
        $_gallery_customnext = 'Next';
        $_gallery_pagesize = 15;
        $_gallery_style = 'grid';
        $_gallery_showdsc = 0;
        $_gallery_thumbcrop = 'box';
        $_gallery_disptype = 'default';
        $_not_live_content = '';
        $_debugmode = 0;
        $_admin_off_scripts = 0;
        $_old_script_method = 0;

        $_free_migrated = 0;

        $arroptions = get_option(self::$opt_alloptions);
        if ($arroptions !== false)
        {
            $bak = str_replace('.', '_', $arroptions[self::$opt_version]);
            add_option(self::$opt_alloptions . '_backup_' . $bak, $arroptions);
        }

        if ($arroptions == false || (is_array($arroptions) && isset($arroptions[self::$opt_free_migrated]) && $arroptions[self::$opt_free_migrated] == 0))
        {
            $arr_free_migrate_options = get_option('youtubeprefs_alloptions_migrate');
            if ($arr_free_migrate_options == false)
            {
                $arr_free_migrate_options = get_option('youtubeprefs_alloptions');
            }

            if ($arr_free_migrate_options != false)
            {
                if ($arroptions == false)
                {
                    $arroptions = $arr_free_migrate_options;
                }
                else
                {
                    $arroptions = $arr_free_migrate_options + $arroptions;
                }
                $arroptions[self::$opt_free_migrated] = 1;
            }
        }

        //update vanilla to previous settings if exists
        if ($arroptions !== false)
        {
            $_center = self::tryget($arroptions, self::$opt_center, 0);
            $_glance = self::tryget($arroptions, self::$opt_glance, 1);
            $_autoplay = self::tryget($arroptions, self::$opt_autoplay, 0);
            $_debugmode = self::tryget($arroptions, self::$opt_debugmode, 0);
            $_old_script_method = self::tryget($arroptions, self::$opt_old_script_method, 0);
            $_cc_load_policy = self::tryget($arroptions, self::$opt_cc_load_policy, 0);
            $_iv_load_policy = self::tryget($arroptions, self::$opt_iv_load_policy, 1);
            $_loop = self::tryget($arroptions, self::$opt_loop, 0);
            $_modestbranding = self::tryget($arroptions, self::$opt_modestbranding, 0);
            $_rel = self::tryget($arroptions, self::$opt_rel, 1);
            $_showinfo = self::tryget($arroptions, self::$opt_showinfo, 1);
            $_playsinline = self::tryget($arroptions, self::$opt_playsinline, 0);
            $_origin = self::tryget($arroptions, self::$opt_origin, 0);
            $_html5 = self::tryget($arroptions, self::$opt_html5, 0);
            $_hl = self::tryget($arroptions, self::$opt_hl, '');
            $_dohl = self::tryget($arroptions, self::$opt_dohl, 0);
            $_theme = self::tryget($arroptions, self::$opt_theme, 'dark');
            $_color = self::tryget($arroptions, self::$opt_color, 'red');
            $_wmode = self::tryget($arroptions, self::$opt_wmode, 'opaque');
            $_vq = self::tryget($arroptions, self::$opt_vq, '');
            $_pro = self::tryget($arroptions, self::$opt_pro, '');
            $_ssl = self::tryget($arroptions, self::$opt_ssl, 0);
            $_nocookie = self::tryget($arroptions, self::$opt_nocookie, 0);
            $_playlistorder = self::tryget($arroptions, self::$opt_playlistorder, 0);
            $_acctitle = self::tryget($arroptions, self::$opt_acctitle, 0);
            $_ogvideo = self::tryget($arroptions, self::$opt_ogvideo, 0);
            $_migrate = self::tryget($arroptions, self::$opt_migrate, 0);
            $_migrate_youtube = self::tryget($arroptions, self::$opt_migrate_youtube, 0);
            $_migrate_embedplusvideo = self::tryget($arroptions, self::$opt_migrate_embedplusvideo, 0);
            $_controls = self::tryget($arroptions, self::$opt_controls, 2);
            $_autohide = self::tryget($arroptions, self::$opt_autohide, 2);
            $_oldspacing = self::tryget($arroptions, self::$opt_oldspacing, 1);
            $_responsive = self::tryget($arroptions, self::$opt_responsive, 0);
            $_responsive_all = self::tryget($arroptions, self::$opt_responsive_all, 1);
            $_widgetfit = self::tryget($arroptions, self::$opt_widgetfit, 1);
            $_evselector_light = self::tryget($arroptions, self::$opt_evselector_light, 0);
            $_stop_mobile_buffer = self::tryget($arroptions, self::$opt_stop_mobile_buffer, 1);
            $_schemaorg = self::tryget($arroptions, self::$opt_schemaorg, 0);
            $_ftpostimg = self::tryget($arroptions, self::$opt_ftpostimg, 0);
            $_spdc = self::tryget($arroptions, self::$opt_spdc, 0);
            $_spdcexp = self::tryget($arroptions, self::$opt_spdcexp, 24);
            $_dashpre = self::tryget($arroptions, self::$opt_dashpre, 0);
            $_spdcab = self::tryget($arroptions, self::$opt_spdcab, 1);
            $_dynload = self::tryget($arroptions, self::$opt_dynload, 0);
            $_dyntype = self::tryget($arroptions, self::$opt_dyntype, '');
            $_defaultdims = self::tryget($arroptions, self::$opt_defaultdims, 0);
            $_defaultwidth = self::tryget($arroptions, self::$opt_defaultwidth, '');
            $_defaultheight = self::tryget($arroptions, self::$opt_defaultheight, '');
            $_defaultvol = self::tryget($arroptions, self::$opt_defaultvol, 0);
            $_vol = self::tryget($arroptions, self::$opt_vol, '');
            $_apikey = self::tryget($arroptions, self::$opt_apikey, '');
            $_gallery_pagesize = self::tryget($arroptions, self::$opt_gallery_pagesize, 15);
            $_gallery_columns = self::tryget($arroptions, self::$opt_gallery_columns, 3);
            $_gallery_collapse_grid = self::tryget($arroptions, self::$opt_gallery_collapse_grid, 0);
            $_gallery_collapse_grid_breaks = self::tryget($arroptions, self::$opt_gallery_collapse_grid_breaks, self::$dft_bpts);
            $_gallery_scrolloffset = self::tryget($arroptions, self::$opt_gallery_scrolloffset, 20);
            $_gallery_showtitle = self::tryget($arroptions, self::$opt_gallery_showtitle, 1);
            $_gallery_showpaging = self::tryget($arroptions, self::$opt_gallery_showpaging, 1);
            $_gallery_autonext = self::tryget($arroptions, self::$opt_gallery_autonext, 0);
            $_gallery_thumbplay = self::tryget($arroptions, self::$opt_gallery_thumbplay, 1);
            $_gallery_style = self::tryget($arroptions, self::$opt_gallery_style, 'grid');
            $_gallery_thumbcrop = self::tryget($arroptions, self::$opt_gallery_thumbcrop, 'box');
            $_gallery_disptype = self::tryget($arroptions, self::$opt_gallery_disptype, 'default');
            $_gallery_channelsub = self::tryget($arroptions, self::$opt_gallery_channelsub, $_gallery_channelsub);
            $_gallery_channelsublink = self::tryget($arroptions, self::$opt_gallery_channelsublink, $_gallery_channelsublink);
            $_gallery_channelsubtext = self::tryget($arroptions, self::$opt_gallery_channelsubtext, $_gallery_channelsubtext);
            $_gallery_customarrows = self::tryget($arroptions, self::$opt_gallery_customarrows, $_gallery_customarrows);
            $_gallery_customnext = self::tryget($arroptions, self::$opt_gallery_customnext, $_gallery_customnext);
            $_gallery_customprev = self::tryget($arroptions, self::$opt_gallery_customprev, $_gallery_customprev);
            $_gallery_showdsc = self::tryget($arroptions, self::$opt_gallery_showdsc, $_gallery_showdsc);
            $_not_live_content = self::tryget($arroptions, self::$opt_not_live_content, $_not_live_content);
            $_admin_off_scripts = self::tryget($arroptions, self::$opt_admin_off_scripts, $_admin_off_scripts);

            $_free_migrated = self::tryget($arroptions, self::$opt_free_migrated, 0);
        }
        else
        {
            $_oldspacing = 0;
        }

        $all = array(
            self::$opt_version => self::$version,
            self::$opt_center => $_center,
            self::$opt_glance => $_glance,
            self::$opt_autoplay => $_autoplay,
            self::$opt_cc_load_policy => $_cc_load_policy,
            self::$opt_iv_load_policy => $_iv_load_policy,
            self::$opt_loop => $_loop,
            self::$opt_modestbranding => $_modestbranding,
            self::$opt_rel => $_rel,
            self::$opt_showinfo => $_showinfo,
            self::$opt_playsinline => $_playsinline,
            self::$opt_origin => $_origin,
            self::$opt_autohide => $_autohide,
            self::$opt_html5 => $_html5,
            self::$opt_hl => $_hl,
            self::$opt_dohl => $_dohl,
            self::$opt_theme => $_theme,
            self::$opt_color => $_color,
            self::$opt_wmode => $_wmode,
            self::$opt_vq => $_vq,
            self::$opt_pro => $_pro,
            self::$opt_ssl => $_ssl,
            self::$opt_nocookie => $_nocookie,
            self::$opt_playlistorder => $_playlistorder,
            self::$opt_acctitle => $_acctitle,
            self::$opt_ogvideo => $_ogvideo,
            self::$opt_migrate => $_migrate,
            self::$opt_migrate_youtube => $_migrate_youtube,
            self::$opt_migrate_embedplusvideo => $_migrate_embedplusvideo,
            self::$opt_controls => $_controls,
            self::$opt_oldspacing => $_oldspacing,
            self::$opt_responsive => $_responsive,
            self::$opt_responsive_all => $_responsive_all,
            self::$opt_widgetfit => $_widgetfit,
            self::$opt_evselector_light => $_evselector_light,
            self::$opt_stop_mobile_buffer => $_stop_mobile_buffer,
            self::$opt_schemaorg => $_schemaorg,
            self::$opt_ftpostimg => $_ftpostimg,
            self::$opt_spdc => $_spdc,
            self::$opt_spdcexp => $_spdcexp,
            self::$opt_dashpre => $_dashpre,
            self::$opt_spdcab => $_spdcab,
            self::$opt_dynload => $_dynload,
            self::$opt_dyntype => $_dyntype,
            self::$opt_defaultdims => $_defaultdims,
            self::$opt_defaultwidth => $_defaultwidth,
            self::$opt_defaultheight => $_defaultheight,
            self::$opt_defaultvol => $_defaultvol,
            self::$opt_vol => $_vol,
            self::$opt_apikey => $_apikey,
            self::$opt_gallery_columns => $_gallery_columns,
            self::$opt_gallery_collapse_grid => $_gallery_collapse_grid,
            self::$opt_gallery_collapse_grid_breaks => $_gallery_collapse_grid_breaks,
            self::$opt_gallery_scrolloffset => $_gallery_scrolloffset,
            self::$opt_gallery_showtitle => $_gallery_showtitle,
            self::$opt_gallery_showpaging => $_gallery_showpaging,
            self::$opt_gallery_autonext => $_gallery_autonext,
            self::$opt_gallery_thumbplay => $_gallery_thumbplay,
            self::$opt_gallery_channelsub => $_gallery_channelsub,
            self::$opt_gallery_channelsublink => $_gallery_channelsublink,
            self::$opt_gallery_channelsubtext => $_gallery_channelsubtext,
            self::$opt_gallery_customarrows => $_gallery_customarrows,
            self::$opt_gallery_customnext => $_gallery_customnext,
            self::$opt_gallery_customprev => $_gallery_customprev,
            self::$opt_gallery_showdsc => $_gallery_showdsc,
            self::$opt_gallery_style => $_gallery_style,
            self::$opt_gallery_thumbcrop => $_gallery_thumbcrop,
            self::$opt_gallery_disptype => $_gallery_disptype,
            self::$opt_gallery_pagesize => $_gallery_pagesize,
            self::$opt_not_live_content => $_not_live_content,
            self::$opt_debugmode => $_debugmode,
            self::$opt_admin_off_scripts => $_admin_off_scripts,
            self::$opt_old_script_method => $_old_script_method,
            self::$opt_free_migrated => $_free_migrated
        );

        update_option(self::$opt_alloptions, $all);
        update_option('embed_autourls', 1);
        self::$alloptions = get_option(self::$opt_alloptions);

        try
        {
            if (self::$alloptions[self::$opt_spdc] == 1)
            {
                self::spdcpurge();
                wp_remote_get(site_url(), array('timeout' => self::$curltimeout));
            }
        }
        catch (Exception $ex)
        {
            
        }
    }

    public static function tryget($array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }

    public static function wp_above_version($ver)
    {
        global $wp_version;
        if (version_compare($wp_version, $ver, '>='))
        {
            return true;
        }
        return false;
    }

    public static function do_ytprefs()
    {
        //add_filter('autoptimize_filter_js_exclude', array(get_class(), 'ao_override_jsexclude'), 10, 1);
        if (!is_admin())
        {
            add_filter('the_content', array(get_class(), 'apply_prefs_content'), 1);
            add_filter('widget_text', array(get_class(), 'apply_prefs_widget'), 1);
            add_shortcode('embedyt', array(get_class(), 'apply_prefs_shortcode'));
            if (self::$alloptions[self::$opt_migrate] == 1)
            {
                if (self::$alloptions[self::$opt_migrate_youtube] == 1)
                {
                    add_shortcode('youtube', array(get_class(), 'apply_prefs_shortcode_youtube'));
                    add_shortcode('youtube_video', array(get_class(), 'apply_prefs_shortcode_youtube'));
                }
                if (self::$alloptions[self::$opt_migrate_embedplusvideo] == 1)
                {
                    add_shortcode('embedplusvideo', array(get_class(), 'apply_prefs_shortcode_embedplusvideo'));
                }
            }
        }
        else
        {
            if (self::$alloptions[self::$opt_ftpostimg] == 1 && self::$alloptions[self::$opt_pro] && strlen(trim(self::$alloptions[self::$opt_pro])) > 0)
            {
                add_action('save_post', array(get_class(), 'doftpostimg'), 110, 3);
            }
        }
    }

    public static function ao_override_jsexclude($exclude)
    {
        if (strpos($exclude, 'ytprefs' . self::$min . '.js') === false)
        {
            return $exclude . ',ytprefs' . self::$min . '.js,__ytprefs__';
        }
        return $exclude;
    }

    public static function apply_prefs_shortcode($atts, $content = null)
    {
        $content = trim($content);
        $currfilter = current_filter();
        if (preg_match(self::$justurlregex, $content))
        {
            return self::get_html(array($content), $currfilter == 'widget_text' ? false : true);
        }
        return '';
    }

    public static function apply_prefs_shortcode_youtube($atts, $content = null)
    {
        $content = 'https://www.youtube.com/watch?v=' . trim($content);
        $currfilter = current_filter();
        if (preg_match(self::$justurlregex, $content))
        {
            return self::get_html(array($content), $currfilter == 'widget_text' ? false : true);
        }
        return '';
    }

    public static function apply_prefs_shortcode_embedplusvideo($atts, $content = null)
    {
        $atts = shortcode_atts(array(
            "height" => self::$defaultheight,
            "width" => self::$defaultwidth,
            "vars" => "",
            "standard" => "",
            "id" => "ep" . rand(10000, 99999)
                ), $atts);

        $epvars = $atts['vars'];
        $epvars = preg_replace('/\s/', '', $epvars);
        $epvars = preg_replace('/¬/', '&not', $epvars);
        $epvars = str_replace('&amp;', '&', $epvars);

        $epparams = self::keyvalue($epvars, true);

        if (isset($epparams) && isset($epparams['ytid']))
        {
            $start = isset($epparams['start']) && is_numeric($epparams['start']) ? '&start=' . intval($epparams['start']) : '';
            $end = isset($epparams['end']) && is_numeric($epparams['end']) ? '&end=' . intval($epparams['end']) : '';
            $end = isset($epparams['stop']) && is_numeric($epparams['stop']) ? '&end=' . intval($epparams['stop']) : '';

            $url = 'https://www.youtube.com/watch?v=' . trim($epparams['ytid']) . $start . $end;

            $currfilter = current_filter();
            if (preg_match(self::$justurlregex, $url))
            {
                return self::get_html(array($url), $currfilter == 'widget_text' ? false : true);
            }
        }
        return '';
    }

    public static function apply_prefs_content($content)
    {
        $content = preg_replace_callback(self::$ytregex, array(get_class(), "get_html_content"), $content);
        return $content;
    }

    public static function apply_prefs_widget($content)
    {
        $content = preg_replace_callback(self::$ytregex, array(get_class(), "get_html_widget"), $content);
        return $content;
    }

    public static function get_html_content($m)
    {
        return self::get_html($m, true);
    }

    public static function get_html_widget($m)
    {
        return self::get_html($m, false);
    }

    public static function get_gallery_page($options)
    {
        $gallobj = new stdClass();

        $options->pageSize = min(intval($options->pageSize), 50);
        $options->columns = intval($options->columns);
        $options->showTitle = intval($options->showTitle);
        $options->showPaging = intval($options->showPaging);
        $options->autonext = intval($options->autonext);
        $options->thumbplay = intval($options->thumbplay);
        $options->showDsc = intval($options->showDsc);
        $options->thumbcrop = sanitize_html_class($options->thumbcrop);
        $options->style = sanitize_html_class($options->style);

        if (empty($options->apiKey))
        {
            $gallobj->html = '<div>Please enter your YouTube API key to embed galleries.</div>';
            return $gallobj;
        }

        $apiEndpoint = 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet,status&playlistId=' . $options->playlistId
                . '&maxResults=' . $options->pageSize
                . '&key=' . $options->apiKey;
        if ($options->pageToken != null)
        {
            $apiEndpoint .= '&pageToken=' . $options->pageToken;
        }
        $spdckey = '';
        if (self::$alloptions[self::$opt_pro] && strlen(trim(self::$alloptions[self::$opt_pro])) > 9 && self::$alloptions[self::$opt_spdc] == 1)
        {
            try
            {
                $optionsstr = json_encode($options);
                $spdckey = self::$spdcprefix . '_' . md5($apiEndpoint . $optionsstr);
                $spdcval = get_transient($spdckey);
                if (!empty($spdcval))
                {
                    //self::debuglog((microtime(true) - $time_start) . "\t" . $spdckey . "\t" . $spdcval . "\r\n");
                    $gallobj->html = $spdcval;
                    return $gallobj;
                }
            }
            catch (Exception $ex)
            {
                
            }
        }

        $code = '';
        $init_id = null;

        $apiResult = wp_remote_get($apiEndpoint, array('timeout' => self::$curltimeout));

        if (is_wp_error($apiResult))
        {
            $gallobj->html = '<div>Sorry, there was a YouTube API error: <em>' . htmlspecialchars(strip_tags($apiResult->get_error_message())) . '</em>' .
                    ' Please make sure you performed the <a href="https://www.youtube.com/watch?v=LpKDFT40V0U" target="_blank">steps in this video</a> to create and save a proper server API key.' .
                    '</div>';
            return $gallobj;
        }

        if (self::$alloptions[self::$opt_debugmode] == 1 && current_user_can('manage_options'))
        {
            $redactedEndpoint = preg_replace('@&key=[^&]+@i', '&key=PRIVATE', $apiEndpoint);
            $active_plugins = get_option('active_plugins');
            $gallobj->html = '<pre onclick="_EPADashboard_.selectText(this);" class="epyt-debug">CLICK this debug text to auto-select all. Then, COPY the selection.' . "\n\n" .
                    'THIS IS DEBUG MODE OUTPUT. UNCHECK THE OPTION IN THE SETTINGS PAGE ONCE YOU ARE DONE DEBUGGING TO PUT THINGS BACK TO NORMAL.' . "\n\n" . $redactedEndpoint . "\n\n" . print_r($apiResult, true) . "\n\nActive Plugins\n\n" . print_r($active_plugins, true) . '</pre>';
            return $gallobj;
        }

        $jsonResult = json_decode($apiResult['body']);

        if (isset($jsonResult->error))
        {
            if (isset($jsonResult->error->message))
            {
                $gallobj->html = '<div>Sorry, there was a YouTube API error: <em>' . htmlspecialchars(strip_tags($jsonResult->error->message)) . '</em>' .
                        ' Please make sure you performed the <a href="https://www.youtube.com/watch?v=LpKDFT40V0U" target="_blank">steps in this video</a> to create and save a proper server API key.' .
                        '</div>';
                return $gallobj;
            }
            $gallobj->html = '<div>Sorry, there may be an issue with your YouTube API key. Please make sure you performed the <a href="https://www.youtube.com/watch?v=LpKDFT40V0U" target="_blank">steps in this video</a> to create and save a proper server API key.</div>';
            return $gallobj;
        }



        $resultsPerPage = $options->pageSize; // $jsonResult->pageInfo->resultsPerPage;
        $totalResults = $jsonResult->pageInfo->totalResults;

        $nextPageToken = '';
        $prevPageToken = '';
        if (isset($jsonResult->nextPageToken))
        {
            $nextPageToken = $jsonResult->nextPageToken;
        }

        if (isset($jsonResult->prevPageToken))
        {
            $prevPageToken = $jsonResult->prevPageToken;
        }

        $cnt = 0;
        $colclass = '';
        if (in_array($options->style, array('grid', '')))
        {
            $colclass = ' epyt-cols-' . $options->columns . ' ';
        }

        $cropclass = '';
        if (!in_array($options->thumbcrop, array('box', '')))
        {
            $cropclass = ' epyt-thumb-' . $options->thumbcrop . ' ';
        }

        $code.= '<div class="epyt-gallery-allthumbs ' . $cropclass . $colclass . '">';




        if (isset($jsonResult->items) && $jsonResult->items != null && is_array($jsonResult->items))
        {

            foreach ($jsonResult->items as $item)
            {

                $thumb = new stdClass();

                $thumb->id = isset($item->snippet->resourceId->videoId) ? $item->snippet->resourceId->videoId : null;
                $thumb->id = $thumb->id ? $thumb->id : $item->id->videoId;
                $thumb->title = $options->showTitle ? $item->snippet->title : '';
                $thumb->privacyStatus = isset($item->status->privacyStatus) ? $item->status->privacyStatus : null;

                if ($cnt == 0 && $options->pageToken == null)
                {
                    $init_id = $thumb->id;
                }

                if (self::$alloptions[self::$opt_pro] && strlen(trim(self::$alloptions[self::$opt_pro])) > 0 && $options->style == 'listview')
                {
                    $thumb->dsc = $options->showDsc ? $item->snippet->description : '';
                }

                if ($thumb->privacyStatus == 'private')
                {
                    $thumb->img = plugins_url('/images/private.png', __FILE__);
                    $thumb->quality = 'medium';
                }
                else
                {
                    if (isset($item->snippet->thumbnails->high->url))
                    {
                        $thumb->img = $item->snippet->thumbnails->high->url;
                        $thumb->quality = 'high';
                    }
                    elseif (isset($item->snippet->thumbnails->default->url))
                    {
                        $thumb->img = $item->snippet->thumbnails->default->url;
                        $thumb->quality = 'default';
                    }
                    elseif (isset($item->snippet->thumbnails->medium->url))
                    {
                        $thumb->img = $item->snippet->thumbnails->medium->url;
                        $thumb->quality = 'medium';
                    }
                    else
                    {
                        $thumb->img = plugins_url('/images/deleted-video-thumb.png', __FILE__);
                        $thumb->quality = 'medium';
                    }
                }

                $code .= self::get_thumbnail_html($thumb, $options);
                $cnt++;

                if ($cnt % $options->columns === 0 && $options->style !== 'carousel')
                {
                    $code .= '<div class="epyt-gallery-rowbreak"></div>';
                }
            }
        }

        $code .= '<div class="epyt-gallery-clear"></div></div>';

        if ($options->style === 'carousel' && $options->showTitle)
        {
            $code .= '<div class="epyt-gallery-rowtitle"></div>';
        }


        $totalPages = ceil($totalResults / $resultsPerPage);
        $pagination = '<div class="epyt-pagination">';

        $txtprev = self::$alloptions[self::$opt_gallery_customarrows] ? self::$alloptions[self::$opt_gallery_customprev] : _('Prev');
        $pagination .= '<div tabindex="0" role="button" class="epyt-pagebutton epyt-prev ' . (empty($prevPageToken) ? ' hide ' : '') . '" data-playlistid="' . esc_attr($options->playlistId)
                . '" data-pagesize="' . intval($options->pageSize)
                . '" data-pagetoken="' . esc_attr($prevPageToken)
                . '" data-style="' . esc_attr($options->style)
                . '" data-columns="' . intval($options->columns)
                . '" data-showtitle="' . intval($options->showTitle)
                . '" data-showpaging="' . intval($options->showPaging)
                . '" data-autonext="' . intval($options->autonext)
                . '" data-thumbplay="' . intval($options->thumbplay)
                . ((self::$alloptions[self::$opt_pro] && strlen(trim(self::$alloptions[self::$opt_pro])) > 9 && $options->style == 'listview' && $options->showDsc) ? '" data-showdsc="' . intval($options->showDsc) : '')
                . ((self::$alloptions[self::$opt_pro] && strlen(trim(self::$alloptions[self::$opt_pro])) > 9 && !in_array($options->thumbcrop, array('box', ''))) ? '" data-thumbcrop="' . $options->thumbcrop : '')
                . '"><div class="arrow">&laquo;</div> <div>' . $txtprev . '</div></div>';


        $pagination .= '<div class="epyt-pagenumbers ' . ($totalPages > 1 ? '' : 'hide') . '">';
        $pagination .= '<div class="epyt-current">1</div><div class="epyt-pageseparator"> / </div><div class="epyt-totalpages">' . $totalPages . '</div>';
        $pagination .= '</div>';

        $txtnext = self::$alloptions[self::$opt_gallery_customarrows] ? self::$alloptions[self::$opt_gallery_customnext] : _('Next');
        $pagination .= '<div tabindex="0" role="button" class="epyt-pagebutton epyt-next' . (empty($nextPageToken) ? ' hide ' : '') . '" data-playlistid="' . esc_attr($options->playlistId)
                . '" data-pagesize="' . intval($options->pageSize)
                . '" data-pagetoken="' . esc_attr($nextPageToken)
                . '" data-style="' . esc_attr($options->style)
                . '" data-columns="' . intval($options->columns)
                . '" data-showtitle="' . intval($options->showTitle)
                . '" data-showpaging="' . intval($options->showPaging)
                . '" data-autonext="' . intval($options->autonext)
                . '" data-thumbplay="' . intval($options->thumbplay)
                . ((self::$alloptions[self::$opt_pro] && strlen(trim(self::$alloptions[self::$opt_pro])) > 0 && $options->style == 'listview' && $options->showDsc) ? '" data-showdsc="' . intval($options->showDsc) : '')
                . ((self::$alloptions[self::$opt_pro] && strlen(trim(self::$alloptions[self::$opt_pro])) > 9 && !in_array($options->thumbcrop, array('box', ''))) ? '" data-thumbcrop="' . $options->thumbcrop : '')
                . '"><div>' . $txtnext . '</div> <div class="arrow">&raquo;</div></div>';

        $pagination .= '<div class="epyt-loader"><img alt="loading" width="16" height="11" src="' . plugins_url('images/gallery-page-loader.gif', __FILE__) . '"></div>';

        $pagination .= '</div>';

        if ($options->showPaging == 0)
        {
            $pagination = '<div class="epyt-pagination"></div>';
        }
        $code = $pagination . $code . $pagination;

        if (self::$alloptions[self::$opt_pro] && strlen(trim(self::$alloptions[self::$opt_pro])) > 0 && self::$alloptions[self::$opt_spdc] == 1)
        {
            $daysecs = self::$alloptions[self::$opt_spdcexp] * 60 * 60;
            set_transient($spdckey, $code, $daysecs);
            $allk = get_option(self::$spdcall, array());
            $allk[] = $spdckey;
            update_option(self::$spdcall, $allk);

            //self::debuglog((microtime(true) - $time_start) . "\t" . $spdckey . "\t" . $code . "\r\n");
        }

        $gallobj->html = $code;
        $gallobj->init_id = $init_id;
        return $gallobj;
    }

    public static function get_thumbnail_html($thumb, $options)
    {
        $escId = esc_attr($thumb->id);
        $code = '';
        $styleclass = '';
        $rawstyle = '';
        $dschtml = '';
        if (self::$alloptions[self::$opt_pro] && strlen(trim(self::$alloptions[self::$opt_pro])) > 0)
        {
            if ($options->style == 'listview')
            {
                $styleclass = 'epyt-listview';
                $dschtml = isset($thumb->dsc) && !empty($thumb->dsc) ? '<div class="epyt-gallery-dsc">' . $thumb->dsc . '</div>' : '';
            }
            else if ($options->style == 'carousel')
            {
                $rawstyle = ' style="width: ' . (100.0 / floatval($options->pageSize)) . '%;" ';
            }
        }

        $code .= '<div tabindex="0" role="button" data-videoid="' . $escId . '" class="epyt-gallery-thumb ' . $styleclass . '" ' . $rawstyle . '>';
        $code .= '<div class="epyt-gallery-img-box"><div class="epyt-gallery-img" style="background-image: url(' . esc_attr($thumb->img) . ')">' .
                '<div class="epyt-gallery-playhover"><img alt="play" class="epyt-play-img" width="30" height="23" src="' . plugins_url('images/playhover.png', __FILE__) . '" /><div class="epyt-gallery-playcrutch"></div></div>' .
                '</div></div>';
        if ($options->style != 'carousel' && !empty($thumb->title))
        {
            $code .= '<div class="epyt-gallery-title">' . esc_html($thumb->title) . '</div>';
        }
        else
        {
            $code .= '<div class="epyt-gallery-notitle"><span>' . esc_html($thumb->title) . '</span></div>';
        }
        $code .= $dschtml . '</div>';


        return $code;
    }

    public static function my_embedplus_gallery_page()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        {
            //check_ajax_referer('embedplus-nonce', 'security');
            $options = (object) $_POST['options'];
            $options->apiKey = self::$alloptions[self::$opt_apikey];
            echo self::get_gallery_page($options)->html;
            die();
        }
    }

    public static function get_html($m, $iscontent)
    {
        //$time_start = microtime(true);

        $link = trim(str_replace(self::$badentities, self::$goodliterals, $m[0]));

        $link = preg_replace('/\s/', '', $link);
        $linkparamstemp = explode('?', $link);

        $linkparams = array();
        if (count($linkparamstemp) > 1)
        {
            $linkparams = self::keyvalue($linkparamstemp[1], true);
        }
        if (strpos($linkparamstemp[0], 'youtu.be') !== false && !isset($linkparams['v']))
        {
            $vtemp = explode('/', $linkparamstemp[0]);
            $linkparams['v'] = array_pop($vtemp);
        }

        if (isset($linkparams['channel']) && isset($linkparams['live']) && $linkparams['live'] == '1')
        {
            $live_error_msg = ' To embed live videos, please make sure you performed the <a href="https://www.youtube.com/watch?v=LpKDFT40V0U" target="_blank">steps in this video</a> to create and save a proper server API key.';
            if (isset(self::$alloptions[self::$opt_apikey]))
            {

                try
                {
                    $ytapilink_live = 'https://www.googleapis.com/youtube/v3/search?order=date&maxResults=1&type=video&eventType=live&safeSearch=none&videoEmbeddable=true&channelId=' . $linkparams['channel'] . '&part=snippet&key=' . self::$alloptions[self::$opt_apikey];
                    $apidata_live = wp_remote_get($ytapilink_live, array('timeout' => self::$curltimeout));
                    if (!is_wp_error($apidata_live))
                    {
                        $raw = wp_remote_retrieve_body($apidata_live);
                        if (!empty($raw))
                        {
                            $json = json_decode($raw, true);
                            if (!isset($json['error']) && is_array($json) && count($json['items']))
                            {
                                $linkparams['v'] = $json['items'][0]['id']['videoId'];
                            }
                            else if (isset($json['error']))
                            {
                                return $live_error_msg;
                            }
                        }
                    }
                }
                catch (Exception $ex)
                {
                    return $live_error_msg;
                }
            }
            else
            {
                return $live_error_msg;
            }

            if (!isset($linkparams['v']))
            {
                return apply_filters('the_content', trim(self::$alloptions[self::$opt_not_live_content]));
            }
        }

        $youtubebaseurl = 'youtube';
        $schemaorgoutput = '';
        $voloutput = '';
        $dynsrc = '';
        $dyntype = '';
        $acctitle = '';
        $videoseries = '';
        $disptype = '';
        $beginlb = '';
        $endlb = '';
        $disptypeif = '';

        $finalparams = $linkparams + self::$alloptions;

        $spdckey = '';
        if (self::$alloptions[self::$opt_pro] && strlen(trim(self::$alloptions[self::$opt_pro])) > 0 && self::$alloptions[self::$opt_spdc] == 1 && !isset($finalparams['live']))
        {
            try
            {
                $kparams = $finalparams;
                $kparams['iscontent'] = $iscontent;
                ksort($kparams);
                $jparams = json_encode($kparams);
                $spdckey = self::$spdcprefix . '_' . md5($jparams);
                $spdcval = get_transient($spdckey);
                if (!empty($spdcval))
                {
                    //self::debuglog((microtime(true) - $time_start) . "\t" . $spdckey . "\t" . $spdcval . "\r\n");
                    return $spdcval;
                }
            }
            catch (Exception $ex)
            {
                
            }
        }

        self::init_dimensions($link, $linkparams, $finalparams);

        if (self::$alloptions[self::$opt_nocookie] == 1)
        {
            $youtubebaseurl = 'youtube-nocookie';
        }

        if (self::$alloptions[self::$opt_defaultvol] == 1)
        {
            $voloutput = ' data-vol="' . self::$alloptions[self::$opt_vol] . '" ';
        }

        if (self::$alloptions[self::$opt_dohl] == 1)
        {
            $locale = get_locale();
            $finalparams[self::$opt_hl] = $locale;
        }
        else
        {
            unset($finalparams[self::$opt_hl]);
        }

        if (isset($finalparams[self::$opt_html5]) && $finalparams[self::$opt_html5] == 0)
        {
            unset($finalparams[self::$opt_html5]);
        }

        if (self::$alloptions[self::$opt_pro] && strlen(trim(self::$alloptions[self::$opt_pro])) > 10)
        {

            if (self::$alloptions[self::$opt_schemaorg] == 1 && isset($finalparams['v']))
            {
                $schemaorgoutput = self::getschemaorgoutput($finalparams['v']);
            }

            if (self::$alloptions[self::$opt_dynload] == 1
            )
            {
                $dynsrc = 'data-ep-';
                $dyntype = ' data-ep-a="' . self::$alloptions[self::$opt_dyntype] . '" ';
            }

            if (isset($linkparams[self::$opt_vol]) && is_numeric(trim($linkparams[self::$opt_vol])))
            {
                $voloutput = ' data-vol="' . $linkparams[self::$opt_vol] . '" ';
            }


            if (isset($finalparams['layout']) && strtolower($finalparams['layout']) == 'gallery' && isset($finalparams['list']) && isset($finalparams[self::$opt_gallery_disptype]) && $finalparams[self::$opt_gallery_disptype] === 'lb')
            {
                $finalparams[self::$opt_autoplay] = 0;
                $disptype = ' epyt-lb';
                $beginlb = '<div class="lity-hide">';
                $endlb = '</div>';
                $disptypeif = ' epyt-lbif';
            }
        }
        else
        {
            if (isset($finalparams[self::$opt_vol]))
            {
                unset($finalparams[self::$opt_vol]);
            }
            if (isset($finalparams[self::$opt_gallery_disptype]))
            {
                unset($finalparams[self::$opt_gallery_disptype]);
            }
        }

        $centercode = '';
        if ($finalparams[self::$opt_center] == 1)
        {
            $centercode = ' style="display: block; margin: 0px auto;" ';
        }

        if (self::$alloptions[self::$opt_acctitle] == 1)
        {
            try
            {
                //attr escape
                if (self::$oembeddata)
                {
                    $acctitle = self::$oembeddata->title;
                }
                else
                {

                    if (isset($linkparams['list']))
                    {
                        $odata = self::get_oembed('http://youtube.com/playlist?list=' . $linkparams['list'], 1920, 1280);
                        if (is_object($odata) && isset($odata->title))
                        {
                            $acctitle = $odata->title;
                        }
                    }
                    else
                    {
                        $odata = self::get_oembed('http://youtube.com/watch?v=' . $linkparams['v'], 1920, 1280);
                        if (is_object($odata) && isset($odata->title))
                        {
                            $acctitle = $odata->title;
                        }
                    }
                }

                if ($acctitle)
                {
                    $acctitle = ' title="' . esc_attr($acctitle) . '" ';
                }
            }
            catch (Exception $e)
            {
                
            }
        }

        // playlist cleanup
        $videoidoutput = isset($linkparams['v']) ? $linkparams['v'] : '';

        if ((self::$alloptions[self::$opt_playlistorder] == 1 || isset($finalparams['plindex'])) && isset($finalparams['list']))
        {
            try
            {
                $videoidoutput = '';
                if (isset($finalparams['plindex']))
                {
                    $finalparams['index'] = intval($finalparams['plindex']);
                }
            }
            catch (Exception $ex)
            {
                
            }
        }

        $galleryWrapper1 = '';
        $galleryWrapper2 = '';
        $galleryCode = '';
        $galleryid_ifm_data = '';
        if (
                isset($finalparams['layout']) && strtolower($finalparams['layout']) == 'gallery' && isset($finalparams['list'])
        )
        {
            $gallery_options = new stdClass();
            $gallery_options->playlistId = $finalparams['list'];
            $gallery_options->pageToken = null;
            $gallery_options->pageSize = $finalparams[self::$opt_gallery_pagesize];
            $gallery_options->columns = intval($finalparams[self::$opt_gallery_columns]);
            $gallery_options->showTitle = intval($finalparams[self::$opt_gallery_showtitle]);
            $gallery_options->showPaging = intval($finalparams[self::$opt_gallery_showpaging]);
            $gallery_options->autonext = intval($finalparams[self::$opt_gallery_autonext]);
            $gallery_options->thumbplay = intval($finalparams[self::$opt_gallery_thumbplay]);
            $gallery_options->showDsc = intval($finalparams[self::$opt_gallery_showdsc]);
            $gallery_options->style = $finalparams[self::$opt_gallery_style];
            $gallery_options->thumbcrop = $finalparams[self::$opt_gallery_thumbcrop];
            $gallery_options->apiKey = self::$alloptions[self::$opt_apikey];

            $galleryid = 'epyt_gallery_' . rand(10000, 99999);
            $galleryid_ifm_data = ' data-epytgalleryid="' . $galleryid . '" ';

            $subbutton = '';
            if (self::$alloptions[self::$opt_gallery_channelsub] == 1)
            {
                $subbutton = '<div class="epyt-gallery-subscribe"><a target="_blank" class="epyt-gallery-subbutton" href="' .
                        esc_attr(self::$alloptions[self::$opt_gallery_channelsublink]) . '?sub_confirmation=1"><img alt="subscribe" src="' . plugins_url('images/play-subscribe.png', __FILE__) . '" />' .
                        htmlspecialchars(self::$alloptions[self::$opt_gallery_channelsubtext], ENT_QUOTES) . '</a></div>';
            }


            $gallery_page_obj = self::get_gallery_page($gallery_options);

            $galleryWrapper1 = '<div class="epyt-gallery ' . $disptype . '" data-currpage="1" id="' . $galleryid . '">';
            $galleryWrapper2 = '</div>';
            $galleryCode = $subbutton . '<div class="epyt-gallery-list epyt-gallery-style-' . esc_attr($gallery_options->style) . '">' .
                    $gallery_page_obj->html .
                    '</div>';
            $videoidoutput = isset($gallery_page_obj->init_id) ? $gallery_page_obj->init_id : '';
        }


        $code1 = $beginlb . '<iframe ' . $dyntype . $centercode . ' id="_ytid_' . rand(10000, 99999) . '" width="' . self::$defaultwidth . '" height="' . self::$defaultheight .
                '" ' . $dynsrc . 'src="https://www.' . $youtubebaseurl . '.com/embed/' . $videoseries . $videoidoutput . '?';
        $code2 = '" class="__youtube_prefs__' . ($iscontent ? '' : ' __youtube_prefs_widget__') . $disptypeif .
                '"' . $voloutput . $acctitle . $galleryid_ifm_data . ' allowfullscreen data-no-lazy="1" data-skipgform_ajax_framebjll=""></iframe>' . $endlb . $schemaorgoutput;

        $origin = '';

        try
        {
            if (self::$alloptions[self::$opt_origin] == 1)
            {
                $url_parts = parse_url(site_url());
                $origin = 'origin=' . $url_parts['scheme'] . '://' . $url_parts['host'] . '&';
            }
        }
        catch (Exception $e)
        {
            $origin = '';
        }
        $finalsrc = 'enablejsapi=1&' . $origin;

        if (count($finalparams) > 1)
        {
            foreach ($finalparams as $key => $value)
            {
                if (in_array($key, self::$yt_options))
                {
                    if (!empty($galleryCode) && ($key == 'listType' || $key == 'list'))
                    {
                        
                    }
                    else
                    {
                        if (!(isset($finalparams['live']) && $key == 'loop'))
                        {
                            $finalsrc .= htmlspecialchars($key) . '=' . htmlspecialchars($value) . '&';
                            if ($key == 'loop' && $value == 1 && !isset($finalparams['list']))
                            {
                                $finalsrc .= 'playlist=' . $finalparams['v'] . '&';
                            }
                        }
                    }
                }
            }
        }

        $code = $galleryWrapper1 . $code1 . $finalsrc . $code2 . $galleryCode . $galleryWrapper2;
        //. '<!--' . $m[0] . '-->';
        self::$defaultheight = null;
        self::$defaultwidth = null;
        self::$oembeddata = null;


        if (self::$alloptions[self::$opt_pro] && strlen(trim(self::$alloptions[self::$opt_pro])) > 0 && self::$alloptions[self::$opt_spdc] == 1 && !isset($finalparams['live']))
        {
            $daysecs = self::$alloptions[self::$opt_spdcexp] * 60 * 60;
            set_transient($spdckey, $code, $daysecs);
            $allk = get_option(self::$spdcall, array());
            $allk[] = $spdckey;
            update_option(self::$spdcall, $allk);

            //self::debuglog((microtime(true) - $time_start) . "\t" . $spdckey . "\t" . $code . "\r\n");
        }
        return $code;
    }

    public static function debuglog($str)
    {
        $handle = fopen(__DIR__ . "\\debug.txt", "a+");
        fwrite($handle, $str);
        fclose($handle);
    }

    public static function spdcpurge()
    {
        $allk = get_option(self::$spdcall, array());
        if (is_array($allk))
        {
            foreach ($allk as $t)
            {
                $success = delete_transient($t);
            }
        }
        update_option(self::$spdcall, array());
    }

    public static function keyvalue($qry, $includev)
    {
        $ytvars = explode('&', $qry);
        $ytkvp = array();
        foreach ($ytvars as $k => $v)
        {
            $kvp = explode('=', $v);
            if (count($kvp) == 2 && ($includev || strtolower($kvp[0]) != 'v'))
            {
                $ytkvp[$kvp[0]] = $kvp[1];
            }
        }

        return $ytkvp;
    }

    public static function getschemaorgoutput($vidid)
    {
        $schemaorgcode = '';
        try
        {
            $ytapilink = 'https://www.googleapis.com/youtube/v3/videos?id=' . $vidid . '&part=contentDetails,snippet&key=' . self::$alloptions[self::$opt_apikey];


            $apidata = wp_remote_get($ytapilink, array('timeout' => self::$curltimeout));
            if (!is_wp_error($apidata))
            {
                $raw = wp_remote_retrieve_body($apidata);
                if (!empty($raw))
                {
                    $json = json_decode($raw, true);
                    if (is_array($json))
                    {
                        $_name = esc_attr(sanitize_text_field(str_replace("@", "&#64;", $json['items'][0]['snippet']['title'])));
                        $_description = esc_attr(sanitize_text_field(str_replace("@", "&#64;", $json['items'][0]['snippet']['description'])));
                        $_thumbnailUrl = esc_url("https://i.ytimg.com/vi/" . $vidid . "/0.jpg");
                        $_duration = $json['items'][0]['contentDetails']['duration']; // "T0H9M35S" "PT9M35S"
                        $_uploadDate = sanitize_text_field($json['items'][0]['snippet']['publishedAt']); // "2014-10-03T15:30:12.000Z"

                        $schemaorgcode = '<span itemprop="video" itemscope itemtype="http://schema.org/VideoObject">';
                        $schemaorgcode .= '<meta itemprop="embedUrl" content="https://www.youtube.com/embed/' . $vidid . '">';
                        $schemaorgcode .= '<meta itemprop="name" content="' . $_name . '">';
                        $schemaorgcode .= '<meta itemprop="description" content="' . $_description . '">';
                        $schemaorgcode .= '<meta itemprop="thumbnailUrl" content="' . $_thumbnailUrl . '">';
                        $schemaorgcode .= '<meta itemprop="duration" content="' . $_duration . '">';
                        $schemaorgcode .= '<meta itemprop="uploadDate" content="' . $_uploadDate . '">';
                        $schemaorgcode .= '</span>';
                    }
                }
            }
        }
        catch (Exception $ex)
        {
            
        }
        return $schemaorgcode;
    }

    public static function secondsToDuration($seconds)
    {
        $remaining = $seconds;
        $parts = array();
        $multipliers = array(
            'hours' => 3600,
            'minutes' => 60,
            'seconds' => 1
        );

        foreach ($multipliers as $type => $m)
        {
            $parts[$type] = (int) ($remaining / $m);
            $remaining -= ($parts[$type] * $m);
        }

        return $parts;
    }

    public static function formatDuration($parts)
    {
        $default = array(
            'hours' => 0,
            'minutes' => 0,
            'seconds' => 0
        );

        extract(array_merge($default, $parts));

        return "T{$hours}H{$minutes}M{$seconds}S";
    }

    public static function init_dimensions($url, $urlkvp, $finalparams)
    {
        // get default dimensions; try embed size in settings, then try theme's content width, then just 480px
        if (self::$defaultwidth == null)
        {
            global $content_width;
            if (empty($content_width))
            {
                $content_width = $GLOBALS['content_width'];
            }

            if (isset($urlkvp['width']) && is_numeric($urlkvp['width']))
            {
                self::$defaultwidth = $urlkvp['width'];
            }
            else if (self::$alloptions[self::$opt_defaultdims] == 1 && (isset(self::$alloptions[self::$opt_defaultwidth]) && is_numeric(self::$alloptions[self::$opt_defaultwidth])))
            {
                self::$defaultwidth = self::$alloptions[self::$opt_defaultwidth];
            }
            else if (self::$optembedwidth)
            {
                self::$defaultwidth = self::$optembedwidth;
            }
            else if ($content_width)
            {
                self::$defaultwidth = $content_width;
            }
            else
            {
                self::$defaultwidth = 480;
            }



            if (isset($urlkvp['height']) && is_numeric($urlkvp['height']))
            {
                self::$defaultheight = $urlkvp['height'];
            }
            else if (self::$alloptions[self::$opt_defaultdims] == 1 && (isset(self::$alloptions[self::$opt_defaultheight]) && is_numeric(self::$alloptions[self::$opt_defaultheight])))
            {
                self::$defaultheight = self::$alloptions[self::$opt_defaultheight];
            }
            else
            {
                self::$defaultheight = self::get_aspect_height($url, $urlkvp, $finalparams);
            }
        }
    }

    public static function get_oembed($url, $height, $width)
    {
        require_once( ABSPATH . WPINC . '/class-oembed.php' );
        $oembed = _wp_oembed_get_object();
        $args = array();
        $args['width'] = $width;
        $args['height'] = $height;
        $args['discover'] = false;
        self::$oembeddata = $oembed->fetch('https://www.youtube.com/oembed', $url, $args);
        return self::$oembeddata;
    }

    public static function get_aspect_height($url, $urlkvp, $finalparams)
    {

        // attempt to get aspect ratio correct height from oEmbed
        $aspectheight = round((self::$defaultwidth * 9) / 16, 0);


        if ($url)
        {
            $odata = self::get_oembed($url, self::$defaultwidth, self::$defaultwidth);

            if ($odata)
            {
                $aspectheight = $odata->height;
            }
        }

        if ($finalparams[self::$opt_controls] != 0 && $finalparams[self::$opt_autohide] != 1)
        {
            //add 28 for YouTube's own bar: DEPRECATED
            //$aspectheight += 28;
        }
        return $aspectheight;
    }

    public static function doftpostimg($postid, $post, $update)
    {
        if (current_user_can('edit_posts'))
        {
            if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !in_array($post->post_status, array('publish', 'pending', 'draft', 'future', 'private')))
            {
                return;
            }
            try
            {
                self::doftpostimgfor($post);
            }
            catch (Exception $ex)
            {
                // display error message
            }
        }
    }

    public static function doftpostimgfor($post)
    {
        $search_content = isset($post->post_content) ? $post->post_content : '';
        $search_content = substr(wp_strip_all_tags($search_content), 0, 5000);

        $search_content = apply_filters('youtube_embedplus_video_content', $search_content);

        $vid_match = null;
        if ($search_content && $post->ID && !has_post_thumbnail($post->ID) && preg_match(self::$justurlregex, $search_content, $vid_match)
        )
        {

            $first_vid_link = trim(str_replace(self::$badentities, self::$goodliterals, $vid_match[0]));

            $first_vid_link = preg_replace('/\s/', '', $first_vid_link);
            $linkparamstemp = explode('?', $first_vid_link);

            $linkparams = array();
            if (count($linkparamstemp) > 1)
            {
                $linkparams = self::keyvalue($linkparamstemp[1], true);
            }
            if (strpos($linkparamstemp[0], 'youtu.be') !== false && !isset($linkparams['v']))
            {
                $vtemp = explode('/', $linkparamstemp[0]);
                $linkparams['v'] = array_pop($vtemp);
            }

            $just_id = $linkparams['v'];

            if ($just_id == null && isset($linkparams['list']))
            {
                $apiEndpoint = 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet,status&playlistId=' . $linkparams['list']
                        . '&maxResults=10&key=' . self::$alloptions[self::$opt_apikey];
                try
                {
                    $apiResult = wp_remote_get($apiEndpoint, array('timeout' => self::$curltimeout));
                    if (!is_wp_error($apiResult))
                    {
                        $jsonResult = json_decode($apiResult['body']);
                        if (!isset($jsonResult->error) && isset($jsonResult->items) && $jsonResult->items != null && is_array($jsonResult->items))
                        {

                            $item = $jsonResult->items[0];
                            $just_id = isset($item->snippet->resourceId->videoId) ? $item->snippet->resourceId->videoId : null;
                            $just_id = $just_id ? $just_id : $item->id->videoId;
                        }
                    }
                }
                catch (Exception $ex)
                {
                    
                }
            }

            if ($just_id != null)
            {
                $ftimgurl = "https://img.youtube.com/vi/" . $just_id . "/maxresdefault.jpg";
                $ftimgid = self::media_sideload($ftimgurl, $post->ID, sanitize_title(preg_replace("/[^a-zA-Z0-9\s]/", "-", $post->post_title)));

                if (!ftimgid || is_wp_error($ftimgid))
                {
                    $ftimgurl = null;
                    $ftimgid = 0;
                    if ($just_id)
                    {
                        require_once( ABSPATH . WPINC . '/class-oembed.php' );
                        $oembed = _wp_oembed_get_object();
                        $args = array();
                        $args['width'] = 1920;
                        $args['height'] = 1080;
                        $args['discover'] = false;
                        $odata = $oembed->fetch('https://www.youtube.com/oembed', 'http://youtube.com/watch?v=' . $just_id, $args);

                        if ($odata)
                        {
                            $ftimgurl = $odata->thumbnail_url;
                        }
                    }

                    $ftimgid = $ftimgurl && !is_wp_error($ftimgurl) ? self::media_sideload($ftimgurl, $post->ID, sanitize_title(preg_replace("/[^a-zA-Z0-9\s]/", "-", $post->title))) : 0;

                    if (!$ftimgid || is_wp_error($ftimgid))
                    {
                        return;
                    }
                }
                set_post_thumbnail($post->ID, $ftimgid);
            }
        }
    }

    public static function media_sideload($url, $post_id, $filename = null)
    {
        if (!$url || !$post_id)
        {
            return new WP_Error('missing', __('Please provide a valid URL and post ID', ''));
        }

        $post_title = get_the_title($post_id);

        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $tmp = download_url($url);

        if (is_wp_error($tmp))
        {
            @unlink($file_array['tmp_name']);
            $file_array['tmp_name'] = '';
            return $tmp;
        }

        preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $url, $matches);
        $url_filename = basename($matches[0]);
        $url_type = wp_check_filetype($url_filename);

        if (!empty($filename))
        {
            $filename = sanitize_file_name($filename);
            $tmppath = pathinfo($tmp);
            $new = $tmppath['dirname'] . '/' . $filename . '.' . $tmppath['extension'];
            rename($tmp, $new);
            $tmp = $new;
        }

        $file_array['tmp_name'] = $tmp;
        if (!empty($filename))
        {
            $file_array['name'] = $filename . '.' . $url_type['ext'];
        }
        else
        {
            $file_array['name'] = $url_filename;
        }

        $post_data = array(
            'post_title' => $post_title,
            'post_parent' => $post_id,
        );

        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );

        $att_id = media_handle_sideload($file_array, $post_id, null, $post_data);

        if (is_wp_error($att_id))
        {
            @unlink($file_array['tmp_name']);
            return $att_id;
        }

        return $att_id;
    }

    public static function do_ogvideo()
    {
        global $wp_query;
        $the_content = $wp_query->post->post_content;
        $matches = Array();
        $ismatch = preg_match_all(self::$justurlregex, $the_content, $matches);

        if ($ismatch)
        {
            $match = $matches[0][0];

            $link = trim(preg_replace('/&amp;/i', '&', $match));
            $link = preg_replace('/\s/', '', $link);
            $link = trim(str_replace(self::$badentities, self::$goodliterals, $link));

            $linkparamstemp = explode('?', $link);

            $linkparams = array();
            if (count($linkparamstemp) > 1)
            {
                $linkparams = self::keyvalue($linkparamstemp[1], true);
            }
            if (strpos($linkparamstemp[0], 'youtu.be') !== false && !isset($linkparams['v']))
            {
                $vtemp = explode('/', $linkparamstemp[0]);
                $linkparams['v'] = array_pop($vtemp);
            }

            if (isset($linkparams['v']))
            {
                ?>
                <meta property="og:type" content="video">
                <meta property="og:video" content="https://www.youtube.com/v/<?php echo $linkparams['v']; ?>?autohide=1&amp;version=3">
                <meta property="og:video:type" content="application/x-shockwave-flash">
                <meta property="og:video:width" content="480">
                <meta property="og:video:height" content="360">
                <meta property="og:image" content="https://img.youtube.com/vi/<?php echo $linkparams['v']; ?>/0.jpg">
                <?php
            }
        }
    }

    public static function ytprefs_plugin_menu()
    {
        if (self::$alloptions[self::$opt_pro] && strlen(trim(self::$alloptions[self::$opt_pro])) > 0)
        {
            add_menu_page('YouTube Settings', 'YouTube PRO', 'manage_options', 'youtube-my-preferences', array(get_class(), 'ytprefs_show_options'), plugins_url('images/youtubeicon16.png', __FILE__), '10.000392854349');
            add_submenu_page('youtube-my-preferences', '', '', 'manage_options', 'youtube-my-preferences', array(get_class(), 'ytprefs_show_options'));
            add_submenu_page('youtube-my-preferences', 'YouTube Analytics Dashboard', '<img style="width: 16px; height: 16px; vertical-align: text-top;" src="' . plugins_url('images/epstats16.png', __FILE__) . '" />&nbsp;&nbsp;PRO Analytics', 'manage_options', 'youtube-ep-analytics-dashboard', array(get_class(), 'epstats_show_options'));
        }
        else
        {
            add_menu_page('YouTube Settings', 'YouTube Free', 'manage_options', 'youtube-my-preferences', array(get_class(), 'ytprefs_show_options'), plugins_url('images/youtubeicon16.png', __FILE__), '10.000392854349');
            add_submenu_page('youtube-my-preferences', '', '', 'manage_options', 'youtube-my-preferences', array(get_class(), 'ytprefs_show_options'));
            add_submenu_page('youtube-my-preferences', 'YouTube PRO', '<img style="width: 16px; height: 16px; vertical-align: text-top;" src="' . plugins_url('images/iconwizard.png', __FILE__) . '" />&nbsp;&nbsp;YouTube PRO', 'manage_options', 'youtube-ep-analytics-dashboard', array(get_class(), 'epstats_show_options'));
        }
        add_submenu_page(null, 'YouTube Posts', 'YouTube Posts', 'manage_options', 'youtube-ep-glance', array(get_class(), 'glance_page'));
    }

    public static function epstats_show_options()
    {

        if (!current_user_can('manage_options'))
        {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        if (self::$double_plugin)
        {
            self::double_plugin_warning();
        }


        // Now display the settings editing screen
        ?>
        <div class="wrap">
            <style type="text/css">
                .wrap {font-family: Arial;}
                .epicon { width: 20px; height: 20px; vertical-align: middle; padding-right: 5px;}
                .epindent {padding-left: 25px;}
                iframe.shadow {-webkit-box-shadow: 0px 0px 20px 0px #000000; box-shadow: 0px 0px 20px 0px #000000;}
                .bold {font-weight: bold;}
                .orange {color: #f85d00;}
            </style>
            <br>
            <?php
            $thishost = (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : "");
            $thiskey = self::$alloptions[self::$opt_pro];

            $dashurl = self::$epbase . "/dashboard/pro-easy-video-analytics.aspx?ref=protab&domain=" . $thishost . "&prokey=" . $thiskey;

            if (self::$alloptions[self::$opt_pro] && strlen(trim(self::$alloptions[self::$opt_pro])) > 0)
            {
                //// header
                echo "<h2>" . '<img alt="YouTube Plugin Icon" src="' . plugins_url('images/epstats16.png', __FILE__) . '" /> ' . __('YouTube Analytics Dashboard') . "</h2>";
                echo '<p><i>Logging you in below... (You can also <a class="button-primary" target="_blank" href="' . $dashurl . '">click here</a> to launch your dashboard in a new tab)</i></p>';
            }
            else
            {
                //// header
                echo "<h2>" . '<img alt="YouTube Plugin Wizard" style="vertical-align: text-bottom;" src="' . plugins_url('images/iconwizard.png', __FILE__) . '" /> ' . __('YouTube Plugin PRO') . "</h2><p class='bold orange'>This tab is here to provide direct access to analytics. Graphs and other data about your site will show below after you activate PRO.</p><br>";
            }
            ?>
            <iframe class="shadow" src="<?php echo $dashurl ?>" width="1060" height="3600" scrolling="auto"/>
        </div>
        <?php
    }

    public static function my_embedplus_pro_record()
    {
        $result = array();
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        {
            $tmppro = preg_replace('/[^A-Za-z0-9-]/i', '', $_REQUEST[self::$opt_pro]);
            $new_options = array();
            $new_options[self::$opt_pro] = $tmppro;
            $all = get_option(self::$opt_alloptions);
            $all = $new_options + $all;
            update_option(self::$opt_alloptions, $all);

            if (strlen($tmppro) > 0)
            {
                $result['type'] = 'success';
            }
            else
            {
                $result['type'] = 'error';
            }
            echo json_encode($result);
        }
        else
        {
            $result['type'] = 'error';
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
        die();
    }

    public static function my_embedplus_dashpre()
    {
        $result = array();
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        {
            try
            {
                $tmp = intval($_REQUEST[self::$opt_dashpre]);
                $new_options = array();
                $new_options[self::$opt_dashpre] = $tmp;
                $all = get_option(self::$opt_alloptions);
                $all = $new_options + $all;
                update_option(self::$opt_alloptions, $all);
            }
            catch (Exception $ex)
            {
                
            }
        }
        die();
    }

    public static function my_embedplus_clearspdc()
    {
        $result = array();
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        {
            try
            {
                self::spdcpurge();
                $result['type'] = 'success';
            }
            catch (Exception $ex)
            {
                $result['type'] = 'error';
            }
            echo json_encode($result);
        }
        else
        {
            $result['type'] = 'error';
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
        die();
    }

    public static function custom_admin_pointers_check()
    {
        //return false; // ooopointer shut all off;
        $admin_pointers = self::custom_admin_pointers();
        foreach ($admin_pointers as $pointer => $array)
        {
            if ($array['active'])
                return true;
        }
    }

    public static function glance_script()
    {
        add_thickbox();
        ?>
        <script type="text/javascript">
            function widen_ytprefs_glance() {
                setTimeout(function () {
                    jQuery("#TB_window").animate({marginLeft: '-' + parseInt((780 / 2), 10) + 'px', width: '780px'}, 300);
                    jQuery("#TB_window iframe").animate({width: '780px'}, 300);
                }, 15);
            }

            (function ($j)
            {
                $j(document).ready(function () {

                    $j.ajax({
                        type: "post",
                        dataType: "json",
                        timeout: 30000,
                        url: _EPYTA_.wpajaxurl,
                        data: {action: 'my_embedplus_glance_count'},
                        success: function (response) {
                            if (response.type == "success") {
                                $j(response.container).append(response.data);
                                $j(".ytprefs_glance_button").click(widen_ytprefs_glance);
                                $j(window).resize(widen_ytprefs_glance);
                                if (typeof ep_do_pointers == 'function')
                                {
                                    //ep_do_pointers($j);
                                }
                            }
                            else {
                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                        },
                        complete: function () {
                        }
                    });

                });

            })(jQuery);
        </script>
        <?php
    }

    public static function custom_admin_pointers_footer()
    {
        $admin_pointers = self::custom_admin_pointers();
        ?>
        <script type="text/javascript">
            /* <![CDATA[ */
            function ep_do_pointers($)
            {
        <?php
        foreach ($admin_pointers as $pointer => $array)
        {
            if ($array['active'])
            {
                ?>
                        $('<?php echo $array['anchor_id']; ?>').pointer({
                            content: '<?php echo $array['content']; ?>',
                            position: {
                                edge: '<?php echo $array['edge']; ?>',
                                align: '<?php echo $array['align']; ?>'
                            },
                            close: function () {
                                $.post(_EPYTA_.wpajaxurl, {
                                    pointer: '<?php echo $pointer; ?>',
                                    action: 'dismiss-wp-pointer'
                                });
                            }
                        }).pointer('open');
                <?php
            }
        }
        ?>
            }

            ep_do_pointers(jQuery); // switch off all pointers via js ooopointer
            /* ]]> */
        </script>
        <?php
    }

    public static function custom_admin_pointers()
    {
        $dismissed = explode(',', (string) get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', true));
        $version = str_replace('.', '_', self::$version); // replace all periods in version with an underscore
        $prefix = 'custom_admin_pointers' . $version . '_';

        $new_pointer_content = '<h3>' . __('New Update') . '</h3>'; // ooopointer

        $new_pointer_content .= '<p>'; // ooopointer
        $new_pointer_content .= "This update includes some bug fixes for galleries and the wizard for the Free and Pro versions.";
        $new_pointer_content .= '</p>';

        return array(
            $prefix . 'new_items' => array(
                'content' => $new_pointer_content,
                'anchor_id' => 'a.toplevel_page_youtube-my-preferences', //'#ytprefs_glance_button', 
                'edge' => 'top',
                'align' => 'left',
                'active' => (!in_array($prefix . 'new_items', $dismissed) )
            ),
        );
    }

    public static function postchecked($idx)
    {
        return isset($_POST[$idx]) && $_POST[$idx] == (true || 'on');
    }

    public static function output_scriptvars() // deprecated
    {
        self::$scriptsprinted++;
        if (self::$scriptsprinted == 1)
        {
            $blogwidth = self::get_blogwidth();
            $epprokey = self::$alloptions[self::$opt_pro];
            $myytdefaults = http_build_query(self::$alloptions);
            ?>
            <script type="text/javascript">
                var wpajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
                if (window.location.toString().indexOf('https://') == 0)
                {
                    wpajaxurl = wpajaxurl.replace("http://", "https://");
                }

                var epblogwidth = <?php echo $blogwidth; ?>;
                var epprokey = '<?php echo $epprokey; ?>';
                var epbasesite = '<?php echo self::$epbase; ?>';
                var epversion = '<?php echo self::$version; ?>';
                var myytdefaults = '<?php echo $myytdefaults; ?>';
                var eppluginadminurl = '<?php echo admin_url('admin.php?page=youtube-my-preferences'); ?>';
                //////////////////BEGIN SCRIPT HERE///////////////////////


                // Create IE + others compatible event handler
                var epeventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
                var epeventer = window[epeventMethod];
                var epmessageEvent = epeventMethod == "attachEvent" ? "onmessage" : "message";

                // Listen to message from child window
                epeventer(epmessageEvent, function (e)
                {
                    var embedcode = "";
                    try
                    {
                        if (e.data.indexOf("youtubeembedplus") === 0)
                        {
                            embedcode = e.data.split("|")[1];

                            if (embedcode.indexOf("[") !== 0)
                            {
                                embedcode = "<p>" + embedcode + "</p>";
                            }

                            if (window.tinyMCE !== null && window.tinyMCE.activeEditor !== null && !window.tinyMCE.activeEditor.isHidden())
                            {
                                if (typeof window.tinyMCE.execInstanceCommand !== 'undefined')
                                {
                                    window.tinyMCE.execInstanceCommand(
                                            window.tinyMCE.activeEditor.id,
                                            'mceInsertContent',
                                            false,
                                            embedcode);
                                }
                                else
                                {
                                    send_to_editor(embedcode);
                                }
                            }
                            else
                            {
                                embedcode = embedcode.replace('<p>', '\n').replace('</p>', '\n');
                                if (typeof QTags.insertContent === 'function')
                                {
                                    QTags.insertContent(embedcode);
                                }
                                else
                                {
                                    send_to_editor(embedcode);
                                }
                            }
                            tb_remove();


                        }
                        else if (e.data.indexOf("youtubeextprop") === 0)
                        {
                            var extprop = e.data.split("|")[1];
                            var extpropval = extprop === 'xdash1' ? 1 : 0;
                            if (extpropval != <?php echo intval(self::$alloptions[self::$opt_dashpre]); ?>)
                            {
                                jQuery.ajax({
                                    type: "post",
                                    dataType: "json",
                                    timeout: 30000,
                                    url: wpajaxurl,
                                    data: {action: 'my_embedplus_dashpre', <?php echo self::$opt_dashpre; ?>: extpropval},
                                    success: function (response) {
                                    },
                                    error: function (xhr, ajaxOptions, thrownError) {
                                    },
                                    complete: function () {
                                    }
                                });

                            }


                        }

                    }
                    catch (err)
                    {

                    }


                }, false);



            </script>
            <?php
        }
    }

    public static function ytprefs_show_options()
    {

        if (!current_user_can('manage_options'))
        {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        if (self::$double_plugin)
        {
            self::double_plugin_warning();
        }


        // variables for the field and option names 
        $ytprefs_submitted = 'ytprefs_submitted';

        // Read in existing option values from database

        $all = get_option(self::$opt_alloptions);

        // See if the user has posted us some information
        // If they did, this hidden field will be set to 'Y'
        if (isset($_POST[$ytprefs_submitted]) && $_POST[$ytprefs_submitted] == 'Y')
        {
            // Read their posted values

            $new_options = array();
            $new_options[self::$opt_center] = self::postchecked(self::$opt_center) ? 1 : 0;
            $new_options[self::$opt_glance] = self::postchecked(self::$opt_glance) ? 1 : 0;
            $new_options[self::$opt_autoplay] = self::postchecked(self::$opt_autoplay) ? 1 : 0;
            $new_options[self::$opt_debugmode] = self::postchecked(self::$opt_debugmode) ? 1 : 0;
            $new_options[self::$opt_admin_off_scripts] = self::postchecked(self::$opt_admin_off_scripts) ? 1 : 0;
            $new_options[self::$opt_old_script_method] = self::postchecked(self::$opt_old_script_method) ? 1 : 0;
            $new_options[self::$opt_cc_load_policy] = self::postchecked(self::$opt_cc_load_policy) ? 1 : 0;
            $new_options[self::$opt_iv_load_policy] = self::postchecked(self::$opt_iv_load_policy) ? 1 : 3;
            $new_options[self::$opt_loop] = self::postchecked(self::$opt_loop) ? 1 : 0;
            $new_options[self::$opt_modestbranding] = self::postchecked(self::$opt_modestbranding) ? 1 : 0;
            $new_options[self::$opt_rel] = self::postchecked(self::$opt_rel) ? 1 : 0;
            $new_options[self::$opt_showinfo] = self::postchecked(self::$opt_showinfo) ? 1 : 0;
            $new_options[self::$opt_playsinline] = self::postchecked(self::$opt_playsinline) ? 1 : 0;
            $new_options[self::$opt_origin] = self::postchecked(self::$opt_origin) ? 1 : 0;
            $new_options[self::$opt_controls] = self::postchecked(self::$opt_controls) ? 2 : 0;
            $new_options[self::$opt_autohide] = self::postchecked(self::$opt_autohide) ? 1 : 2;
            $new_options[self::$opt_html5] = self::postchecked(self::$opt_html5) ? 1 : 0;
            $new_options[self::$opt_theme] = self::postchecked(self::$opt_theme) ? 'dark' : 'light';
            $new_options[self::$opt_color] = self::postchecked(self::$opt_color) ? 'red' : 'white';
            $new_options[self::$opt_wmode] = self::postchecked(self::$opt_wmode) ? 'opaque' : 'transparent';
            $new_options[self::$opt_vq] = self::postchecked(self::$opt_vq) ? 'hd720' : '';
            $new_options[self::$opt_nocookie] = self::postchecked(self::$opt_nocookie) ? 1 : 0;
            $new_options[self::$opt_playlistorder] = self::postchecked(self::$opt_playlistorder) ? 1 : 0;
            $new_options[self::$opt_acctitle] = self::postchecked(self::$opt_acctitle) ? 1 : 0;
            $new_options[self::$opt_ogvideo] = self::postchecked(self::$opt_ogvideo) ? 1 : 0;
            $new_options[self::$opt_migrate] = self::postchecked(self::$opt_migrate) ? 1 : 0;
            $new_options[self::$opt_migrate_youtube] = self::postchecked(self::$opt_migrate_youtube) ? 1 : 0;
            $new_options[self::$opt_migrate_embedplusvideo] = self::postchecked(self::$opt_migrate_embedplusvideo) ? 1 : 0;
            //$new_options[self::$opt_ssl] = self::postchecked(self::$opt_ssl) ? 1 : 0;
            $new_options[self::$opt_oldspacing] = self::postchecked(self::$opt_oldspacing) ? 1 : 0;
            $new_options[self::$opt_responsive] = self::postchecked(self::$opt_responsive) ? 1 : 0;
            $new_options[self::$opt_widgetfit] = self::postchecked(self::$opt_widgetfit) ? 1 : 0;
            $new_options[self::$opt_evselector_light] = self::postchecked(self::$opt_evselector_light) ? 1 : 0;
            $new_options[self::$opt_stop_mobile_buffer] = self::postchecked(self::$opt_stop_mobile_buffer) ? 1 : 0;
            $new_options[self::$opt_schemaorg] = self::postchecked(self::$opt_schemaorg) ? 1 : 0;
            $new_options[self::$opt_ftpostimg] = self::postchecked(self::$opt_ftpostimg) ? 1 : 0;
            $new_options[self::$opt_spdc] = self::postchecked(self::$opt_spdc) ? 1 : 0;
            $new_options[self::$opt_spdcab] = self::postchecked(self::$opt_spdcab) ? 1 : 0;
            $new_options[self::$opt_dynload] = self::postchecked(self::$opt_dynload) ? 1 : 0;
            $new_options[self::$opt_defaultdims] = self::postchecked(self::$opt_defaultdims) ? 1 : 0;
            $new_options[self::$opt_defaultvol] = self::postchecked(self::$opt_defaultvol) ? 1 : 0;
            $new_options[self::$opt_dohl] = self::postchecked(self::$opt_dohl) ? 1 : 0;
            $new_options[self::$opt_gallery_showtitle] = self::postchecked(self::$opt_gallery_showtitle) ? 1 : 0;
            $new_options[self::$opt_gallery_showpaging] = self::postchecked(self::$opt_gallery_showpaging) ? 1 : 0;
            $new_options[self::$opt_gallery_autonext] = self::postchecked(self::$opt_gallery_autonext) ? 1 : 0;
            $new_options[self::$opt_gallery_thumbplay] = self::postchecked(self::$opt_gallery_thumbplay) ? 1 : 0;
            $new_options[self::$opt_gallery_channelsub] = self::postchecked(self::$opt_gallery_channelsub) ? 1 : 0;
            $new_options[self::$opt_gallery_customarrows] = self::postchecked(self::$opt_gallery_customarrows) ? 1 : 0;
            $new_options[self::$opt_gallery_showdsc] = self::postchecked(self::$opt_gallery_showdsc) ? 1 : 0;
            $new_options[self::$opt_gallery_collapse_grid] = self::postchecked(self::$opt_gallery_collapse_grid) ? 1 : 0;

            $_defaultwidth = '';
            try
            {
                $_defaultwidth = is_numeric(trim($_POST[self::$opt_defaultwidth])) ? intval(trim($_POST[self::$opt_defaultwidth])) : $_defaultwidth;
            }
            catch (Exception $ex)
            {
                
            }
            $new_options[self::$opt_defaultwidth] = $_defaultwidth;

            $_defaultheight = '';
            try
            {
                $_defaultheight = is_numeric(trim($_POST[self::$opt_defaultheight])) ? intval(trim($_POST[self::$opt_defaultheight])) : $_defaultheight;
            }
            catch (Exception $ex)
            {
                
            }
            $new_options[self::$opt_defaultheight] = $_defaultheight;

            $_responsive_all = 1;
            try
            {
                $_responsive_all = is_numeric(trim($_POST[self::$opt_responsive_all])) ? intval(trim($_POST[self::$opt_responsive_all])) : $_responsive_all;
            }
            catch (Exception $ex)
            {
                
            }
            $new_options[self::$opt_responsive_all] = $_responsive_all;

            $_vol = '';
            try
            {
                $_vol = is_numeric(trim($_POST[self::$opt_vol])) ? intval(trim($_POST[self::$opt_vol])) : $_vol;
            }
            catch (Exception $ex)
            {
                
            }
            $new_options[self::$opt_vol] = $_vol;

            $_gallery_pagesize = 12;
            try
            {
                $_gallery_pagesize = is_numeric(trim($_POST[self::$opt_gallery_pagesize])) ? intval(trim($_POST[self::$opt_gallery_pagesize])) : $_gallery_pagesize;
            }
            catch (Exception $ex)
            {
                
            }
            $new_options[self::$opt_gallery_pagesize] = $_gallery_pagesize;


            $_gallery_columns = 3;
            try
            {
                $_gallery_columns = is_numeric(trim($_POST[self::$opt_gallery_columns])) ? intval(trim($_POST[self::$opt_gallery_columns])) : $_gallery_columns;
            }
            catch (Exception $ex)
            {
                
            }
            $new_options[self::$opt_gallery_columns] = $_gallery_columns;


            $_gallery_collapse_grid_breaks = self::$dft_bpts;
            try
            {
                $_gallery_collapse_grid_breaks = is_array($_POST[self::$opt_gallery_collapse_grid_breaks]) ? $_POST[self::$opt_gallery_collapse_grid_breaks] : $_gallery_collapse_grid_breaks;
            }
            catch (Exception $ex)
            {
                
            }
            $new_options[self::$opt_gallery_collapse_grid_breaks] = $_gallery_collapse_grid_breaks;



            $_gallery_scrolloffset = 20;
            try
            {
                $_gallery_scrolloffset = is_numeric(trim($_POST[self::$opt_gallery_scrolloffset])) ? intval(trim($_POST[self::$opt_gallery_scrolloffset])) : $_gallery_scrolloffset;
            }
            catch (Exception $ex)
            {
                
            }
            $new_options[self::$opt_gallery_scrolloffset] = $_gallery_scrolloffset;

            $_gallery_style = 'grid';
            try
            {
                if (isset($_POST[self::$opt_gallery_style]))
                {
                    $_gallery_style = trim(str_replace(array(' ', "'", '"'), array('', '', ''), strip_tags($_POST[self::$opt_gallery_style])));
                }
            }
            catch (Exception $ex)
            {
                $_gallery_style = 'grid';
            }
            $new_options[self::$opt_gallery_style] = $_gallery_style;


            $_gallery_thumbcrop = 'box';
            try
            {
                if (isset($_POST[self::$opt_gallery_thumbcrop]))
                {
                    $_gallery_thumbcrop = trim(str_replace(array(' ', "'", '"'), array('', '', ''), strip_tags($_POST[self::$opt_gallery_thumbcrop])));
                }
            }
            catch (Exception $ex)
            {
                $_gallery_thumbcrop = 'box';
            }
            $new_options[self::$opt_gallery_thumbcrop] = $_gallery_thumbcrop;


            $_gallery_disptype = 'default';
            try
            {
                if (isset($_POST[self::$opt_gallery_disptype]))
                {
                    $_gallery_disptype = trim(str_replace(array(' ', "'", '"'), array('', '', ''), strip_tags($_POST[self::$opt_gallery_disptype])));
                }
            }
            catch (Exception $ex)
            {
                $_gallery_disptype = 'default';
            }
            $new_options[self::$opt_gallery_disptype] = $_gallery_disptype;


            $_gallery_channelsublink = '';
            try
            {
                $_gallery_channelsublink = trim(strip_tags($_POST[self::$opt_gallery_channelsublink]));
                $pieces = explode('?', $_gallery_channelsublink);
                $_gallery_channelsublink = trim($pieces[0]);
            }
            catch (Exception $ex)
            {
                $_gallery_channelsublink = '';
            }
            $new_options[self::$opt_gallery_channelsublink] = $_gallery_channelsublink;


            $_gallery_channelsubtext = '';
            try
            {
                $_gallery_channelsubtext = stripslashes(trim($_POST[self::$opt_gallery_channelsubtext]));
            }
            catch (Exception $ex)
            {
                $_gallery_channelsubtext = '';
            }
            $new_options[self::$opt_gallery_channelsubtext] = $_gallery_channelsubtext;


            $_gallery_custom_prev = 'Prev';
            try
            {
                $_gallery_custom_prev = trim(strip_tags($_POST[self::$opt_gallery_customprev]));
            }
            catch (Exception $ex)
            {
                $_gallery_custom_prev = 'Prev';
            }
            $new_options[self::$opt_gallery_customprev] = $_gallery_custom_prev;


            $_gallery_custom_next = 'Next';
            try
            {
                $_gallery_custom_next = trim(strip_tags($_POST[self::$opt_gallery_customnext]));
            }
            catch (Exception $ex)
            {
                $_gallery_custom_next = 'Next';
            }
            $new_options[self::$opt_gallery_customnext] = $_gallery_custom_next;

            $_not_live_content = '';
            try
            {
                $_not_live_content = wp_kses_post($_POST[self::$opt_not_live_content]);
            }
            catch (Exception $ex)
            {
                $_not_live_content = '';
            }
            $new_options[self::$opt_not_live_content] = $_not_live_content;



            $_apikey = '';
            try
            {
                $_apikey = trim(str_replace(array(' ', "'", '"'), array('', '', ''), strip_tags($_POST[self::$opt_apikey])));
            }
            catch (Exception $ex)
            {
                $_apikey = '';
            }
            $new_options[self::$opt_apikey] = $_apikey;


//            $_hl = '';
//            try
//            {
//                $temphl = strtolower(trim($_POST[self::$opt_hl]));
//                $_hl = preg_match('/^[a-z][a-z]$/i', $temphl) ? $temphl : '';
//            }
//            catch (Exception $ex)
//            {
//                
//            }
//            $new_options[self::$opt_hl] = $_hl;

            $_dyntype = '';
            try
            {
                if (isset($_POST[self::$opt_dyntype]))
                {
                    $tempdyntype = trim($_POST[self::$opt_dyntype]);
                    $_dyntype = preg_match('/^[a-zA-Z-]+$/i', $tempdyntype) ? $tempdyntype : '';
                }
            }
            catch (Exception $ex)
            {
                
            }
            $new_options[self::$opt_dyntype] = $_dyntype;

            $_spdcexp = 24;
            try
            {
                $_spdcexp = isset($_POST[self::$opt_spdcexp]) && is_numeric(trim($_POST[self::$opt_spdcexp])) ? intval(trim($_POST[self::$opt_spdcexp])) : $_spdcexp;
            }
            catch (Exception $ex)
            {
                
            }
            $new_options[self::$opt_spdcexp] = $_spdcexp;


            $all = $new_options + $all;

            update_option(self::$opt_alloptions, $all);

            try
            {
                self::spdcpurge();
                if ($all[self::$opt_spdc] == 1)
                {
                    wp_remote_get(site_url(), array('timeout' => self::$curltimeout));
                }
            }
            catch (Exception $ex)
            {
                
            }
            ?>
            <div class="updated"><p><strong><?php _e('Changes saved.'); ?></strong></p></div>
            <?php
        }


        // Now display the settings editing screen

        echo '<div class="wrap" style="max-width: 1000px;">';

        // header

        echo "<h2>" . '<img alt="YouTube Plugin Icon" src="' . plugins_url('images/youtubeicon16.png', __FILE__) . '" /> ' . __('YouTube Settings') . "</h2>";

        // settings form
        ?>

        <style type="text/css">
            .wrap {font-family: Arial; color: #000000;}
            #ytform p { line-height: 20px; margin-bottom: 11px; }
            #ytform ul li {margin-left: 30px; list-style: disc outside none;}
            .ytindent {padding: 0px 0px 0px 20px; font-size: 12px;}
            .ytindent ul, .ytindent p {font-size: 12px;}
            .shadow {-webkit-box-shadow: 0px 0px 20px 0px #000000; box-shadow: 0px 0px 20px 0px #000000;}
            .gopro {margin: 0px;}
            .gopro img {vertical-align: middle;
                        width: 19px;
                        height: 19px;
                        padding-bottom: 4px;}
            .gopro li {margin-bottom: 0px;}
            .orange {color: #f85d00;}
            .bold {font-weight: bold;}
            .grey{color: #888888;}
            #goprobox {border-radius: 15px; padding: 10px 15px 15px 15px; border: 3px solid #CCE5EC; position: relative;}
            #salenote {position: absolute; right: 10px; top: 10px; width: 75px; height: 30px;}
            #nonprosupport {border-radius: 15px; padding: 10px 15px 20px 15px;  border: 3px solid #ff6655;}
            .pronon {font-weight: bold; color: #f85d00;}
            ul.reglist li {margin: 0px 0px 0px 30px; list-style: disc outside none;}
            .procol {width: 475px; float: left;}
            .ytindent .procol ul {font-size: 11px;}
            .smallnote, .ytindent .smallnote {font-style: italic; font-size: 10px;}
            .italic {font-style: italic;}
            .ytindent h3 {font-size: 15px; line-height: 22px; margin: 5px 0px 10px 0px;}
            #wizleftlink {float: left; display: block; width: 240px; font-style: italic; text-align: center; text-decoration: none;}
            .button-primary {font-weight: bold; white-space: nowrap;}
            p.submit {margin: 10px 0 0 0; padding: 10px 0 5px 0;}
            .wp-core-ui p.submit .button-primary {font-size: 20px; height: 50px; padding: 0 20px 1px;
                                                  background: #2ea2cc; /* Old browsers */
                                                  background: -moz-linear-gradient(top,  #2ea2cc 0%, #007396 98%); /* FF3.6+ */
                                                  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#2ea2cc), color-stop(98%,#007396)); /* Chrome,Safari4+ */
                                                  background: -webkit-linear-gradient(top,  #2ea2cc 0%,#007396 98%); /* Chrome10+,Safari5.1+ */
                                                  background: -o-linear-gradient(top,  #2ea2cc 0%,#007396 98%); /* Opera 11.10+ */
                                                  background: -ms-linear-gradient(top,  #2ea2cc 0%,#007396 98%); /* IE10+ */
                                                  background: linear-gradient(to bottom,  #2ea2cc 0%,#007396 98%); /* W3C */
                                                  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2ea2cc', endColorstr='#007396',GradientType=0 ); /* IE6-9 */
            }
            p.submit em {display: inline-block; padding-left: 20px; vertical-align: middle; width: 240px; margin-top: -6px;}
            #opt_pro {box-shadow: 0px 0px 5px 0px #1870D5; width: 320px;vertical-align: top;}
            #goprobox h3 {font-size: 13px;}
            .chx {border-left: 5px solid rgba(100, 100, 100,.1); margin-bottom: 20px;}
            .chx p {margin: 0px 0px 5px 0px;}
            .cuz {background-image: linear-gradient(to bottom,#4983FF,#0C5597) !important; color: #ffffff;}
            .brightpro {background-image: linear-gradient(to bottom,#ff5500,#cc2200) !important; color: #ffffff;}
            #boxdefaultdims {font-weight: bold; padding: 0px 10px; <?php echo $all[self::$opt_defaultdims] ? '' : 'display: none;' ?>}
            #boxcustomarrows {font-weight: bold; padding: 0px 10px; <?php echo $all[self::$opt_gallery_customarrows] ? 'display: block;' : 'display: none;' ?>}
            #boxchannelsub {font-weight: bold; padding: 0px 10px; <?php echo $all[self::$opt_gallery_channelsub] ? 'display: block;' : 'display: none;' ?>}
            #box_collapse_grid {font-weight: bold; padding: 0px 10px; <?php echo isset($all[self::$opt_gallery_collapse_grid]) && $all[self::$opt_gallery_collapse_grid] ? 'display: block;' : 'display: none;' ?>}
            .textinput {border-width: 2px !important;}
            h3.sect {border-radius: 10px; background-color: #D9E9F7; padding: 5px 5px 5px 10px; position: relative; font-weight: bold;}
            h3.sect a {text-decoration: none; color: #E20000;}
            h3.sect a.button-primary {color: #ffffff;} 
            h4.sect {border-radius: 10px; background-color: #D9E9F7; padding: 5px 5px 5px 10px; position: relative; font-weight: bold;}

            .ytnav {margin-bottom: 15px;}
            .ytnav a {font-weight: bold; display: inline-block; padding: 5px 10px; margin: 0px 15px 0px 0px; border: 1px solid #cccccc; border-radius: 6px;
                      text-decoration: none; background-color: #ffffff;}
            .ytnav a:last-child {margin-right: 0;}
            .jumper {height: 25px;}
            .ssschema {float: right; width: 350px; height: auto; margin-right: 10px;}
            .ssfb {float: right; height: auto; margin-right: 10px; margin-left: 15px; margin-bottom: 10px;}
            .totop {position: absolute; right: 20px; top: 5px; color: #444444; font-size: 10px;}
            input[type=checkbox] {border: 1px solid #000000;}
            .chktitle {display: inline-block; padding: 1px 5px 1px 5px; border-radius: 3px; background-color: #ffffff; border: 1px solid #dddddd;}
            b, strong {font-weight: bold;}
            input.checkbox[disabled], input[type=radio][disabled] {border: 1px dashed #444444;}
            .pad10 {padding: 10px;}
            #boxdohl {font-weight: bold; padding: 0px 10px;  <?php echo $all[self::$opt_dohl] ? '' : 'display: none;' ?>}
            #boxdyn {font-weight: bold; padding: 0px 10px;  <?php echo $all[self::$opt_dynload] ? 'display: block;' : 'display: none;' ?>}
            #boxspdc {padding: 0px 10px;  border-left: 5px solid #eee;  <?php echo $all[self::$opt_spdc] ? '' : 'display: none;' ?>}
            #boxdefaultvol {font-weight: bold; padding: 0px 10px;  <?php echo $all[self::$opt_defaultvol] ? '' : 'display: none;' ?>}
            .vol-output {display: none; width: 30px; color: #008800;}
            .vol-range {background-color: #dddddd; border-radius: 3px; cursor: pointer;}
            input#vol {vertical-align: middle;}
            .vol-seeslider {display: none;}
            input#spdcexp {width: 70px;}
            .indent-option {margin-left: 25px;}
            #boxschemaorg { padding: 7px 0;  <?php echo $all[self::$opt_schemaorg] ? 'display: block;' : 'display: none;' ?>}
            #boxmigratelist { <?php echo $all[self::$opt_migrate] ? '' : 'display: none;' ?>}
            #boxresponsive_all { <?php echo $all[self::$opt_responsive] ? '' : 'display: none;' ?> padding-left: 25px; border-left: 5px solid rgba(100, 100, 100,.1); margin-left: 5px;}
            .apikey-msg {display: inline-block; vertical-align: top;}
            .apikey-video{margin-left: 3%; display: inline-block; width: 50%; position: relative; padding-top: 29%}
            .apikey-video iframe{display: block; width: 100%; height: 100%; position: absolute; top: 0; left: 0;}
            #boxnocookie {display: inline-block; border-radius: 3px; padding: 2px 4px 2px 4px; color: red; background-color: yellow; font-weight: bold; <?php echo $all[self::$opt_nocookie] ? '' : 'display: none;' ?>}
            .strike {text-decoration: line-through;}
            .upgchecks { padding: 20px; border-radius: 15px; border: 1px dotted #777777; background-color: #fcfcfc; }
            .clearboth {clear: both;}
            div.hr {clear: both; border-bottom: 1px dotted #A8BDD8; margin: 20px 0 20px 0;}
            .wp-pointer-buttons a.close {margin-top: 0 !important;}
            .pad20{padding: 20px 0 20px 0;}
            .ssgallery {float: right; width: 130px; height: auto; margin-left: 15px; border: 3px solid #ffffff;}
            .sssubscribe{display: block; width: 400px; height: auto;}
            .ssaltgallery {float: right; height: auto; margin-right: 10px; margin-left: 15px; margin-bottom: 10px; width: 350px;}
            .sspopupplayer {float: right; height: auto; margin-right: 10px; margin-left: 15px; margin-bottom: 10px; width: 350px;}
            .sswizardbutton {    max-width: 70%; height: auto;}
            .save-changes-follow {position: fixed; z-index: 10000; bottom: 0; right: 0; background-color: #ffffff; padding: 0 20px; border-top-left-radius: 20px; border: 2px solid #aaaaaa; border-right-width: 0; border-bottom-width: 0;
                                  -webkit-box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.75);
                                  -moz-box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.75);
                                  box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.75); }

        </style>

        <div class="ytindent">
            <br>
            <div class="ytnav">
                <a href="#jumppro">PRO Key</a>
                <a href="#jumpapikey">API Key</a>
                <a href="#jumpdefaults">Defaults</a>
                <a href="#jumpwiz">Visual Wizard</a>
                <a href="#jumpcompat">Compatibility</a>
                <a href="#jumpgallery">Galleries</a>
                <a href="#jumpprosettings">PRO Settings</a>
                <a href="#jumphowto">Embed Manually</a>
                <a href="#jumpsupport">Support</a>
            </div>

            <div class="jumper" id="jumppro"></div>
            <div id="goprobox">
                <?php
                if (isset($all[self::$opt_pro]) && strlen(trim($all[self::$opt_pro])) > 0)
                {
                    echo "<h3 class=sect>" . __('Thank you for going PRO.');
                    echo ' &nbsp;<input type="submit" name="showkey" class="button-primary" style="vertical-align: 15%;" id="showprokey" value="View my PRO key" />';
                    echo "</h3>";
                    ?>
                    <?php
                }
                else
                {
                    ?>
                    <h3 class="sect">
                        <a href="<?php echo self::$epbase ?>/dashboard/pro-easy-video-analytics.aspx" class="button-primary" target="_blank">Want to go PRO? (Low Prices) &raquo;</a> &nbsp; 
                        PRO users help keep new features coming and our coffee cups filled. Go PRO and get these perks in return:
                    </h3>
                    <div class="procol">
                        <ul class="gopro">
                            <li>
                                <img src="<?php echo plugins_url('images/iconcache.png', __FILE__) ?>">
                                Faster Page Loads (Caching)
                            </li>
                            <li>
                                <img src="<?php echo plugins_url('images/iconwizard.png', __FILE__) ?>">
                                Full Visual Embedding Wizard (Easily customize embeds without memorizing codes)
                            </li>
                            <li>
                                <img src="<?php echo plugins_url('images/icongallery.png', __FILE__) ?>">
                                Alternate Gallery Styling (popup/lightbox player, slider and list layouts, and more)
                            </li>       
                            <li>
                                <img src="<?php echo plugins_url('images/iconfx.png', __FILE__) ?>">
                                Add eye-catching special effects as your videos load
                            </li>
                            <li>
                                <img src="<?php echo plugins_url('images/deletechecker.png', __FILE__) ?>">
                                Deleted Video Checker (alerts you if YouTube deletes videos you embedded)
                            </li>
                            <li>
                                <img src="<?php echo plugins_url('images/globe.png', __FILE__) ?>">
                                Alerts when visitors from different countries are blocked from viewing your embeds
                            </li>                 
                            <li>
                                <img src="<?php echo plugins_url('images/mobilecompat.png', __FILE__) ?>">
                                Check if your embeds have restrictions that can block mobile viewing
                            </li>       

                        </ul>
                    </div>
                    <div class="procol" style="max-width: 465px;">
                        <ul class="gopro">
                            <li>
                                <img src="<?php echo plugins_url('images/videothumbs.png', __FILE__) ?>">
                                Featured thumbnail images (just click 'Update')  
                            </li>       
                            <li>
                                <img src="<?php echo plugins_url('images/prioritysupport.png', __FILE__) ?>">
                                Priority support (Puts your request in front)
                            </li>
                            <li>
                                <img src="<?php echo plugins_url('images/bulletgraph45.png', __FILE__) ?>">
                                User-friendly video analytics dashboard
                            </li>

                            <li id="fbstuff">
                                <img src="<?php echo plugins_url('images/iconfb.png', __FILE__) ?>">
                                Automatic Open Graph tagging for Facebook
                            </li>
                            <!--                            <li>
                                                            <img src="<?php echo plugins_url('images/iconythealth.png', __FILE__) ?>">
                                                            Instant YouTube embed diagnostic reports
                                                        </li>                          -->
                            <li>
                                <img src="<?php echo plugins_url('images/vseo.png', __FILE__) ?>">
                                Automatic tagging for video SEO (will even work for your old embeds)
                            </li>
                            <li>
                                <img src="<?php echo plugins_url('images/iconvolume.png', __FILE__) ?>">
                                Fine-Grained Volume Initialization – Individual video volume settings in the wizard
                            </li>       

                            <li>
                                <img src="<?php echo plugins_url('images/infinity.png', __FILE__) ?>">
                                Unlimited PRO upgrades and downloads
                            </li>
                            <!--                            <li>
                                                            <img src="<?php echo plugins_url('images/questionsale.png', __FILE__) ?>">
                                                            What else? You tell us!                                
                                                        </li>                           -->
                        </ul>
                    </div>
                    <div style="clear: both;"></div>
                    <br>
                    <h3 class="bold">Enter and save your PRO key (emailed to you):</h3>
                <?php } ?>
                <form name="form2" method="post" action="" id="epform2" class="submitpro" <?php
                if ($all[self::$opt_pro] && strlen(trim($all[self::$opt_pro])) > 0)
                {
                    echo 'style="display: none;"';
                }
                ?>>

                    <input name="<?php echo self::$opt_pro; ?>" id="opt_pro" value="<?php echo $all[self::$opt_pro]; ?>" type="text">
                    <input type="submit" name="Submit" class="button-primary" id="prokeysubmit" value="<?php _e('Save Key') ?>" />
                    <?php
                    if (!($all[self::$opt_pro] && strlen(trim($all[self::$opt_pro])) > 0))
                    {
                        ?>                    
                        &nbsp; &nbsp; &nbsp; <span style="font-size: 25px; color: #cccccc;">|</span> &nbsp; &nbsp; &nbsp; <a href="<?php echo self::$epbase ?>/dashboard/pro-easy-video-analytics.aspx" class="button-primary brightpro" target="_blank">Click here to go PRO &raquo;</a>
                        <?php
                    }
                    ?>
                    <br>
                    <span style="display: none;" id="prokeyloading" class="orange bold">Verifying...</span>
                    <span  class="orange bold" style="display: none;" id="prokeysuccess">Success! Please refresh this page.</span>
                    <span class="orange bold" style="display: none;" id="prokeyfailed">Sorry, that seems to be an invalid key, or it has been used already. If you're behind a firewall, you may need to try activating on another network.</span>
                    <span class="orange bold" style="display: none;" id="prokeycancel">Your request is being processed. Response code: CR1.</span>

                </form>
            </div>



            <form name="form1" method="post" action="" id="ytform">
                <input type="hidden" name="<?php echo $ytprefs_submitted; ?>" value="Y">
                <div class="jumper" id="jumpapikey"></div>
                <h3 class="sect">
                    YouTube API Key <a href="#top" class="totop">&#9650; top</a>
                </h3>
                <p>
                    Some features (such as galleries, and some wizard features) now require you to create a free YouTube API key from Google. 
                </p>
                <p>
                    <b class="chktitle">YouTube API Key:</b> 
                    <input type="text" name="<?php echo self::$opt_apikey; ?>" id="<?php echo self::$opt_apikey; ?>" value="<?php echo trim($all[self::$opt_apikey]); ?>" class="textinput" style="width: 250px;">
                    <a href="https://www.youtube.com/watch?v=LpKDFT40V0U" target="_blank">Click this link &raquo;</a> and follow the video to get your API key. Don't worry, it's an easy process.
                </p>


                <div class="jumper" id="jumpdefaults"></div>
                <h3 class="sect">
                    <?php _e("Default YouTube Options") ?> <a href="#top" class="totop">&#9650; top</a>
                </h3>
                <p>
                    <?php _e("One of the benefits of using this plugin is that you can set site-wide default options for all your videos (click \"Save Changes\" when finished). However, you can also override them (and more) on a per-video basis. Directions on how to do that are in the next section.") ?>
                </p>

                <div class="ytindent chx">
                    <p>
                        <input name="<?php echo self::$opt_glance; ?>" id="<?php echo self::$opt_glance; ?>" <?php checked($all[self::$opt_glance], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_glance; ?>"><?php _e('<b class="chktitle">At a glance:</b> Show "At a Glance" Embed Links on the dashboard homepage.') ?></label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_center; ?>" id="<?php echo self::$opt_center; ?>" <?php checked($all[self::$opt_center], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_center; ?>"><?php _e('<b class="chktitle">Centering:</b> Automatically center all your videos (not necessary if all your videos span the whole width of your blog).') ?></label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_autoplay; ?>" id="<?php echo self::$opt_autoplay; ?>" <?php checked($all[self::$opt_autoplay], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_autoplay; ?>"><?php _e('<b class="chktitle">Autoplay:</b>  Automatically start playing your videos.') ?></label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_iv_load_policy; ?>" id="<?php echo self::$opt_iv_load_policy; ?>" <?php checked($all[self::$opt_iv_load_policy], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_iv_load_policy; ?>"><?php _e('<b class="chktitle">Annotations:</b> Show annotations by default.') ?></label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_loop; ?>" id="<?php echo self::$opt_loop; ?>" <?php checked($all[self::$opt_loop], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_loop; ?>"><?php _e('<b class="chktitle">Looping:</b> Loop all your videos.') ?></label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_modestbranding; ?>" id="<?php echo self::$opt_modestbranding; ?>" <?php checked($all[self::$opt_modestbranding], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_modestbranding; ?>"><?php _e('<b class="chktitle">Modest Branding:</b> No YouTube logo will be shown on the control bar.  Instead, the logo will only show as a watermark when the video is paused/stopped.') ?></label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_rel; ?>" id="<?php echo self::$opt_rel; ?>" <?php checked($all[self::$opt_rel], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_rel; ?>"><?php _e('<b class="chktitle">Related Videos:</b> Show related and recommended videos during pause and at the end of playback.') ?></label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_showinfo; ?>" id="<?php echo self::$opt_showinfo; ?>" <?php checked($all[self::$opt_showinfo], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_showinfo; ?>"><?php _e('<b class="chktitle">Show Title:</b> Show the video title and other info.') ?></label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_acctitle; ?>" id="<?php echo self::$opt_acctitle; ?>" <?php checked($all[self::$opt_acctitle], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_acctitle; ?>"><b class="chktitle">Accessible Title Attributes: </b> Improve accessibility by using title attributes for screen reader support. It should help your site pass functional accessibility evaluations (FAE). </label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_theme; ?>" id="<?php echo self::$opt_theme; ?>" <?php checked($all[self::$opt_theme], 'dark'); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_theme; ?>"><?php _e('<b class="chktitle strike">Dark Theme:</b> Use the dark theme (uncheck to use light theme). <b>Note: YouTube has deprecated this option and will always use the dark theme.</b>') ?></label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_color; ?>" id="<?php echo self::$opt_color; ?>" <?php checked($all[self::$opt_color], 'red'); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_color; ?>"><?php _e('<b class="chktitle">Red Progress Bar:</b> Use the red progress bar (uncheck to use a white progress bar). Note: Using white will disable the modestbranding option.') ?></label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_vq; ?>" id="<?php echo self::$opt_vq; ?>" <?php checked($all[self::$opt_vq], 'hd720'); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_vq; ?>"><?php _e('<b class="chktitle strike">HD Quality:</b> Force HD quality when available. <b>NOTE: YouTube has deprecated this unofficially supported option.</b>') ?> </label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_wmode; ?>" id="<?php echo self::$opt_wmode; ?>" <?php checked($all[self::$opt_wmode], 'opaque'); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_wmode; ?>"><?php _e('<b class="chktitle">Wmode:</b> Use "opaque" wmode (uncheck to use "transparent"). Opaque may have higher performance.') ?></label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_defaultdims; ?>" id="<?php echo self::$opt_defaultdims; ?>" <?php checked($all[self::$opt_defaultdims], 1); ?> type="checkbox" class="checkbox">                        
                        <span id="boxdefaultdims">
                            Width: <input type="text" name="<?php echo self::$opt_defaultwidth; ?>" id="<?php echo self::$opt_defaultwidth; ?>" value="<?php echo trim($all[self::$opt_defaultwidth]); ?>" class="textinput" style="width: 50px;"> &nbsp;
                            Height: <input type="text" name="<?php echo self::$opt_defaultheight; ?>" id="<?php echo self::$opt_defaultheight; ?>" value="<?php echo trim($all[self::$opt_defaultheight]); ?>" class="textinput" style="width: 50px;">
                        </span>

                        <label for="<?php echo self::$opt_defaultdims; ?>"><?php _e('<b class="chktitle">Default Dimensions:</b> Make your videos have a default size. (NOTE: Checking the responsive option will override this size setting) ') ?></label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_responsive; ?>" id="<?php echo self::$opt_responsive; ?>" <?php checked($all[self::$opt_responsive], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_responsive; ?>"><?php _e('<b class="chktitle">Responsive Video Sizing:</b> Make your videos responsive so that they dynamically fit in all screen sizes (smart phone, PC and tablet). NOTE: While this is checked, any custom hardcoded widths and heights you may have set will dynamically change too. <b>Do not check this if your theme already handles responsive video sizing.</b>') ?></label>
                    <div id="boxresponsive_all">
                        <input type="radio" name="<?php echo self::$opt_responsive_all; ?>" id="<?php echo self::$opt_responsive_all; ?>1" value="1" <?php checked($all[self::$opt_responsive_all], 1); ?> >
                        <label for="<?php echo self::$opt_responsive_all; ?>1">Responsive for all YouTube videos</label> &nbsp;&nbsp;
                        <input type="radio" name="<?php echo self::$opt_responsive_all; ?>" id="<?php echo self::$opt_responsive_all; ?>0" value="0" <?php checked($all[self::$opt_responsive_all], 0); ?> >
                        <label for="<?php echo self::$opt_responsive_all; ?>0">Responsive for only videos embedded via this plugin</label>
                    </div>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_widgetfit; ?>" id="<?php echo self::$opt_widgetfit; ?>" <?php checked($all[self::$opt_widgetfit], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_widgetfit; ?>"><?php _e('<b class="chktitle">Autofit Widget Videos:</b> Make each video that you embed in a widget area automatically fit the width of its container.') ?></label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_playsinline; ?>" id="<?php echo self::$opt_playsinline; ?>" <?php checked($all[self::$opt_playsinline], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_playsinline; ?>">
                            <b class="chktitle">iOS Playback:</b> Check this to allow your embeds to play inline within your page when viewed on iOS (iPhone and iPad) browsers. Uncheck it to have iOS launch your embeds in fullscreen instead.
                            <em>Disclaimer: YouTube/Google has issues with this iOS related parameter, but we are providing it here in the event that they support it consistently.</em>
                        </label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_origin; ?>" id="<?php echo self::$opt_origin; ?>" <?php checked($all[self::$opt_origin], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_origin; ?>"><b class="chktitle">Extra Player Security: </b>
                            Add site origin information with each embed code as an extra security measure. In YouTube's/Google's own words, checking this option "protects against malicious third-party JavaScript being injected into your page and hijacking control of your YouTube player." We especially recommend checking it as it adds higher security than the built-in YouTube embedding method that comes with the current version of WordPress (i.e. oembed).
                        </label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_nocookie; ?>" id="<?php echo self::$opt_nocookie; ?>" <?php checked($all[self::$opt_nocookie], 1); ?> type="checkbox" class="checkbox">
                        <span id="boxnocookie">
                            Uncheck this option if you are planning to embed galleries and playlists on your site. Furthermore, videos on mobile devices may have problems if you leave this checked.
                        </span>
                        <label for="<?php echo self::$opt_nocookie; ?>">
                            <b class="chktitle">No Cookies:</b> Prevent YouTube from leaving tracking cookies on your visitors browsers unless they actual play the videos. This is coded to apply this behavior on links in your past post as well. <b>NOTE: Research shows that YouTube's support of Do Not Track can be error-prone. </b>
                        </label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_controls; ?>" id="<?php echo self::$opt_controls; ?>" <?php checked($all[self::$opt_controls], 2); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_controls; ?>"><b class="chktitle">Show Controls:</b> Show the player's control bar. Unchecking this option creates a cleaner look but limits what your viewers can control (play position, volume, etc.).</label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_autohide; ?>" id="<?php echo self::$opt_autohide; ?>" <?php checked($all[self::$opt_autohide], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_autohide; ?>"><b class="chktitle strike">Autohide Controls:</b> Slide away the control bar after the video starts playing. It will automatically slide back in again if you mouse over the video. If you unchecked "Show Controls" above, then what you select for Autohide does not matter since there are no controls to even hide.
                            <strong>Note: YouTube has deprecated this option, and will always autohide the controls.</strong>
                        </label>
                    </p>
            <!--                    <p>
                        <input name="<?php echo self::$opt_ssl; ?>" id="<?php echo self::$opt_ssl; ?>" <?php checked($all[self::$opt_ssl], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_ssl; ?>">
                            <b class="chktitle">HTTPS/SSL Player:</b> Do you have a website that uses HTTPS? Check this to use the secure YouTube player for all of your embeds.
                            This will go back and also secure your past embeds as they are loaded on their pages. Most web browsers will warn users when they access web pages via HTTPS that contain embedded content loaded via HTTP. If your main site is currently accessed via HTTPS, using HTTPS URLs for your YouTube embeds will prevent your users from running into that warning. If you're not currently supporting HTTPS/SSL, <a href="http://embedplus.com/convert-old-youtube-embeds-to-https-ssl.aspx" target="_blank">here's some motivation from Google &raquo;</a>
                        </label>
                    </p>-->
                    <p>
                        <input name="<?php echo self::$opt_defaultvol; ?>" id="<?php echo self::$opt_defaultvol; ?>" <?php checked($all[self::$opt_defaultvol], 1); ?> type="checkbox" class="checkbox">                        
                        <label for="<?php echo self::$opt_defaultvol; ?>">
                            <b class="chktitle">Volume Initialization: </b>
                            Set an initial volume level for all of your embedded videos.  Check this and you'll see a <span class="vol-seeslider">slider</span> <span class="vol-seetextbox">textbox</span> for setting the start volume to a value between 0 (mute) and 100 (max) percent.  Leaving it unchecked means you want the visitor's default behavior.  This feature is experimental and is less predictable on a page with more than one embed. Read more about why you might want to <a href="<?php echo self::$epbase ?>/mute-volume-youtube-wordpress.aspx" target="_blank">initialize YouTube embed volume here &raquo;</a>
                        </label>
                        <span id="boxdefaultvol">
                            Volume: <span class="vol-output"></span> <input min="0" max="100" step="1" type="text" name="<?php echo self::$opt_vol; ?>" id="<?php echo self::$opt_vol; ?>" value="<?php echo trim($all[self::$opt_vol]); ?>" >
                        </span>
                    </p>

                    <p>
                        <input name="<?php echo self::$opt_cc_load_policy; ?>" id="<?php echo self::$opt_cc_load_policy; ?>" <?php checked($all[self::$opt_cc_load_policy], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_cc_load_policy; ?>"><?php _e('<b class="chktitle">Closed Captions:</b> Turn on closed captions by default.') ?></label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_dohl; ?>" id="<?php echo self::$opt_dohl; ?>" <?php checked($all[self::$opt_dohl], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_dohl; ?>"><b class="chktitle">Player Localization / Internationalization: </b>
                            Automatically detect your site's default language (using get_locale) and set your YouTube embeds interface language so that it matches. Specifically, this will set the player's tooltips and caption track if your language is natively supported by YouTube. We suggest checking this if English is not your site's default language.  <a href="<?php echo self::$epbase ?>/youtube-iso-639-1-language-codes.aspx" target="_blank">See here for more details &raquo;</a></label>
                    </p>                    
                    <p>
                        <input name="<?php echo self::$opt_html5; ?>" id="<?php echo self::$opt_html5; ?>" <?php checked($all[self::$opt_html5], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_html5; ?>">
                            <b class="chktitle strike">HTML5 First:</b> 
                            As of January 2015, YouTube began serving the HTML5 player by default; therefore, this plugin no longer needs a special HTML5 setting.  This option is simply kept here as a notice.
                        </label>
                    </p>

                    <p>
                        <input name="<?php echo self::$opt_playlistorder; ?>" id="<?php echo self::$opt_playlistorder; ?>" <?php checked($all[self::$opt_playlistorder], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_playlistorder; ?>">
                            <b class="chktitle">Playlist Ordering:</b> 
                            Check this option if you want your playlists to begin with the latest added video by default. (Unchecking this will force playlists to always start with your selected specific video, even if you add videos to the playlist later).
                            Note that this is not for setting the thumbnail list order of galleries,  just the standard playlist player that YouTube provides.
                        </label>
                    </p>
                    <p>
                        <label for="<?php echo self::$opt_not_live_content; ?>">
                            <b class="chktitle">Default "Not Live" Content:</b> <sup class="orange">NEW</sup>
                            Below, enter what you would like to appear while your channel is not currently streaming live.
                        </label>
                        <?php
                        wp_editor(
                                wp_kses_post($all[self::$opt_not_live_content]), self::$opt_not_live_content, array('textarea_rows' => 5)
                        );
                        ?> 


                    </p>


                </div>

                <div class="jumper" id="jumpwiz"></div>
                <h3 class="sect">Visual YouTube Wizard <a href="#top" class="totop">&#9650; top</a></h3>

                <p>
                    While you're writing your post or page, you have the ability to search YouTube and insert videos, playlists, and even galleries right from your editor tab.
                    Simply click the <img style="vertical-align: text-bottom;" src="<?php echo plugins_url('images/wizbuttonbig.png', __FILE__) ?>"> wizard button found above 
                    your editor to start the wizard (see image below to locate this button). There, you'll have several options for different types of embeds.
                    Each embed code will have an <span class="button-primary cuz">&#9660; Insert Into Editor</span> button that 
                    you can click to directly embed the desired video to your post without having to copy and paste.
                </p>
                <p>
                    <b class="orange">Even more options are available to PRO users!</b> Simply click the <a class="button-primary cuz">&#9658; Customize</a> button on the wizard to further personalize each of your embeds without having to manually add special codes yourself. The customize button will allow you to easily override most of the above default options for that embed.
                    <br>
                    <br>
                    <img style="width: 500px; margin: 0 auto; display: block;" src="<?php echo plugins_url('images/ssprowizard.png', __FILE__) ?>" >
                </p>

                <div class="jumper" id="jumpcompat"></div>
                <h3 class="sect">Compatibility Settings <a href="#top" class="totop">&#9650; top</a></h3>
                <p>
                    With tens of thousands of active users, our plugin may not work with every plugin out there. Below are some settings you may wish to try out. 
                </p>
                <div class="ytindent chx">
                    <p>
                        <input name="<?php echo self::$opt_old_script_method; ?>" id="<?php echo self::$opt_old_script_method; ?>" <?php checked($all[self::$opt_old_script_method], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_old_script_method; ?>">
                            <b class="chktitle">Use Legacy Scripts: </b>
                            This is a legacy option for users with theme issues that require backwards compatibility (v.10.5 or earlier). It may also help with caching plugin or CDN plugin issues.
                        </label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_admin_off_scripts; ?>" id="<?php echo self::$opt_admin_off_scripts; ?>" <?php checked($all[self::$opt_admin_off_scripts], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_admin_off_scripts; ?>">
                            <b class="chktitle">Turn Off Scripts While Editing: </b>
                            Front-end editors and visual pagebuilders often run Javascript while you're in edit mode. Check this to turn off this plugin's Javascript during edit mode, if you see conflicts.
                            Don't worry, all other visitors to your site will still view your site normally.
                        </label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_migrate; ?>" id="<?php echo self::$opt_migrate; ?>" <?php checked($all[self::$opt_migrate], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_migrate; ?>">
                            <b class="chktitle">Migrate Shortcodes: </b> Inherit shortcodes from other plugins. This is useful for when a plugin becomes deprecated, or you simply prefer this plugin's features.
                        </label>
                    <div id="boxmigratelist">
                        <ul>
                            <li><input name="<?php echo self::$opt_migrate_embedplusvideo; ?>" id="<?php echo self::$opt_migrate_embedplusvideo; ?>" <?php checked($all[self::$opt_migrate_embedplusvideo], 1); ?> type="checkbox" class="checkbox"><label for="<?php echo self::$opt_migrate_embedplusvideo; ?>"><b>"YouTube Advanced Embed":</b>   <code>[embedplusvideo]</code> shortcode</label></li>
                            <li><input name="<?php echo self::$opt_migrate_youtube; ?>" id="<?php echo self::$opt_migrate_youtube; ?>" <?php checked($all[self::$opt_migrate_youtube], 1); ?> type="checkbox" class="checkbox"><label for="<?php echo self::$opt_migrate_youtube; ?>"><b>"YouTube Embed":</b> <code>[youtube]</code> and <code>[youtube_video]</code> shortcodes</label></li>
                            <li class="smallnote orange" style="list-style: none;">This feature is beta. More shortcodes coming.</li>
                        </ul>

                    </div>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_oldspacing; ?>" id="<?php echo self::$opt_oldspacing; ?>" <?php checked($all[self::$opt_oldspacing], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_oldspacing; ?>">
                            <b class="chktitle">Legacy Spacing:</b> Continue the spacing style from version 4.0 and older. Those versions required you to manually add spacing above and below your video. Unchecking this will automatically add the spacing.
                        </label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_evselector_light; ?>" id="<?php echo self::$opt_evselector_light; ?>" <?php checked($all[self::$opt_evselector_light], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_evselector_light; ?>">
                            <b class="chktitle">Theme Video Problems: </b> 
                            Check this option if you're having issues with autoplayed videos or background videos etc. that have been generated by your theme.
                        </label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_stop_mobile_buffer; ?>" id="<?php echo self::$opt_stop_mobile_buffer; ?>" <?php checked($all[self::$opt_stop_mobile_buffer], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_stop_mobile_buffer; ?>">
                            <b class="chktitle">Mobile Autoplay Problems: </b> 
                            Autoplay works for desktop, but mobile devices don't allow autoplay due to network carrier data charges. For mobile devices, this option allows the player to properly display the video for the visitor to click on.
                        </label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_debugmode; ?>" id="<?php echo self::$opt_debugmode; ?>" <?php checked($all[self::$opt_debugmode], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_debugmode; ?>">
                            <b class="chktitle">Debug Mode: </b> If you ask for support, we may ask you to turn on debug mode here.
                            It may print out some diagnostic info so that we can help you solve your issue. 
                        </label>
                    </p>

                </div>
                <div class="jumper" id="jumpgallery"></div>
                <h3 class="sect">Gallery Settings and Directions <a href="#top" class="totop">&#9650; top</a></h3>
                <img class="ssgallery" src="<?php echo plugins_url('images/ssgallery.png', __FILE__) ?>">
                <p>
                    <a target="_blank" href="<?php echo self::$epbase ?>/responsive-youtube-playlist-channel-gallery-for-wordpress.aspx">You can now make playlist embeds (and channel-playlist embeds) have a gallery layout &raquo;</a>. <strong>First, you must obtain your YouTube API key</strong>. 
                    Don't worry, it's an easy process. Just <a href="https://www.youtube.com/watch?v=LpKDFT40V0U" target="_blank">click this link &raquo;</a> and follow the video on that page to get your server API key. Since Google updates their API Key generation directions frequently, follow the general steps shown in the video.
                    Then paste your API key in the "YouTube API Key" box at the top of this screen, and click the "Save Changes" button.
                </p>

                <p>
                    Below are the settings for galleries:
                </p>
                <div class="ytindent chx">

                    <p>
                        <label for="<?php echo self::$opt_gallery_pagesize; ?>"><b class="chktitle">Gallery Page Size:</b></label>
                        <select name="<?php echo self::$opt_gallery_pagesize; ?>" id="<?php echo self::$opt_gallery_pagesize; ?>" style="width: 60px;">
                            <?php
                            $gps_val = intval(trim($all[self::$opt_gallery_pagesize]));
                            $gps_val = min($gps_val, 50);
                            for ($gps = 1; $gps <= 50; $gps++)
                            {
                                ?><option <?php echo $gps_val == $gps ? 'selected' : '' ?> value="<?php echo $gps ?>"><?php echo $gps ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        Enter how many thumbnails per page should be shown at once (YouTube allows a maximum of 50 per page).
                    </p>
                    <p>
                        <label for="<?php echo self::$opt_gallery_columns; ?>"><b class="chktitle">Number of Columns:</b></label>
                        <input name="<?php echo self::$opt_gallery_columns; ?>" id="<?php echo self::$opt_gallery_columns; ?>" type="number" class="textinput" style="width: 60px;" value="<?php echo trim($all[self::$opt_gallery_columns]); ?>">                        
                        Enter how many thumbnails can fit per row.
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_gallery_collapse_grid; ?>" id="<?php echo self::$opt_gallery_collapse_grid; ?>" <?php checked($all[self::$opt_gallery_collapse_grid], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_gallery_collapse_grid; ?>">
                            <b class="chktitle">Stack Thumbnails for Mobile:</b> <sup class="orange">NEW</sup> Check this option to responsively stack thumbnails on smaller screens, for the grid layout.
                        </label>
                        <span id="box_collapse_grid">
                            <?php
                            foreach ($all[self::$opt_gallery_collapse_grid_breaks] as $idx => $bpts)
                            {
                                ?>
                                On screens up to
                                <input type="number" name="<?php echo self::$opt_gallery_collapse_grid_breaks . '[' . $idx . '][bp][max]'; ?>"
                                       id="<?php echo self::$opt_gallery_collapse_grid_breaks . '[' . $idx . '][bp][max]'; ?>" 
                                       value="<?php echo intval(trim($bpts['bp']['max'])); ?>" class="textinput" style="width: 70px;">px wide, stack thumbnails to 1 column.
                                <input type="hidden" name="<?php echo self::$opt_gallery_collapse_grid_breaks . '[' . $idx . '][cols]'; ?>"
                                       id="<?php echo self::$opt_gallery_collapse_grid_breaks . '[' . $idx . '][cols]'; ?>"
                                       value="<?php echo intval(trim($bpts['cols'])); ?>">
                                <input type="hidden" name="<?php echo self::$opt_gallery_collapse_grid_breaks . '[' . $idx . '][bp][min]'; ?>"
                                       id="<?php echo self::$opt_gallery_collapse_grid_breaks . '[' . $idx . '][bp][min]'; ?>"
                                       value="<?php echo intval(trim($bpts['bp']['min'])); ?>">
                                       <?php
                                   }
                                   ?>
                            <span class="smallnote grey pad20"><br>Note: a common mobile screen width is 767 pixels.</span>
                        </span>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_gallery_showpaging; ?>" id="<?php echo self::$opt_gallery_showpaging; ?>" <?php checked($all[self::$opt_gallery_showpaging], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_gallery_showpaging; ?>"><b class="chktitle">Show Pagination:</b> Show the Next/Previous buttons and page numbering.</label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_gallery_customarrows; ?>" id="<?php echo self::$opt_gallery_customarrows; ?>" <?php checked($all[self::$opt_gallery_customarrows], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_gallery_customarrows; ?>">
                            <b class="chktitle">Custom Next/Previous Text:</b> If you want your gallery viewers to see something besides "Next" and "Prev" when browsing through thumbnails, enter your replacement text here. This feature can be quite useful for non-English sites.  For example, a French site might replace Prev with Pr&eacute;c&eacute;dent  and Next with Suivant.
                        </label>
                        <span id="boxcustomarrows">
                            Previous Page: <input type="text" name="<?php echo self::$opt_gallery_customprev; ?>" id="<?php echo self::$opt_gallery_customprev; ?>" value="<?php echo esc_attr(trim($all[self::$opt_gallery_customprev])); ?>" class="textinput" style="width: 100px;"> &nbsp;
                            Next Page: <input type="text" name="<?php echo self::$opt_gallery_customnext; ?>" id="<?php echo self::$opt_gallery_customnext; ?>" value="<?php echo esc_attr(trim($all[self::$opt_gallery_customnext])); ?>" class="textinput" style="width: 100px;">
                        </span>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_gallery_channelsub; ?>" id="<?php echo self::$opt_gallery_channelsub; ?>" <?php checked($all[self::$opt_gallery_channelsub], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_gallery_channelsub; ?>">
                            <b class="chktitle">Show Subscribe Button: </b> Are you the channel owner for all your galleries? Check this box to add a "Subscribe" button to all your galleries as shown below.  This might help you convert your site's visitors to YouTube subscribers of your channel.
                        </label>
                        <span id="boxchannelsub">
                            Channel URL: <input type="text" placeholder="https://www.youtube.com/user/YourChannel" name="<?php echo self::$opt_gallery_channelsublink; ?>" id="<?php echo self::$opt_gallery_channelsublink; ?>" value="<?php echo esc_attr(trim($all[self::$opt_gallery_channelsublink])); ?>" class="textinput" style="width: 200px;"> &nbsp;
                            Button text: <input type="text" name="<?php echo self::$opt_gallery_channelsubtext; ?>" id="<?php echo self::$opt_gallery_channelsubtext; ?>" value="<?php echo esc_attr(trim($all[self::$opt_gallery_channelsubtext])); ?>" class="textinput" style="width: 200px;">
                        </span>
                    </p>
                    <p><img class="sssubscribe" src="<?php echo plugins_url('images/sssubscribe.png', __FILE__) ?>"></p>

                    <p>
                        <label for="<?php echo self::$opt_gallery_scrolloffset; ?>"><b class="chktitle">Scroll Offset:</b></label>
                        <input name="<?php echo self::$opt_gallery_scrolloffset; ?>" id="<?php echo self::$opt_gallery_scrolloffset; ?>" type="number" class="textinput" style="width: 60px;" value="<?php echo trim($all[self::$opt_gallery_scrolloffset]); ?>">
                        After you click on a thumbnail, the gallery will automatically smooth scroll up to the actual player. If you need it to scroll a few pixels further, increase this number.
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_gallery_showtitle; ?>" id="<?php echo self::$opt_gallery_showtitle; ?>" <?php checked($all[self::$opt_gallery_showtitle], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_gallery_showtitle; ?>"><b class="chktitle">Show Thumbnail Title:</b> Show titles with each thumbnail.</label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_gallery_autonext; ?>" id="<?php echo self::$opt_gallery_autonext; ?>" <?php checked($all[self::$opt_gallery_autonext], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_gallery_autonext; ?>"><b class="chktitle">Automatic Continuous Play:</b>  Automatically play the next video in the gallery as soon as the current video finished.</label>
                    </p>
                    <p>
                        <input name="<?php echo self::$opt_gallery_thumbplay; ?>" id="<?php echo self::$opt_gallery_thumbplay; ?>" <?php checked($all[self::$opt_gallery_thumbplay], 1); ?> type="checkbox" class="checkbox">
                        <label for="<?php echo self::$opt_gallery_thumbplay; ?>"><b class="chktitle">Thumbnail Click Plays Video:</b> Clicking on a gallery thumbnail autoplays the video. Uncheck this and visitors must also click the video's play button after clicking the thumbnail.</label>
                    </p>
                    <div class="pad20">
                        <p>
                            Ready to get started with an actual gallery?  Just click the plugin wizard button and pick your desired gallery embedding choice.
                        </p>
                        <p><img class="sswizardbutton" src="<?php echo plugins_url('images/sswizardbutton.jpg', __FILE__) ?>"></p>
                    </div>
                </div>


                <div class="jumper" id="jumpprosettings"></div>
                <div class="upgchecks">
                    <h3 class="sect">PRO Features <a href="#top" class="totop">&#9650; top</a></h3>
                    <?php
                    if ($all[self::$opt_pro] && strlen(trim($all[self::$opt_pro])) > 9)
                    {
                        ?>
                        <p class="smallnote orange">Below are PRO features for enhanced SEO and performance (works for even past embed links). Gallery options for PRO users will also be listed here.</p>
                        <p>
                            <img class="ssaltgallery" src="<?php echo plugins_url('images/ssaltgalleryall.jpg', __FILE__) ?>" />
                            <?php $cleanstyle = trim($all[self::$opt_gallery_style]); ?>
                            <select name="<?php echo self::$opt_gallery_style; ?>" id="<?php echo self::$opt_gallery_style; ?>" >
                                <option value="">Gallery Style</option>
                                <option value="grid" <?php echo 'grid' === $cleanstyle ? 'selected' : '' ?> >Grid (default)</option>
                                <option value="listview" <?php echo 'listview' === $cleanstyle ? 'selected' : '' ?> >Vertical List</option>
                                <option value="carousel" <?php echo 'carousel' === $cleanstyle ? 'selected' : '' ?> >Horizontal Slider</option>
                            </select>
                            <label for="<?php echo self::$opt_gallery_style; ?>">
                                <b>(PRO)</b>  <b class="chktitle">Alternate Gallery Styling:</b>
                                Switch from the grid style of the FREE version to another gallery style. Right now, we provide a vertical (single column) and horizontal (single row) list style as alternatives to the grid, with more designs coming. These current alternatives were inspired by the standard YouTube playlist player's "table of contents," except our gallery's video lists are always visible and shown under the playing video.
                                <a target="_blank" href="<?php echo self::$epbase ?>/responsive-youtube-playlist-channel-gallery-for-wordpress.aspx">Read more here &raquo;</a>
                            </label>
                        </p>

                        <div class="hr"></div>
                        <p>
                            <img class="ssaltgallery" src="<?php echo plugins_url('images/ssverticallayout.png', __FILE__) ?>" />
                            <input name="<?php echo self::$opt_gallery_showdsc; ?>" id="<?php echo self::$opt_gallery_showdsc; ?>" <?php checked($all[self::$opt_gallery_showdsc], 1); ?> type="checkbox" class="checkbox">
                            <label for="<?php echo self::$opt_gallery_showdsc; ?>">
                                <b>(PRO)</b> <b class="chktitle">Show Gallery Descriptions (for vertical list styling): </b> 
                                For the vertical list layout, this option will show full video descriptions (taken directly from YouTube.com) with each thumbnail. Note: these descriptions only apply the vertical list layout; other layouts don't have enough room.
                            </label>
                        </p>
                        <div class="hr"></div>
                        <p>
                            <img class="ssaltgallery" src="<?php echo plugins_url('images/ssaltgallerycircles.jpg', __FILE__) ?>" />
                            <?php $cleancrop = trim($all[self::$opt_gallery_thumbcrop]); ?>
                            <label for="<?php echo self::$opt_gallery_thumbcrop; ?>">
                                <b>(PRO)</b>  <b class="chktitle">Gallery Thumbnail Shape:</b>
                                Differentiate your gallery by showing different thumbnail shapes.  We currently offer rectangle and circle shapes.
                            </label>
                            <br>
                            <select name="<?php echo self::$opt_gallery_thumbcrop; ?>" id="<?php echo self::$opt_gallery_thumbcrop; ?>" >
                                <option value="">Thumbnail Shape</option>
                                <option value="box" <?php echo 'box' === $cleancrop ? 'selected' : '' ?> >Rectangle (default)</option>
                                <option value="portal" <?php echo 'portal' === $cleancrop ? 'selected' : '' ?> >Circular</option>
                            </select>
                        </p>
                        <div class="hr"></div>
                        <p>
                            <img class="sspopupplayer" src="<?php echo plugins_url('images/sspopupplayer.jpg', __FILE__) ?>" />
                            <?php $cleandisp = trim($all[self::$opt_gallery_disptype]); ?>
                            <label for="<?php echo self::$opt_gallery_disptype; ?>">
                                <b>(PRO)</b>  <b class="chktitle">Gallery Video Display Mode:</b> <sup class="orange">NEW</sup>
                                Display your gallery videos simply above the thumbnails (default), or as a popup lightbox.
                            </label>
                            <br>
                            <select name="<?php echo self::$opt_gallery_disptype; ?>" id="<?php echo self::$opt_gallery_disptype; ?>" >
                                <option value="">Display Type</option>
                                <option value="default" <?php echo 'default' === $cleandisp ? 'selected' : '' ?> >Above Thumbnails (default)</option>
                                <option value="lb" <?php echo 'lb' === $cleandisp ? 'selected' : '' ?> >Popup Lightbox</option>
                            </select>
                        </p>
                        <div class="hr"></div>

                        <p>
                            <input name="<?php echo self::$opt_spdc; ?>" id="<?php echo self::$opt_spdc; ?>" <?php checked($all[self::$opt_spdc], 1); ?> type="checkbox" class="checkbox">
                            <label for="<?php echo self::$opt_spdc; ?>">
                                <b>(PRO)</b> <b class="chktitle">Faster Page Loads (Caching): </b> 
                                Use embed caching to speed up your page loads. By default, WordPress needs to request information from YouTube.com's servers for every video you embed, every time a page is loaded. These data requests can add time to your total page load time. Turn on this feature to cache that data (instead of having to request for the same information every time you load a page). This should then make your pages that have videos load faster.  It's been noted that even small speed ups in page load can help increase visitor engagement, retention, and conversions. Caching also makes galleries run faster.
                            </label>
                        <div class="indent-option">
                            <div id="boxspdc">
                                <div class="pad10">
                                    <input type="button" class="button button-primary" value="Click to clear YouTube cache"/>
                                    <span style="display: none;" id="clearspdcloading" class="orange bold">Clearing...</span>
                                    <span  class="orange bold" style="display: none;" id="clearspdcsuccess">Finished clearing YouTube cache.</span>
                                    <span class="orange bold" style="display: none;" id="clearspdcfailed">Sorry, there seemed to be a problem clearing the cache.</span>
                                </div>
                                <label>
                                    <b class="chktitle">Cache Liftime (hours): </b>
                                    <input name="<?php echo self::$opt_spdcexp; ?>" id="<?php echo self::$opt_spdcexp; ?>" value="<?php echo trim($all[self::$opt_spdcexp]); ?>" type="number" min="1"/>
                                    Tip: If your pages rarely change, you may wish to set this to a much higher value than 24 hours.
                                </label>
                                <br>
                                <br>
                                <label>
                                    <input name="<?php echo self::$opt_spdcab; ?>" id="<?php echo self::$opt_spdcab; ?>" <?php checked($all[self::$opt_spdcab], 1); ?> type="checkbox" class="checkbox"> 
                                    <b class="chktitle">Show "Clear YouTube Cache" Admin Bar Button: </b> 
                                    This will display the "Clear YouTube Cache" button conveniently in the top admin bar. Uncheck this if you wish to hide the button.
                                </label>

                            </div>
                        </div>
                        </p>
                        <div class="hr"></div>

                        <p>
                            <input name="<?php echo self::$opt_schemaorg; ?>" id="<?php echo self::$opt_schemaorg; ?>" <?php checked($all[self::$opt_schemaorg], 1); ?> type="checkbox" class="checkbox">
                            <label for="<?php echo self::$opt_schemaorg; ?>">
                                <b>(PRO)</b> <b class="chktitle">Video SEO Tags:</b> Update your YouTube embeds with Google, Bing, and Yahoo friendly schema markup for videos.
                            </label>
                            <span id="boxschemaorg">
                                <span class="apikey-msg">
                                    The video SEO tags include data like the title, description, and thumbnail information of each video you embed. This plugin automatically extracts this data directly from YouTube using the version 3 API. This particular API version requires that you obtain a server API key so that YouTube can authenticate the requests. <a href="https://www.youtube.com/watch?v=LpKDFT40V0U" target="_blank">Watch this video to see how to create your own key</a>. Then, paste it in the "YouTube API Key" box at the top of this screen, and click the "Save Changes" button.
                                </span>
                            </span>
                        </p>
                        <div class="hr"></div>
                        <p>
                            <input name="<?php echo self::$opt_dynload; ?>" id="<?php echo self::$opt_dynload; ?>" <?php checked($all[self::$opt_dynload], 1); ?> type="checkbox" class="checkbox">                        
                            <label for="<?php echo self::$opt_dynload; ?>">
                                <b>(PRO)</b>  <b class="chktitle">Special Lazy-Loading Effects:</b>
                                Add eye-catching special effects that will make your YouTube embeds bounce, flip, pulse, or slide as they lazy load on the screen.  Check this box to select your desired effect. <a target="_blank" href="<?php echo self::$epbase ?>/add-special-effects-to-youtube-embeds-in-wordpress.aspx">Read more here &raquo;</a>
                            </label>
                            <br>
                            <span id="boxdyn">
                                Animation:
                                <?php $cleandyn = trim($all[self::$opt_dyntype]); ?>
                                <select name="<?php echo self::$opt_dyntype; ?>" id="<?php echo self::$opt_dyntype; ?>" >
                                    <option value="">Select type</option>
                                    <option value="rotateIn" <?php echo 'rotateIn' === $cleandyn ? 'selected' : '' ?> >rotate in</option>
                                    <option value="slideInRight" <?php echo 'slideInRight' === $cleandyn ? 'selected' : '' ?> >slide from right</option>
                                    <option value="slideInLeft" <?php echo 'slideInLeft' === $cleandyn ? 'selected' : '' ?> >slide from left</option>
                                    <option value="bounceIn" <?php echo 'bounceIn' === $cleandyn ? 'selected' : '' ?> >bounce in</option>
                                    <option value="flipInX" <?php echo 'flipInX' === $cleandyn ? 'selected' : '' ?> >flip up/down</option>
                                    <option value="flipInY" <?php echo 'flipInY' === $cleandyn ? 'selected' : '' ?> >flip left/right</option>
                                    <option value="pulse" <?php echo 'pulse' === $cleandyn ? 'selected' : '' ?> >pulse</option>
                                    <option value="tada" <?php echo 'tada' === $cleandyn ? 'selected' : '' ?> >jiggle</option>
                                    <option value="fadeIn" <?php echo 'fadeIn' === $cleandyn ? 'selected' : '' ?> >fade in</option>
                                    <option value="fadeInDown" <?php echo 'fadeInDown' === $cleandyn ? 'selected' : '' ?> >fade in downward</option>
                                    <option value="fadeInUp" <?php echo 'fadeInUp' === $cleandyn ? 'selected' : '' ?> >fade in upward</option>
                                    <option value="zoomInDown" <?php echo 'zoomInDown' === $cleandyn ? 'selected' : '' ?> >zoom in downward</option>
                                    <option value="zoomInUp" <?php echo 'zoomInUp' === $cleandyn ? 'selected' : '' ?> >zoom in upward</option>
                                </select>
                            </span>
                        </p>
                        <div class="hr"></div>
                        <p>
                            <input name="<?php echo self::$opt_ogvideo; ?>" id="<?php echo self::$opt_ogvideo; ?>" <?php checked($all[self::$opt_ogvideo], 1); ?> type="checkbox" class="checkbox">
                            <label for="<?php echo self::$opt_ogvideo; ?>">
                                <b>(PRO)</b> <b class="chktitle">Facebook Open Graph Markup:</b>  Include Facebook Open Graph markup with the videos you embed with this plugin.  We follow the guidelines for videos as described here: <a href="https://developers.facebook.com/docs/sharing/webmasters#media" target="_blank">https://developers.facebook.com/docs/sharing/webmasters#media</a>
                            </label>
                        </p>
                        <div class="hr"></div>
                        <p>
                            <img class="ssfb" src="<?php echo plugins_url('images/youtube_thumbnail_sample.jpg', __FILE__) ?>" />
                            <input name="<?php echo self::$opt_ftpostimg; ?>" id="<?php echo self::$opt_ftpostimg; ?>" <?php checked($all[self::$opt_ftpostimg], 1); ?> type="checkbox" class="checkbox">
                            <label for="<?php echo self::$opt_ftpostimg; ?>">
                                <b>(PRO)</b> <b class="chktitle">Featured Thumbnail Images: </b> 
                                Automatically grab the thumbnail image of the first video embedded in each post or page, and use it as the featured image.  If your theme can display featured images of posts on your blog home, you’ll see the thumbnails there as shown in the picture on the right.  All you have to do is click Update on a post or page and the plugin does the rest!
                                (Example shown on the right) <a target="_blank" href="<?php echo self::$epbase ?>/add-youtube-video-thumbnails-featured-image-wordpress.aspx">Watch example here &raquo;</a>
                            </label>
                        </p>

                        <?php
                    }
                    else
                    {
                        ?>
                        <p class="smallnote orange">Below are PRO features for enhanced SEO and performance (works for even past embed links). </p>
                        <p>
                            <img class="ssaltgallery" src="<?php echo plugins_url('images/ssaltgalleryall.jpg', __FILE__) ?>" />
                            <select disabled>
                                <option value="">Gallery Style</option>
                            </select>
                            <label>
                                <b class="chktitle">Alternate Gallery Styling: </b> <span class="pronon">(PRO Users)</span> 
                                Switch from the grid style of the FREE version to another gallery style. Right now, we provide a vertical (single column) and horizontal (single row) list style as alternatives to the grid, with more designs coming. These current alternatives were inspired by the standard YouTube playlist player's "table of contents," except our gallery's video lists are always visible and shown under the playing video.
                                <a target="_blank" href="<?php echo self::$epbase ?>/responsive-youtube-playlist-channel-gallery-for-wordpress.aspx">Read more here &raquo;</a>
                            </label>
                        </p>

                        <div class="hr"></div>
                        <p>
                            <img class="ssaltgallery" src="<?php echo plugins_url('images/ssverticallayout.png', __FILE__) ?>" />
                            <input disabled type="checkbox" class="checkbox">
                            <label>
                                <b class="chktitle">Show Gallery Descriptions (for vertical list styling): </b>  <span class="pronon">(PRO Users)</span> 
                                For the vertical list layout, this option will show full video descriptions (taken directly from YouTube.com) with each thumbnail. Note: these descriptions only apply the vertical list layout; other layouts don't have enough room.
                            </label>
                        </p>
                        <div class="hr"></div>
                        <p>
                            <img class="ssaltgallery" src="<?php echo plugins_url('images/ssaltgallerycircles.jpg', __FILE__) ?>" />
                            <select disabled>
                                <option value="">Select Thumbnail Shape</option>
                            </select>
                            <label>
                                <b class="chktitle">Gallery Thumbnail Shape: </b> <span class="pronon">(PRO Users)</span> 
                                Differentiate your gallery by showing different thumbnail shapes.  We currently offer rectangle and circle shapes.
                                <a target="_blank" href="<?php echo self::$epbase ?>/responsive-youtube-playlist-channel-gallery-for-wordpress.aspx">Read more here &raquo;</a>
                            </label>
                        </p>

                        <div class="hr"></div>
                        <p>
                            <img class="sspopupplayer" src="<?php echo plugins_url('images/sspopupplayer.jpg', __FILE__) ?>" />
                            <label>
                                <b class="chktitle">Gallery Video Display Mode: </b> <sup class="orange">NEW</sup> <span class="pronon">(PRO Users)</span>
                                Display your gallery videos simply above the thumbnails (default), or as a popup lightbox.
                            </label>
                            <br>
                            <input type="radio" disabled> Default &nbsp; <input type="radio" disabled> Popup lightbox
                        </p>

                        <div class="hr"></div>
                        <p>
                            <input disabled type="checkbox" class="checkbox">
                            <label>
                                <b class="chktitle">Faster Page Loads (Caching): </b>  <span class="pronon">(PRO Users)</span> 
                                Use embed caching to speed up your page loads. By default, WordPress needs to request information from YouTube.com's servers for every video you embed, every time a page is loaded. These data requests can add time to your total page load time. Turn on this feature to cache that data (instead of having to request for the same information every time you load a page). This should then make your pages that have videos load faster.  It's been noted that even small speed ups in page load can help increase visitor engagement, retention, and conversions. Caching also makes galleries run faster.
                            </label>
                        <div class="indent-option">
                            <label>
                                <b class="chktitle">Cache Lifetime (hours): </b> 
                                <input disabled value="24" type="number">
                                Tip: If your pages rarely change, you may wish to set this to a much higher value than 24 hours.
                            </label>
                        </div>
                        </p>
                        <div class="hr"></div>


                        <p>
                            <input disabled type="checkbox" class="checkbox">
                            <label>
                                <b class="chktitle">Video SEO Tags:</b>  <span class="pronon">(PRO Users)</span> Update your YouTube embeds with Google, Bing, and Yahoo friendly schema markup for videos.
                            </label>
                        </p>
                        <div class="hr"></div>
                        <p>
                            <input disabled type="checkbox" class="checkbox">
                            <label>
                                <b class="chktitle">Special Lazy-Loading Effects:</b>  <span class="pronon">(PRO Users)</span> 
                                Add eye-catching special effects that will make your YouTube embeds bounce, flip, pulse, or slide as they lazy load on the screen.  Check this box to select your desired effect. <a target="_blank" href="<?php echo self::$epbase ?>/add-special-effects-to-youtube-embeds-in-wordpress.aspx">Read more here &raquo;</a>
                            </label>
                        </p>
                        <div class="hr"></div>
                        <p>
                            <input disabled type="checkbox" class="checkbox">
                            <label>
                                <b class="chktitle">Facebook Open Graph Markup:</b> <span class="pronon">(PRO Users)</span>   Include Facebook Open Graph markup with the videos you embed with this plugin.  We follow the guidelines for videos as described here: <a href="https://developers.facebook.com/docs/sharing/webmasters#media" target="_blank">https://developers.facebook.com/docs/sharing/webmasters#media</a>
                            </label>
                        </p>
                        <div class="hr"></div>
                        <p>
                            <img class="ssfb" src="<?php echo plugins_url('images/youtube_thumbnail_sample.jpg', __FILE__) ?>" />
                            <input disabled type="checkbox" class="checkbox">
                            <label>
                                <b class="chktitle">Featured Thumbnail Images:</b>  <span class="pronon">(PRO Users)</span> 
                                Automatically grab the thumbnail image of the first video embedded in each post or page, and use it as the featured image. 
                                All you have to do is click Update on a post or page and the plugin does the rest! 
                                (Example shown on the right) <a target="_blank" href="<?php echo self::$epbase ?>/add-youtube-video-thumbnails-featured-image-wordpress.aspx">Read more here &raquo;</a>
                            </label>
                        </p>
                        <div class="hr"></div>
                        <p>
                            <a href="<?php echo self::$epbase ?>/dashboard/pro-easy-video-analytics.aspx" target="_blank">Activate the above and several other features &raquo;</a>
                        </p>
                        <?php
                    }
                    ?>
                    <div class="clearboth"></div>
                </div>


                <hr>

                <div class="jumper" id="jumphowto"></div>
                <h3 class="sect">
                    Manually Embed a YouTube Video or Playlist &nbsp; <a class="smallnote" href="#jumpgallery">(For gallery directions, go here &raquo;)</a>
                    <a href="#top" class="totop">&#9650; top</a>
                </h3>
                <p>
                    <strong>We recommend using the wizard in your editor to embed.</strong> However, if you choose to manually embed code, follow the instructions below.
                </p>
                <p>
                    <b>For videos:</b> <i>Method 1 - </i> Do you already have a URL to the video you want to embed in a post, page, or even a widget? All you have to do is paste it on its own line, as shown below (including the https:// part). Easy, eh?<br>
                    <i>Method 2 - </i> If you want to do some formatting (e.g. add HTML to center a video) or have two or more videos next to each other on the same line, wrap each link with the <code>[embedyt]...[/embedyt]</code> shortcode. <b>Tip for embedding videos on the same line:</b> As shown in the example image below, decrease the size of each video so that they fit together on the same line (See the "How To Override Defaults" section for height and width instructions).
                </p>
                <p>
                    <b>For galleries:</b> <a href="#jumpgallery">Click here</a> to scroll down to gallery settings and directions.
                </p>
                <p>
                    <b>For self-contained playlists:</b> Go to the page for the playlist that lists all of its videos (<a target="_blank" href="http://www.youtube.com/playlist?list=PL70DEC2B0568B5469">Example &raquo;</a>). Click on the video that you want the playlist to start with. Copy and paste that browser URL into your blog on its own line. If you want the first video to always be the latest video in your playlist, check the option "Playlist Ordering" in the settings down below (you will also see this option available if you use the Pro Wizard). If you want to have two or more playlists next to each other on the same line, wrap each link with the <code>[embedyt]...[/embedyt]</code> shortcode.
                </p>                
                <p>
                    <b>For self-contained channel playlists:</b> At your editor, click on the <img style="vertical-align: text-bottom;" src="<?php echo plugins_url('images/wizbuttonbig.png', __FILE__) ?>"> wizard button and choose the option <i>Search for a video or channel to insert in my editor.</i> Then, click on the <i>channel playlist</i> option there (instead of <i>single video</i>). Search for the channel username and follow the rest of the directions there.
                </p>
                <p>
                    <b>Examples:</b><br><br>
                    <img style="width: 900px; height: auto;" class="shadow" src="<?php echo plugins_url('images/sshowto.png', __FILE__) ?>" />
                </p>
                <p>
                    Always follow these rules for any URL:
                </p>
                <ul class="reglist">
                    <li>Make sure the URL is really on its own line by itself. Or, if you need multiple videos on the same line, make sure each URL is wrapped properly with the shortcode (Example:  <code>[embedyt]http://www.youtube.com/watch?v=ABCDEFGHIJK&width=400&height=250[/embedyt]</code>)</li>
                    <li>Make sure the URL is <strong>not</strong> an active hyperlink (i.e., it should just be plain text). Otherwise, highlight the URL and click the "unlink" button in your editor: <img src="<?php echo plugins_url('images/unlink.png', __FILE__) ?>"/></li>
                    <li>Make sure you did <strong>not</strong> format or align the URL in any way. If your URL still appears in your actual post instead of a video, highlight it and click the "remove formatting" button (formatting can be invisible sometimes): <img src="<?php echo plugins_url('images/erase.png', __FILE__) ?>"/></li>
                    <li>If you really want to align the video, try wrapping the link with the shortcode first. For example: <code>[embedyt]http://www.youtube.com/watch?v=ABCDEFGHIJK[/embedyt]</code> Using the shortcode also allows you to have two or more videos next to each other on the same line.  Just put the shortcoded links together on the same line. For example:<br>
                        <code>[embedyt]http://www.youtube.com/watch?v=ABCDEF[/embedyt] [embedyt]http://www.youtube.com/watch?v=GHIJK[/embedyt]</code>
                </ul>       

                <h3 class="sect">
                    <?php _e("How To Manually Override Defaults / Other Options") ?> <a href="#top" class="totop">&#9650; top</a>
                </h3>
                <p>Suppose you have a few videos that need to be different from the above defaults. You can add options to the end of a link as displayed below to override the above defaults. Each option should begin with '&'.
                    <br><span class="smallnote orange">PRO users: You can use the big blue <a href="<?php echo self::$epbase . '/dashboard/pro-easy-video-analytics.aspx?ref=protab' ?>" target="_blank">customize</a> buttons that you will see inside the wizard, instead of memorizing the following.</span>
                    <?php
                    _e('<ul>');
                    _e("<li><strong>width</strong> - Sets the width of your player. If omitted, the default width will be the width of your theme's content.<em> Example: http://www.youtube.com/watch?v=quwebVjAEJA<strong>&width=500</strong>&height=350</em></li>");
                    _e("<li><strong>height</strong> - Sets the height of your player. <em>Example: http://www.youtube.com/watch?v=quwebVjAEJA&width=500<strong>&height=350</strong></em> </li>");
                    _e("<li><strong>autoplay</strong> - Set this to 1 to autoplay the video (or 0 to play the video once). <em>Example: http://www.youtube.com/watch?v=quwebVjAEJA<strong>&autoplay=1</strong></em> </li>");
                    _e("<li><strong>cc_load_policy</strong> - Set this to 1 to turn on closed captioning (or 0 to leave them off). <em>Example: http://www.youtube.com/watch?v=quwebVjAEJA<strong>&cc_load_policy=1</strong></em> </li>");
                    _e("<li><strong>iv_load_policy</strong> - Set this to 3 to turn off annotations (or 1 to show them). <em>Example: http://www.youtube.com/watch?v=quwebVjAEJA<strong>&iv_load_policy=3</strong></em> </li>");
                    _e("<li><strong>loop</strong> - Set this to 1 to loop the video (or 0 to not loop). <em>Example: http://www.youtube.com/watch?v=quwebVjAEJA<strong>&loop=1</strong></em> </li>");
                    _e("<li><strong>modestbranding</strong> - Set this to 1 to remove the YouTube logo while playing (or 0 to show the logo). <em>Example: http://www.youtube.com/watch?v=quwebVjAEJA<strong>&modestbranding=1</strong></em> </li>");
                    _e("<li><strong>rel</strong> - Set this to 0 to not show related videos at the end of playing (or 1 to show them). <em>Example: http://www.youtube.com/watch?v=quwebVjAEJA<strong>&rel=0</strong></em> </li>");
                    _e("<li><strong>showinfo</strong> - Set this to 0 to hide the video title and other info (or 1 to show it). <em>Example: http://www.youtube.com/watch?v=quwebVjAEJA<strong>&showinfo=0</strong></em> </li>");
                    _e("<li><strong>color</strong> - Set this to 'white' to make the player have a white progress bar (or 'red' for a red progress bar). Note: Using white will disable the modestbranding option. <em>Example: http://www.youtube.com/watch?v=quwebVjAEJA<strong>&color=white</strong></em> </li>");
                    _e("<li><strong>controls</strong> - Set this to 0 to completely hide the video controls (or 2 to show it). <em>Example: http://www.youtube.com/watch?v=quwebVjAEJA<strong>&controls=0</strong></em> </li>");
                    _e("<li><strong>autohide</strong> - Set this to 1 to slide away the control bar after the video starts playing. It will automatically slide back in again if you mouse over the video. (Set to  2 to always show it). <em>Example: http://www.youtube.com/watch?v=quwebVjAEJA<strong>&autohide=1</strong></em> </li>");
                    _e("<li><strong>playsinline</strong> - Set this to 1 to allow videos play inline with the page on iOS browsers. (Set to 0 to have iOS launch videos in fullscreen instead). <em>Example: http://www.youtube.com/watch?v=quwebVjAEJA<strong>&playsinline=1</strong></em> </li>");
                    _e("<li><strong>origin</strong> - Set this to 1 to add the 'origin' parameter for extra JavaScript security. <em>Example: http://www.youtube.com/watch?v=quwebVjAEJA<strong>&origin=1</strong></em> </li>");
                    _e('</ul>');

                    _e("<p>You can also start and end each individual video at particular times. Like the above, each option should begin with '&'</p>");
                    _e('<ul>');
                    _e("<li><strong>start</strong> - Sets the time (in seconds) to start the video. <em>Example: http://www.youtube.com/watch?v=quwebVjAEJA&width=500&height=350<strong>&start=20</strong></em> </li>");
                    _e("<li><strong>end</strong> - Sets the time (in seconds) to stop the video. <em>Example: http://www.youtube.com/watch?v=quwebVjAEJA&width=500&height=350<strong>&end=100</strong></em> </li>");
                    _e('</ul>');
                    ?>
                <div class="save-changes-follow"> <?php self::save_changes_button(isset($_POST[$ytprefs_submitted]) && $_POST[$ytprefs_submitted] == 'Y'); ?> </div>
            </form>


            <div class="jumper" id="jumpsupport"></div>
            <div id="nonprosupport">
                <h3 class="bold">Support tips for all users (Free and PRO)</h3>
                We've found that a common support request has been from users that are pasting video links on single lines, as required, but are not seeing the video embed show up. One of these suggestions is usually the fix:
                <ul class="reglist">
                    <li>Make sure the URL is really on its own line by itself. Or, if you need multiple videos on the same line, make sure each URL is wrapped properly with the shortcode (Example:  <code>[embedyt]http://www.youtube.com/watch?v=ABCDEFGHIJK&width=400&height=250[/embedyt]</code>)</li>
                    <li>Make sure the URL is not an active hyperlink (i.e., it should just be plain text). Otherwise, highlight the URL and click the "unlink" button in your editor: <img src="<?php echo plugins_url('images/unlink.png', __FILE__) ?>"/>.</li>
                    <li>Make sure you did <strong>not</strong> format or align the URL in any way. If your URL still appears in your actual post instead of a video, highlight it and click the "remove formatting" button (formatting can be invisible sometimes): <img src="<?php echo plugins_url('images/erase.png', __FILE__) ?>"/></li>
                    <li>Try wrapping the URL with the <code>[embedyt]...[/embedyt]</code> shortcode. For example: <code>[embedyt]http://www.youtube.com/watch?v=ABCDEFGHIJK[/embedyt]</code> Using the shortcode also allows you to have two or more videos next to each other on the same line.  Just put the shortcoded links together on the same line. For example:<br>
                        <code>[embedyt]http://www.youtube.com/watch?v=ABCDEF&width=400&height=250[/embedyt] [embedyt]http://www.youtube.com/watch?v=GHIJK&width=400&height=250[/embedyt]</code>
                        <br> TIP: As shown above, decrease the size of each video so that they fit together on the same line (See the "How To Override Defaults" section for height and width instructions)
                    </li>
                    <li>If you upload a new video to a playlist or channel and that video is not yet showing up on a gallery you embedded, you should clear/reset any caching plugins you have. This will force your site to retrieve the freshest version of your playlist and/or channel video listing.  If you don't reset you cache, then you'll have to wait until cache lifetime expires.</li>                       
                    <li>Finally, there's a slight chance your custom theme is the issue, if you have one. To know for sure, we suggest temporarily switching to one of the default WordPress themes (e.g., "Twenty Fourteen") just to see if your video does appear. If it suddenly works, then your custom theme is the issue. You can switch back when done testing.</li>
                    <li>If your videos always appear full size, try turning off "Responsive video sizing."</li>
                    <li>If none of the above work, you can contact us here if you still have issues: ext@embedplus.com. We'll try to respond within a week. PRO users should use the priority form below for faster replies.</li>                        
                </ul>
                <p>
                    Deactivating the No Cookies option has also been proven to solve player errors.
                </p>
                <p>
                    We also have a YouTube channel. We use it to provide users with some helper videos and a way to keep updated on new features as they are introduced. <a href="https://www.youtube.com/subscription_center?add_user=EmbedPlus" target="_blank">Subscribe for tips and updates here &raquo;</a>
                </p>
            </div>
            <br>
            <h3 class="sect">
                Priority Support <a href="#top" class="totop">&#9650; top</a>
            </h3>
            <p>
                <strong>PRO users:</strong> Below, We've enabled the ability to have priority support with our team.  Use this to get one-on-one help with any issues you might have or to send us suggestions for future features.  We typically respond within minutes during normal work hours. We're always happy to accept any testimonials you might have as well. 
            </p>


            <iframe src="<?php echo self::$epbase ?>/dashboard/prosupport.aspx?simple=1&prokey=<?php echo $all[self::$opt_pro]; ?>&domain=<?php echo site_url(); ?>" width="500" height="<?php echo ($all[self::$opt_pro] && strlen(trim($all[self::$opt_pro])) > 0) ? "500" : "140"; ?>"></iframe>

            <?php
            if (!($all[self::$opt_pro] && strlen(trim($all[self::$opt_pro])) > 0))
            {
                ?>
                <br>
                <br>
                <iframe src="<?php echo self::$epbase ?>/dashboard/likecoupon.aspx" width="600" height="500"></iframe>
            <?php }
            ?>
            <div class="ytnav">
                <a href="#jumppro">PRO Key</a>
                <a href="#jumpapikey">API Key</a>
                <a href="#jumpdefaults">Defaults</a>
                <a href="#jumpwiz">Visual Wizard</a>
                <a href="#jumpcompat">Compatibility</a>
                <a href="#jumpgallery">Galleries</a>
                <a href="#jumpprosettings">PRO Settings</a>
                <a href="#jumphowto">Embed Manually</a>
                <a href="#jumpsupport">Support</a>
            </div>
        </div>
        <script type="text/javascript">

            function savevalidate()
            {
                var valid = true;
                var alertmessage = '';
                if (jQuery("#<?php echo self::$opt_defaultdims; ?>").is(":checked"))
                {
                    if (!(jQuery.isNumeric(jQuery.trim(jQuery("#<?php echo self::$opt_defaultwidth; ?>").val())) &&
                            jQuery.isNumeric(jQuery.trim(jQuery("#<?php echo self::$opt_defaultheight; ?>").val()))))
                    {
                        alertmessage += "Please enter valid numbers for default height and width, or uncheck the option.";
                        jQuery("#boxdefaultdims input").css("background-color", "#ffcccc").css("border", "2px solid #000000");
                        valid = false;
                    }
                }

                if (jQuery("#<?php echo self::$opt_gallery_customarrows; ?>").is(":checked"))
                {
                    if (!jQuery.trim(jQuery("#<?php echo self::$opt_gallery_customprev; ?>").val()) ||
                            !jQuery.trim(jQuery("#<?php echo self::$opt_gallery_customnext; ?>").val()))
                    {
                        alertmessage += "Please enter valid text for both the custom gallery Prev and Next buttons, or uncheck the option.";
                        jQuery("#boxcustomarrows input").css("background-color", "#ffcccc").css("border", "2px solid #000000");
                        valid = false;
                    }
                }


                if (jQuery("#<?php echo self::$opt_gallery_channelsub; ?>").is(":checked"))
                {
                    if (!jQuery.trim(jQuery("#<?php echo self::$opt_gallery_channelsublink; ?>").val()) ||
                            !jQuery.trim(jQuery("#<?php echo self::$opt_gallery_channelsubtext; ?>").val()))
                    {
                        alertmessage += "Please enter valid text for both the subscribe text and subscribe URL, or uncheck the option.";
                        jQuery("#boxchannelsub input").css("background-color", "#ffcccc").css("border", "2px solid #000000");
                        valid = false;
                    }
                }


                if (jQuery("#<?php echo self::$opt_gallery_collapse_grid; ?>").is(":checked"))
                {
                    var emptyStacks = [];
                    jQuery("#box_collapse_grid input").each(function () {
                        var val = jQuery(this).val();
                        if (jQuery.trim(val) === '' || !jQuery.isNumeric(val))
                        {
                            emptyStacks.push(this);
                            jQuery(this).css("background-color", "#ffcccc").css("outline", "2px solid #000000");
                        }
                    });

                    if (emptyStacks.length)
                    {
                        alertmessage += "Please enter a valid number for the gallery stacking screen width.";
                        valid = false;
                    }
                }



                if (jQuery("#<?php echo self::$opt_defaultvol; ?>").is(":checked"))
                {
                    if (!(jQuery.isNumeric(jQuery.trim(jQuery("#<?php echo self::$opt_vol; ?>").val()))))
                    {
                        alertmessage += "Please enter a number between 0 and 100 for the default volume, or uncheck the option.";
                        jQuery("#boxdefaultvol input").css("background-color", "#ffcccc").css("border", "2px solid #000000");
                        valid = false;
                    }
                }

                if (jQuery("#<?php echo self::$opt_spdc; ?>").is(":checked"))
                {
                    if (!(jQuery.isNumeric(jQuery.trim(jQuery("#<?php echo self::$opt_spdcexp; ?>").val()))))
                    {
                        alertmessage += "Please enter a valid number of hours (greater than 0) for the cache lifetime, or uncheck the option.";
                        jQuery("#boxspdc input[type=number], #boxspdc input[type=text]").css("background-color", "#ffcccc").css("border", "2px solid #000000");
                        valid = false;
                    }
                }



                if (jQuery("#<?php echo self::$opt_schemaorg; ?>").is(":checked"))
                {
                    if (!(jQuery.trim(jQuery("#<?php echo self::$opt_apikey; ?>").val()).length > 0))
                    {
                        alertmessage += "Please enter a valid YouTube API key at the top of this screen, or uncheck the 'Video SEO Tags' option.";
                        jQuery("#<?php echo self::$opt_apikey; ?>").css("background-color", "#ffcccc").css("border", "2px solid #000000");
                        valid = false;
                    }
                }



                if (jQuery("#<?php echo self::$opt_dynload; ?>").is(":checked"))
                {
                    if (!(/^[A-Za-z-]+$/.test(jQuery.trim(jQuery("#<?php echo self::$opt_dyntype; ?>").val()))))
                    {
                        alertmessage += "Please select an animation, or uncheck the option.";
                        jQuery("#boxdyn select").css("background-color", "#ffcccc").css("border", "2px solid #000000");
                        valid = false;
                    }
                }




                //                    if (jQuery("#<?php echo self::$opt_dohl; ?>").is(":checked"))
                //                    {
                //                        if (!(/^[A-Za-z][A-Za-z]$/.test(jQuery.trim(jQuery("#<?php echo self::$opt_hl; ?>").val()))))
                //                        {
                //                            alertmessage += "Please enter a valid 2-letter language code.";
                //                            jQuery("#boxdohl input").css("background-color", "#ffcccc").css("border", "2px solid #000000");
                //                            valid = false;
                //                        }
                //                    }

                if (!valid)
                {
                    alert(alertmessage);
                }
                return valid;
            }

            var prokeyval;
            var mydomain = escape("http://" + window.location.host.toString());

            jQuery(document).ready(function ($) {
                jQuery('#<?php echo self::$opt_defaultdims; ?>').change(function ()
                {
                    if (jQuery(this).is(":checked"))
                    {
                        jQuery("#boxdefaultdims").show(500);
                    }
                    else
                    {
                        jQuery("#boxdefaultdims").hide(500);
                    }

                });

                jQuery('#<?php echo self::$opt_gallery_customarrows; ?>').change(function ()
                {
                    if (jQuery(this).is(":checked"))
                    {
                        jQuery("#boxcustomarrows").show(500);
                    }
                    else
                    {
                        jQuery("#boxcustomarrows").hide(500);
                    }

                });

                jQuery('#<?php echo self::$opt_gallery_collapse_grid; ?>').change(function ()
                {
                    if (jQuery(this).is(":checked"))
                    {
                        jQuery("#box_collapse_grid").show(500);
                    }
                    else
                    {
                        jQuery("#box_collapse_grid").hide(500);
                    }
                });

                jQuery('#<?php echo self::$opt_gallery_channelsub; ?>').change(function ()
                {
                    if (jQuery(this).is(":checked"))
                    {
                        jQuery("#boxchannelsub").show(500);
                    }
                    else
                    {
                        jQuery("#boxchannelsub").hide(500);
                    }

                });

                jQuery('#<?php echo self::$opt_dynload; ?>').change(function ()
                {
                    if (jQuery(this).is(":checked"))
                    {
                        jQuery("#boxdyn").show(500);
                    }
                    else
                    {
                        jQuery("#boxdyn").hide(500);
                    }

                });

                jQuery('#<?php echo self::$opt_spdc; ?>').change(function ()
                {
                    if (jQuery(this).is(":checked"))
                    {
                        jQuery("#boxspdc").show(500);
                    }
                    else
                    {
                        jQuery("#boxspdc").hide(500);
                    }
                });


                jQuery('#<?php echo self::$opt_responsive; ?>').change(function ()
                {
                    if (jQuery(this).is(":checked"))
                    {
                        jQuery("#boxresponsive_all").show(500);
                    }
                    else
                    {
                        jQuery("#boxresponsive_all").hide(500);
                    }
                });



                jQuery('#<?php echo self::$opt_migrate; ?>').change(function ()
                {
                    if (jQuery(this).is(":checked"))
                    {
                        jQuery("#boxmigratelist").show(500);
                    }
                    else
                    {
                        jQuery("#boxmigratelist").hide(500);
                    }
                });



                jQuery('#<?php echo self::$opt_nocookie; ?>').change(function ()
                {
                    if (jQuery(this).is(":checked"))
                    {
                        jQuery("#boxnocookie").show(500);
                    }
                    else
                    {
                        jQuery("#boxnocookie").hide(500);
                    }

                });

                jQuery('#<?php echo self::$opt_schemaorg; ?>').change(function ()
                {
                    if (jQuery(this).is(":checked"))
                    {
                        jQuery("#boxschemaorg").show(500);
                    }
                    else
                    {
                        jQuery("#boxschemaorg").hide(500);
                    }
                });


                //                    jQuery('#<?php echo self::$opt_dohl; ?>').change(function()
                //                    {
                //                        if (jQuery(this).is(":checked"))
                //                        {
                //                            jQuery("#boxdohl").show(500);
                //                        }
                //                        else
                //                        {
                //                            jQuery("#boxdohl").hide(500);
                //                        }
                //
                //                    });



                jQuery('#<?php echo self::$opt_defaultvol; ?>').change(function ()
                {
                    if (jQuery(this).is(":checked"))
                    {
                        jQuery("#boxdefaultvol").show(500);
                    }
                    else
                    {
                        jQuery("#boxdefaultvol").hide(500);
                    }

                });

                var rangedetect = document.createElement("input");
                rangedetect.setAttribute("type", "range");
                var canrange = rangedetect.type !== "text";
                //canrange = false;
                if (canrange)
                {
                    $("input#vol").prop("type", "range").addClass("vol-range").on("input change", function () {
                        $('.vol-output').text($(this).val() > 0 ? $(this).val() + '%' : 'Mute');
                    });
                    $('.vol-output').css("display", "inline-block").text($("input#vol").val() > 0 ? $("input#vol").val() + '%' : 'Mute');
                    $('.vol-seeslider').show();
                    $('.vol-seetextbox').hide();
                }
                else
                {
                    $("input#vol").width(40);
                }


                jQuery('#boxspdc input.button').click(function () {
                    jQuery('#clearspdcloading').show();
                    jQuery('#clearspdcfailed').hide();
                    jQuery('#clearspdcsuccess').hide();

                    $clearbutton = jQuery(this);
                    $clearbutton.attr('disabled', 'disabled');

                    jQuery.ajax({
                        type: "post",
                        dataType: "json",
                        timeout: 30000,
                        url: _EPYTA_.wpajaxurl,
                        data: {action: 'my_embedplus_clearspdc'},
                        success: function (response) {
                            if (response.type == "success") {
                                jQuery("#clearspdcsuccess").show();
                            }
                            else {
                                jQuery("#clearspdcfailed").show();
                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            jQuery("#clearspdcfailed").show();
                        },
                        complete: function () {
                            jQuery('#clearspdcloading').hide();
                            $clearbutton.removeAttr('disabled');
                        }

                    });

                });




                jQuery("#showcase-validate").click(function () {
                    window.open("<?php echo self::$epbase . "/showcase-validate.aspx?prokey=" . self::$alloptions[self::$opt_pro] ?>" + "&domain=" + mydomain);
                });

                jQuery('#showprokey').click(function () {
                    jQuery('.submitpro').show(500);
                    return false;
                });

                jQuery('#prokeysubmit').click(function () {
                    jQuery(this).attr('disabled', 'disabled');
                    jQuery('#prokeyfailed').hide();
                    jQuery('#prokeysuccess').hide();
                    jQuery('#prokeyloading').show();
                    prokeyval = jQuery('#opt_pro').val();

                    var tempscript = document.createElement("script");
                    tempscript.src = "<?php echo self::$epbase ?>/dashboard/wordpress-pro-validatejp.aspx?simple=1&prokey=" + prokeyval + "&domain=" + mydomain;
                    var n = document.getElementsByTagName("head")[0].appendChild(tempscript);
                    setTimeout(function () {
                        n.parentNode.removeChild(n);
                    }, 500);
                    return false;
                });

                window.embedplus_record_prokey = function (good) {

                    jQuery.ajax({
                        type: "post",
                        dataType: "json",
                        timeout: 30000,
                        url: _EPYTA_.wpajaxurl,
                        data: {action: 'my_embedplus_pro_record', <?php echo self::$opt_pro; ?>: (good ? prokeyval : "")},
                        success: function (response) {
                            if (response.type == "success") {
                                jQuery("#prokeysuccess").show();
                            }
                            else {
                                jQuery("#prokeyfailed").show();
                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            jQuery('#prokeyfailed').show();
                        },
                        complete: function () {
                            jQuery('#prokeyloading').hide();
                            jQuery('#prokeysubmit').removeAttr('disabled');
                        }

                    });

                };


                window.embedplus_cancel_prokey = function () {

                    jQuery.ajax({
                        type: "post",
                        dataType: "json",
                        timeout: 30000,
                        url: _EPYTA_.wpajaxurl,
                        data: {action: 'my_embedplus_pro_record', <?php echo self::$opt_pro; ?>: ""},
                        success: function (response) {
                            jQuery("#prokeycancel").show();
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            jQuery('#prokeyfailed').show();
                        },
                        complete: function () {
                            jQuery('#prokeyloading').hide();
                            jQuery('#prokeysubmit').removeAttr('disabled');
                        }

                    });

                };

            });
        </script>
        <?php
        if (function_exists('add_thickbox'))
        {
            add_thickbox();
        }
    }

    public static function save_changes_button($submitted)
    {
        $button_label = 'Save Changes';
        if ($submitted)
        {
            $button_label = 'Changes Saved';
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    setTimeout(function () {
                        jQuery('input.ytprefs-submit').val('Save Changes');
                    }, 3000);
                });

            </script>
            <?php
        }
        ?>
        <p class="submit">
            <input type="submit" onclick="return savevalidate();" name="Submit" class="button-primary ytprefs-submit" value="<?php _e($button_label) ?>" />
            <em>If you're using a separate caching plugin and you do not see your changes after saving, you might want to reset your cache.</em>
        </p>
        <?php
    }

    public static function ytprefsscript()
    {
        $loggedin = current_user_can('edit_posts');
        if (!($loggedin && self::$alloptions[self::$opt_admin_off_scripts]))
        {
            wp_enqueue_style(
                    '__EPYT__style', plugins_url('styles/ytprefs' . self::$min . '.css', __FILE__)
            );
            $cols = floatval(self::$alloptions[self::$opt_gallery_columns]);
            $cols = $cols == 0 ? 3.0 : $cols;
            $colwidth = 100.0 / $cols;
            $custom_css = "
                .epyt-gallery-thumb {
                        width: " . round($colwidth, 3) . "%;
                }
                ";

            if (self::$alloptions[self::$opt_gallery_collapse_grid] == 1)
            {
                foreach (self::$alloptions[self::$opt_gallery_collapse_grid_breaks] as $idx => $bpts)
                {
                    $custom_css .= "
                         @media (min-width:" . $bpts['bp']['min'] . "px) and (max-width: " . $bpts['bp']['max'] . "px) {
                            .epyt-gallery-rowbreak {
                                display: none;
                            }
                            .epyt-gallery-allthumbs[class*=\"epyt-cols\"] .epyt-gallery-thumb {
                                width: " . round(100.0 / intval($bpts['cols']), 3) . "% !important;
                            }
                          }";
                }
            }

            wp_add_inline_style('__EPYT__style', $custom_css);


            if (!is_admin() && (isset(self::$alloptions[self::$opt_pro]) && strlen(trim(self::$alloptions[self::$opt_pro])) > 8))
            {
                wp_enqueue_style('__disptype__', plugins_url('scripts/lity' . self::$min . '.css', __FILE__));
                wp_enqueue_script('__dispload__', plugins_url('scripts/lity' . self::$min . '.js', __FILE__), array('jquery'));
            }

            wp_enqueue_script('__ytprefs__', plugins_url('scripts/ytprefs' . self::$min . '.js', __FILE__), array('jquery'));

            if (self::$alloptions[self::$opt_old_script_method] != 1)
            {
                $my_script_vars = array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'security' => wp_create_nonce('embedplus-nonce'),
                    'gallery_scrolloffset' => intval(self::$alloptions[self::$opt_gallery_scrolloffset]),
                    'eppathtoscripts' => plugins_url('scripts/', __FILE__),
                    'epresponsiveselector' => self::get_responsiveselector(),
                    'epdovol' => true,
                    'version' => self::$alloptions[self::$opt_version],
                    'evselector' => self::get_evselector(),
                    'stopMobileBuffer' => self::$alloptions[self::$opt_stop_mobile_buffer] == '1' ? true : false
                );

                if (isset(self::$alloptions[self::$opt_pro]) && strlen(trim(self::$alloptions[self::$opt_pro])) > 8 && isset(self::$alloptions[self::$opt_dashpre]) && self::$alloptions[self::$opt_dashpre] == '1')
                {
                    $my_script_vars['dshpre'] = true;
                }
                wp_localize_script('__ytprefs__', '_EPYT_', $my_script_vars);
            }

            ////////////////////// cloudflare accomodation
            //add_filter('script_loader_tag', array(get_class(), 'set_cfasync'), 10, 3);

            if (!is_admin() && (self::$alloptions[self::$opt_pro] && strlen(trim(self::$alloptions[self::$opt_pro])) > 0) && self::$alloptions[self::$opt_dynload] == 1)
            {
                wp_enqueue_style('__dyntype__', plugins_url('scripts/embdyn' . self::$min . '.css', __FILE__));
                wp_enqueue_script('__dynload__', plugins_url('scripts/embdyn' . self::$min . '.js', __FILE__), array('jquery'));
            }
        }
    }

    public static function set_cfasync($tag, $handle, $src)
    {
        if ('__ytprefs__' !== $handle)
        {
            return $tag;
        }
        return str_replace('<script', '<script data-cfasync="false" ', $tag);
    }

    public static function get_evselector()
    {
        $evselector = 'iframe.__youtube_prefs__[src], iframe[src*="youtube.com/embed/"], iframe[src*="youtube-nocookie.com/embed/"]';

        if (self::$alloptions[self::$opt_evselector_light] == 1)
        {
            $evselector = 'iframe.__youtube_prefs__[src]';
        }

        return $evselector;
    }

    public static function get_responsiveselector()
    {
        $responsiveselector = '[]';
        if (self::$alloptions[self::$opt_widgetfit] == 1)
        {
            $responsiveselector = '["iframe.__youtube_prefs_widget__"]';
        }
        if (self::$alloptions[self::$opt_responsive] == 1)
        {
            if (self::$alloptions[self::$opt_responsive_all] == 1)
            {
                $responsiveselector = '["iframe[src*=\'youtube.com\']","iframe[src*=\'youtube-nocookie.com\']","iframe[data-ep-src*=\'youtube.com\']","iframe[data-ep-src*=\'youtube-nocookie.com\']","iframe[data-ep-gallerysrc*=\'youtube.com\']"]';
            }
            else
            {
                $responsiveselector = '["iframe.__youtube_prefs__"]';
            }
        }
        return $responsiveselector;
    }

    public static function admin_enqueue_scripts()
    {
        wp_enqueue_style('embedplusyoutube', plugins_url() . '/youtube-embed-plus-pro/scripts/embedplus_mce' . self::$min . '.css');
        ////////////// add_action('wp_print_scripts', array(get_class(), 'output_scriptvars'));      
        wp_enqueue_script('__ytprefs_admin__', plugins_url('scripts/ytprefs-admin' . self::$min . '.js', __FILE__), array('jquery'), self::$version, false);
        $admin_script_vars = array(
            'wpajaxurl' => admin_url('admin-ajax.php'),
            'epblogwidth' => self::get_blogwidth(),
            'epprokey' => self::$alloptions[self::$opt_pro],
            'epbasesite' => self::$epbase,
            'epversion' => self::$version,
            'myytdefaults' => http_build_query(self::$alloptions),
            'eppluginadminurl' => admin_url('admin.php?page=youtube-my-preferences'),
            'dashpre' => intval(self::$alloptions[self::$opt_dashpre])
        );
        wp_localize_script('__ytprefs_admin__', '_EPYTA_', $admin_script_vars);


        if ((get_bloginfo('version') >= '3.3') && self::custom_admin_pointers_check())
        {
            add_action('admin_print_footer_scripts', array(get_class(), 'custom_admin_pointers_footer'));

            wp_enqueue_script('wp-pointer');
            wp_enqueue_style('wp-pointer');
        }

        if (self::$alloptions['glance'] == 1)
        {
            add_action('admin_print_footer_scripts', array(get_class(), 'glance_script'));
        }
    }

    public static function get_blogwidth()
    {
        $blogwidth = null;
        try
        {
            $embed_size_w = intval(get_option('embed_size_w'));

            global $content_width;
            if (empty($content_width))
            {
                $content_width = $GLOBALS['content_width'];
            }

            $blogwidth = $embed_size_w ? $embed_size_w : ($content_width ? $content_width : 450);
        }
        catch (Exception $ex)
        {
            
        }

        $blogwidth = preg_replace('/\D/', '', $blogwidth); //may have px

        return $blogwidth;
    }

}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//class start
class Add_new_tinymce_btn_Youtubeprefs_Pro
{

    public $btn_arr;
    public $js_file;

    /*
     * call the constructor and set class variables
     * From the constructor call the functions via wordpress action/filter
     */

    function __construct($seperator, $btn_name, $javascrip_location)
    {
        $this->btn_arr = array("Seperator" => $seperator, "Name" => $btn_name);
        $this->js_file = $javascrip_location;
        add_action('init', array($this, 'add_tinymce_button'));
        add_filter('tiny_mce_version', array($this, 'refresh_mce_version'));
    }

    /*
     * create the buttons only if the user has editing privs.
     * If so we create the button and add it to the tinymce button array
     */

    function add_tinymce_button()
    {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
            return;
        if (get_user_option('rich_editing') == 'true')
        {
            //the function that adds the javascript
            add_filter('mce_external_plugins', array($this, 'add_new_tinymce_plugin'));
            //adds the button to the tinymce button array
            add_filter('mce_buttons', array($this, 'register_new_button'));
        }
    }

    /*
     * add the new button to the tinymce array
     */

    function register_new_button($buttons)
    {
        array_push($buttons, $this->btn_arr["Seperator"], $this->btn_arr["Name"]);
        return $buttons;
    }

    /*
     * Call the javascript file that loads the
     * instructions for the new button
     */

    function add_new_tinymce_plugin($plugin_array)
    {
        $plugin_array[$this->btn_arr['Name']] = $this->js_file;
        return $plugin_array;
    }

    /*
     * This function tricks tinymce in thinking
     * it needs to refresh the buttons
     */

    function refresh_mce_version($ver)
    {
        $ver += 3;
        return $ver;
    }

}

//class end
$youtubeplgplus_pro = new YouTubePrefsPro();
require rtrim(dirname(__FILE__), "\\/") . '/plugin-update-checker/plugin-update-checker.php';
$myUpdateCheckerYouTubePro = Puc_v4_Factory::buildUpdateChecker(
                (strpos(YouTubePrefsPro::$epbase, 'http') === false ? 'https:' : '') . YouTubePrefsPro::$epbase . '/youtube-pro/update-checker/?prokey=' . YouTubePrefsPro::$alloptions[YouTubePrefsPro::$opt_pro], __FILE__);
