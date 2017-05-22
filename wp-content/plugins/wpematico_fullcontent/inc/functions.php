<?php
/** 
 *  @package WPeMatico Full Content
**/
if ( !defined('ABSPATH') ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * wpematico_load_custom_txt
 *
 * @since 2.4
 * @return void
 */
function wpematico_load_custom_txt() {
	$data =	$_POST['data'];
	if( ! wp_verify_nonce( $_POST['_wpnonce' ], 'nonce-'.basename($data) ) ) {		
		wp_send_json_error( array( 'error' => true, 'message' => __( 'File not found.', WPeMatico::TEXTDOMAIN ) ) );
	}
	if (is_writable($data)) {
		$content = @WPeMatico::wpematico_get_contents($data, false);
		if ( !$content )
			wp_send_json_error( array( 'error' => true, 'message' => __( 'Export location or file not readable', WPeMatico::TEXTDOMAIN ) ) );
		else{
			$ret = array( 'pathfilename'=> $data,  'filename'=> basename($data),  'textfile'=> $content, );
			wp_send_json_success($ret);
		}
	}else{
		wp_send_json_error( array( 'error' => true, 'message' => __( sprintf( 'ERROR: File "%s" not writeable.', basename($data) ), WPeMatico::TEXTDOMAIN ) ) );
	}
}
add_action( 'wp_ajax_wpematico_load_custom_txt', 'wpematico_load_custom_txt' );

class wpematico_falseitem {
	public $url;
	function __construct( $url ) {
		$this->url = $url;
	}
	function get_permalink(){
		return $this->url;
	}
	function get_title(){
		return '';
	}
}
class extratests  {
	function getthecontent($url=null){
		if (is_null($url)) return 'ERROR: url';
		$current_item = array('content'=>'','title'=>'','date'=>'','author'=>'');
		$item = new wpematico_falseitem($url);
		$campaign['campaign_usecurl']=true;
		$campaign['avoid_search_redirection']=true;  //test_url must be complete and no redirection
		$campaign['campaign_striphtml']=false;
		
		$campaign['campaign_fullmultipage']=true;
		$campaign['campaign_fulltitle']=true;
		$campaign['campaign_fulldate']=true;
		$campaign['campaign_fullauthor']=true;
		$campaign['campaign_fullcontent'] = true;
		$feed = '';
		add_filter('wpepro_getfullcontent', 'wpemfullcontent_getcontent',10,3 );
		
		error_reporting(E_ERROR | E_PARSE);		
		
		$html = wpemfullcontent_GetFullContent($current_item, $campaign, $feed, $item, true);
		$meta ='<div style="float:right; border: 1px solid black;margin: 0 0 10px;padding: 0 2px;">';
		$meta.='<span> Title: '.	$current_item['title'].'</span><br>';
		$meta.='<span>  Date: '. date_i18n( get_option('date_format'), $current_item['date'] ) .'</span><br>';
		$meta.='<span>Author: '.	$current_item['author'].'</span><br>';
		$meta.='</div>';
		$current_item['content']= $html;
		return $meta . $html;
	}
} // class

function wpematico_test_fullcontent() {
	if( ! wp_verify_nonce( $_POST['_wpnonce' ], 'wpematicopro-fullcontent' ) ) {		
		wp_send_json_error( array( 'error' => true, 'message' => __( 'Denied.', WPeMatico::TEXTDOMAIN ) ) );
	}
	check_admin_referer('wpematicopro-fullcontent');
	$url = esc_url(	trim($_POST['test_url']) );

//	error_reporting(-1);
//	ini_set('display_errors', '1');

	$test = new extratests();
	$html = $test->getthecontent($url);

	if (!is_null($html)) {
		if ( !is_string( $html ) )
			wp_send_json_error( array( 'error' => true, 'message' => __( 'There is no html in content.', WPeMatico::TEXTDOMAIN ) ) );
		else{
			$ret = array( 'htmlcontent'=> $html, );
			wp_send_json_success($ret);
		}
	}else{
		wp_send_json_error( array( 
			'error' => true, 
			'message' => __( sprintf( 'ERROR: Cannot read "%s" content.', "test_url" ), WPeMatico::TEXTDOMAIN ). '  '.
						 __( 'You can try with "autodetect_on_failure: yes" or search another @class/@id that envelopes the content.', WPeMatico::TEXTDOMAIN ) . '<br>'.
						 __( 'A good tip for this is use Firebug or Right Click -> "Inspect Element" on source URL.', WPeMatico::TEXTDOMAIN ) 
		));
	}
}
add_action( 'wp_ajax_wpematico_test_fullcontent', 'wpematico_test_fullcontent' );


add_action( 'wp_ajax_wpematico_movetouploads_fullcontent', 'wpematico_movetouploads_fullcontent' );
function wpematico_movetouploads_fullcontent() {
	if( ! wp_verify_nonce( $_POST['_wpnonce' ], 'wpematicopro-fullcontent' ) ) {		
		wp_send_json_error( array( 'error' => true, 'message' => __( 'File not found.', WPeMatico::TEXTDOMAIN ) ) );
	}
	check_admin_referer('wpematicopro-fullcontent');
	$src=wpematico_fullcontent_foldercreator();
	$ret=wpematico_fullcontent_foldercreator(true);
		
	if ( !is_dir( wpematico_fullcontent_foldercreator(false) ) ) {
		wp_send_json_error( array( 'error' => true, 'message' => __( 'There was an error creating directory.', WPeMatico::TEXTDOMAIN ) ) );
	}
	//copy_dir($src, $ret);
	$files = wscandir($src);
	$someerror = false;
	foreach ($files as $f) {
		if (is_dir($f)) {  //don't shows directories
		}elseif( !in_array(str_replace('.','',strrchr($f, '.')),explode(',','txt')) ) { //allowed extensions
	
		}else{
			$copy = copy($src.$f, $ret.$f);
			if(!$copy) {
				$someerror=true;
			}
		}
	}
	if ( $someerror ) {
		wp_send_json_error( array( 'error' => true, 'message' => __( 'There was an error copying files.', WPeMatico::TEXTDOMAIN ) ) );
	}else{
		if ($files===FALSE) {
			wp_send_json_error( array( 'error' => true, 'message' => __( 'Can\'t copy files!', WPeMatico::TEXTDOMAIN ) ) );
		}
	}
	//reload the page on return;
	wp_send_json_success( array('success'=>true) );
}


/**
 * @param string|bool $customdir Optional: If 'true' return new dir in uploads folder.  If not exist create and copy all files in source dir.<br>
 *						If 'false' return new dir in uploads folder, don't create if not exist.<br>
 *						If string return trailingslashit $customdir as is. <br>
 *						if null or !given return plugin original custom dir
 * @return string directory with trailingslash of custom txt config files for remote content extractor
 */
function wpematico_fullcontent_foldercreator($customdir = null) {
	$upload_dir = wp_upload_dir();
	$path_dst = '/inc/content-extractor/config/custom/';
	$src = trailingslashit(ABSPATH. PLUGINDIR).'wpematico_fullcontent' . $path_dst;
	$new_dst = 'wpematicopro/config/custom/';
								//	$config_dst_url = trailingslashit($upload_dir['url']). $path_dst;
	if( is_string($customdir) ) $ret = trailingslashit( $customdir );
	elseif(is_null($customdir)) $ret = $src;
	elseif(is_bool($customdir)) $ret = trailingslashit($upload_dir['basedir']).$new_dst;
	
	if( !is_dir($ret) && $customdir == true ) {
		$parts = explode('/', $ret);
		$file = array_pop($parts);
		$dir = '';
		foreach($parts as $part)   // don't use recursive creation to avoid php warnings
            if(!is_dir($dir .= "/$part")) mkdir($dir,0777);  
		
	}
	return $ret;
}

function wpematico_fullcontent_admin_head() {
?>
<style>
	a{color: #999; text-decoration:none;}
	a:hover{text-decoration:underline;}
	.addfile:before{content: "\271A";}
	.play:before{ content: "\25B6"; }
	.spinner {
		float: left;
		background-size: 12px;
		height: 12px;
		width: 12px;
		margin: 5px 5px 0 0;
	}
	#saving .spinner {
		float: none;
		background-size: 20px;
		height: 20px;
		width: 20px;
		margin: 3px;
	}
	#savemessage, #savingas {
		font-weight: bold;
	}
	#savemessage.notice-success {
		color: green;
	}
	#savemessage.notice-error, #savingas {
		color: red;
	}
	input{ border:0;font: 9pt Monospace,'Courier New'; }
	input:hover{ cursor: pointer; }
	#filename {
		font-weight: bold;
		cursor: default;
	}
	textarea[name="textfile"] { color:#fff;background-color:#111;border:0; font: 10pt Monospace,'Courier New'; }
	hr {border:1px solid #333;}
	
	#visual img {
		max-width: 100%;
		height: auto;
	}
/* Tabs
----------------------------------*/
.ui-tabs { position: relative; padding: .2em; zoom: 1; } /* position: relative prevents IE scroll bug (element with position: relative inside container with overflow: auto appear as "fixed") */
.ui-tabs .ui-tabs-nav { margin: 0; padding: .2em .2em 0; }
.ui-tabs .ui-tabs-nav li { list-style: none; float: left; position: relative; top: 1px; margin: 0 .2em 1px 0; border-bottom: 0 !important; padding: 0; white-space: nowrap; }
.ui-tabs .ui-tabs-nav li a { float: left; padding: .5em 1em; text-decoration: none; }
.ui-tabs .ui-tabs-nav li.ui-tabs-selected { margin-bottom: 0; padding-bottom: 1px; }
.ui-tabs .ui-tabs-nav li.ui-tabs-selected a, .ui-tabs .ui-tabs-nav li.ui-state-disabled a, .ui-tabs .ui-tabs-nav li.ui-state-processing a { cursor: text; }
.ui-tabs .ui-tabs-nav li a, .ui-tabs.ui-tabs-collapsible .ui-tabs-nav li.ui-tabs-selected a { cursor: pointer; } /* first selector in group seems obsolete, but required to overcome bug in Opera applying cursor: text overall if defined elsewhere... */
.ui-tabs .ui-tabs-panel { display: block; border-width: 0; padding: 1em 1.4em; background: none; }
.ui-tabs .ui-tabs-hide { display: none !important; }	
</style>
<script>
jQuery(document).ready(function($){	
	$("#testtabs").tabs();

	$( document.body ).on( 'click', '.fileonlist', function(e) {
		e.preventDefault();
		var submitButton = $(document.body).find( 'input[type="submit"]' );

		var data = $(this).attr('data');
		var nonce = $(this).attr('nonce');
		submitButton.addClass( 'button-disabled' );
		$('#statusmessage').hide();
		$('.fileonlist .spinner').remove();
		$('#visual').html('');
		$('#text').text('');
		
		$(this).prepend( '<span class="spinner is-active"></span>' );

		// start the process
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				data: data,
				_wpnonce: nonce,
				action: 'wpematico_load_custom_txt',
			},
			dataType: "json",
			success: function( response ) {
				response = response.data;
				if ( response.error ) {
					var error_message = response.message;
					console.log( error_message );
					$('#statusmessage').removeClass('notice-success').addClass('notice-error').html('<p><?php _e('There was an error loading file!', WPeMatico::TEXTDOMAIN ); ?></p>').show().delay(5000).fadeOut('slow');
					$('input[name="pathfilename"]').val('');
					$('input[name="filename"]').val(error_message);
					$('textarea[name="textfile"]').val('<?php _e('Select file to edit on right column.', WPeMatico::TEXTDOMAIN ); ?>');
					$('.fileonlist .spinner').remove();
				} else {
					$('input[name="pathfilename"]').val(response.pathfilename);
					$('input[name="filename"]').val(response.filename);
					$('textarea[name="textfile"]').val(response.textfile);
					$('.fileonlist .spinner').remove();
					$('#statusmessage').removeClass('notice-error').addClass('notice-success').html('<p><?php _e('File loaded!', WPeMatico::TEXTDOMAIN ); ?></p>').show().delay(5000).fadeOut('slow');
					submitButton.removeClass( 'button-disabled' );
				}
			}
		}).fail(function (response) {
			if ( window.console && window.console.log ) {
				console.log( response );
			}
		});
	});
	
	$( document.body ).on( 'click', '#wpematico-save-fullcontent', function(e) {
		e.preventDefault();
		var submitButton = $(document.body).find( 'input[type="submit"]' );

		if ( ! submitButton.hasClass( 'button-disabled' ) ) {

			submitButton.addClass( 'button-disabled' );
			$('#saving .spinner').remove();
			$('#statusmessage').hide();
			$('#saving').append( '<span class="spinner is-active"></span>' );

			// start the process
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					pathfilename: $('input[name="pathfilename"]').val(),
					filename: 'NO', //$('input[name="filename"]').val(),
					textfile: $('textarea[name="textfile"]').val(),
					_wpnonce: $('input[name="_wpnonce"]').val(),
					_wp_http_referer: $('input[name="_wp_http_referer"]').val(),
					action: 'wpematico_save_fullcontent',
				},
				dataType: "json",
				success: function( response ) {
					response = response.data;
					//$class .= ($mess['below-h2']) ? " below-h2" : "";
					if ( response.error ) {
						var error_message = response.message;
						console.log( 'ERROR: '+error_message );
						$('#savemessage').removeClass('notice-success').addClass('notice-error').html(error_message).show().delay(5000).fadeOut('slow');
						$('#saving .spinner').remove();
					} else {
						$('input[name="pathfilename"]').val(response.pathfilename);
						$('input[name="filename"]').val(response.filename);
						$('#savemessage').removeClass('notice-error').addClass('notice-success').html('<?php _e('File saved!', WPeMatico::TEXTDOMAIN ); ?>').show().delay(5000).fadeOut('slow');
					}
					$('#saving .spinner').remove();
					submitButton.removeClass( 'button-disabled' );
				}
			}).fail(function (response) {
				if ( window.console && window.console.log ) {
					console.log( response );
				}
			});
		}

	});
	
	$( document.body ).on( 'click', '.addfile', function(e) {
		//e.preventDefault();
		var submitButton = $(document.body).find( 'input[type="submit"]' );

		submitButton.addClass( 'button-disabled' );
		$('#saving .spinner').remove();
		$('#statusmessage').hide();
		$('#visual').html('');
		$('#text').text('');

		if($('#filename').attr('readonly')=='readonly' ){
			$('#savingas').html('<?php _e('Type new domain name (without ".txt") and click "Save as" again!', WPeMatico::TEXTDOMAIN ); ?>').show();
			$(this).attr('title','<?php _e('Type new domain name and click this button!', WPeMatico::TEXTDOMAIN ); ?>').show();
			$('#filename').removeAttr('readonly');
			$('#filename').focus().select();
		}else{
			$('#savingas').html('<?php _e('Wait... checking domain and saving...', WPeMatico::TEXTDOMAIN ); ?>');
			$(this).attr('title','');
			$('#filename').attr('readonly','readonly');
			$('#saving').append( '<span class="spinner is-active"></span>' );

			// start the process
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					pathfilename: $('input[name="pathfilename"]').val(),
					filename: $('input[name="filename"]').val(),  // new file
					textfile: $('textarea[name="textfile"]').val(),
					_wpnonce: $('input[name="_wpnonce"]').val(),
					_wp_http_referer: $('input[name="_wp_http_referer"]').val(),
					action: 'wpematico_save_fullcontent',
				},
				dataType: "json",
				success: function( response ) {
					response = response.data;
					//$class .= ($mess['below-h2']) ? " below-h2" : "";
					if ( response.error ) {
						var error_message = response.message;
						console.log( 'ERROR: '+error_message );
						$('#savingas').html('ERROR: '+error_message).show().delay(5000).fadeOut('slow');
						$('#saving .spinner').remove();
					} else {
						$('input[name="pathfilename"]').val(response.pathfilename);
						$('input[name="filename"]').val(response.filename);
						$('#savingas').html('<?php _e('File saved!', WPeMatico::TEXTDOMAIN ); ?>').show().delay(5000).fadeOut('slow');
						submitButton.removeClass( 'button-disabled' );
						location.reload();
					}
					$('#saving .spinner').remove();
				}
			}).fail(function (response) {
				$('#savingas').html('ERROR: <?php _e('Something goes wrong! See console!', WPeMatico::TEXTDOMAIN ); ?>').show().delay(3000).fadeOut('slow');
				if ( window.console && window.console.log ) {
					console.log( response );
				}
			});
		}
	});

	$( document.body ).on( 'click', '.movetouploads', function(e) {
		e.preventDefault();
		var submitButton = $(document.body).find( 'input[type="submit"]' );

		submitButton.addClass( 'button-disabled' );
		$('#saving .spinner').remove();
		$('#statusmessage').hide();
		$('#visual').html('');
		$('#text').text('');

		$(this).addClass( 'button-primary-disabled' );
		$(this).after( '<span class="spinner is-active"></span>' );

		// start the process
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				_wpnonce: $('input[name="_wpnonce"]').val(),
				_wp_http_referer: $('input[name="_wp_http_referer"]').val(),
				action: 'wpematico_movetouploads_fullcontent',
			},
			dataType: "json",
			success: function( response ) {
				response = response.data;
				if ( response.error ) {
					var error_message = response.message;
					console.log( 'ERROR: '+error_message );
					$('h2.nav-tab-wrapper').after('<div class="notice notice-error is-dismissible"><p>'+error_message+'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.', WPeMatico::TEXTDOMAIN ); ?></span></button></div>');
					$('.spinner').remove();
				} else {
					$('input[name="pathfilename"]').val(response.pathfilename);
					$('input[name="filename"]').val(response.filename);
					$('h2.nav-tab-wrapper').after('<div class="notice notice-success is-dismissible"><p><?php _e('Files Moved!', WPeMatico::TEXTDOMAIN ); ?> <?php _e('Reload the page before continue.', WPeMatico::TEXTDOMAIN ); ?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.', WPeMatico::TEXTDOMAIN ); ?></span></button></div>');
					$('.movetouploads').remove();
					submitButton.removeClass( 'button-disabled' );
					location.reload();
				}
				$('.spinner').remove();
			}
		}).fail(function (response) {
			$('h2.nav-tab-wrapper').after('<div class="notice notice-error is-dismissible"><p><?php _e('Something goes wrong!', WPeMatico::TEXTDOMAIN ); ?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.', WPeMatico::TEXTDOMAIN ); ?></span></button></div>');			
			if ( window.console && window.console.log ) {
				console.log( response );
			}
		});

	});
	
	$( document.body ).on( 'click', '.preview_txt', function(e) {
		//parse textarea and get test url
		var lines = $('textarea[name="textfile"]').val().replace(/\r\n/g, "\n").split("\n");
		var i = lines.length;
		while (i--) {
			if (lines[i].toLowerCase().indexOf("test_url") >= 0) {
				var test_url=lines[i].substring(9);
				break;
			}
		}
		//alert(test_url);
		$('#statusmessage').hide();
		$('.spinner').remove();
		$('#saving').append( '<span class="spinner is-active"></span>' );
		$('#savemessage').removeClass('notice-error').addClass('notice-success').html('<?php _e('Testing test_url... wait...', WPeMatico::TEXTDOMAIN ); ?>').show().delay(5000).fadeOut('slow');
		$('#visual').html('');
		$('#text').text('');
		
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				test_url: test_url,
				_wpnonce: $('input[name="_wpnonce"]').val(),
				_wp_http_referer: $('input[name="_wp_http_referer"]').val(),
				action: 'wpematico_test_fullcontent',
			},
			dataType: "json",
			success: function( response ) {
				response = response.data;
				//$class .= ($mess['below-h2']) ? " below-h2" : "";
				if ( response.error ) {
					var error_message = response.message;
					console.log( 'ERROR: '+error_message );
					$('.spinner').remove();
					//$('#savemessage').removeClass('notice-success').addClass('notice-error').html(error_message).show().delay(15000).fadeOut('slow');
					$('#statusmessage').removeClass('notice-success').addClass('notice-error').html('<p>'+error_message+'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.', WPeMatico::TEXTDOMAIN ); ?></span></button>').show();
				} else {
					$('#visual').html(response.htmlcontent);
					$('#text').text(response.htmlcontent);
					$('#savemessage').removeClass('notice-error').addClass('notice-success').html('<?php _e('Tested. See results below.', WPeMatico::TEXTDOMAIN ); ?>').show().delay(5000).fadeOut('slow');
					//$('#savingas').html('').fadeOut('slow');
					$('.spinner').remove();
				}
			}
		}).fail(function (response) {
			$('#savemessage').removeClass('notice-success').addClass('notice-error').html('ERROR: <?php _e('Something goes wrong! See console!', WPeMatico::TEXTDOMAIN ); ?>').show().delay(5000).fadeOut('slow');
			if ( window.console && window.console.log ) {
				console.log( response );
			}
			$('.spinner').remove();
		});
	});
	
	$( document.body ).on( 'click', '.preview_uri', function(e) {
		var test_url = $('#single_uri').val();
		//alert(test_url);
		$('#statusmessage').hide();
		$('.spinner').remove();
		$('#saving').append( '<span class="spinner is-active"></span>' );
		$('#savemessage').removeClass('notice-error').addClass('notice-success').html('<?php _e('Testing test_url... wait...', WPeMatico::TEXTDOMAIN ); ?>').show().delay(5000).fadeOut('slow');
		$('#visual').html('');
		$('#text').text('');
		
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				test_url: test_url,
				_wpnonce: $('input[name="_wpnonce"]').val(),
				_wp_http_referer: $('input[name="_wp_http_referer"]').val(),
				action: 'wpematico_test_fullcontent',
			},
			dataType: "json",
			success: function( response ) {
				response = response.data;
				//$class .= ($mess['below-h2']) ? " below-h2" : "";
				if ( response.error ) {
					var error_message = response.message;
					console.log( 'ERROR: '+error_message );
					$('.spinner').remove();
					//$('#savemessage').removeClass('notice-success').addClass('notice-error').html(error_message).show().delay(15000).fadeOut('slow');
					$('#statusmessage').removeClass('notice-success').addClass('notice-error').html('<p>'+error_message+'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.', WPeMatico::TEXTDOMAIN ); ?></span></button>').show();
				} else {
					$('#visual').html(response.htmlcontent);
					$('#text').text(response.htmlcontent);
					$('#savemessage').removeClass('notice-error').addClass('notice-success').html('<?php _e('Tested. See results below.', WPeMatico::TEXTDOMAIN ); ?>').show().delay(5000).fadeOut('slow');
					//$('#savingas').html('').fadeOut('slow');
					$('.spinner').remove();
				}
			}
		}).fail(function (response) {
			$('#savemessage').removeClass('notice-success').addClass('notice-error').html('ERROR: <?php _e('Something goes wrong! See console!', WPeMatico::TEXTDOMAIN ); ?>').show().delay(5000).fadeOut('slow');
			if ( window.console && window.console.log ) {
				console.log( response );
			}
			$('.spinner').remove();
		});
	});
	
});
</script><?php
}


function wscandir($cwdir) {
	if(function_exists("scandir")) {
		return scandir($cwdir);
	} else {
		$cwdh  = opendir($cwdir);
		while (false !== ($filename = readdir($cwdh)))
				$files[] = $filename;
		return $files;
	}
}

function get_current_os() {
	$current_os = '';
	if(strtoupper(substr(PHP_OS, 0, 3) ) == "WIN") {
		$current_os = 'win';
	} else {
		$current_os = 'nix';
	}
	return $current_os;
}
add_action( 'admin_post_full_filelist_upload_action', 'full_filelist_upload_action');
function full_filelist_upload_action() {
	if ( !wp_verify_nonce($_POST['full_wpnonce'], 'full_filelist_upload' ) ) {
		 wp_die(__( 'Security check', WPeMatico::TEXTDOMAIN )); 
	}
	$full_path_custom = plugin_dir_path( __FILE__ ). 'content-extractor/config/custom/';
	if( in_array(str_replace('.','',strrchr($_FILES['f']['name'], '.')),explode(',','txt') ) ) {
		if(!@move_uploaded_file($_FILES['f']['tmp_name'], $full_path_custom.$_FILES['f']['name'])) {
			WPeMatico::add_wp_notice( array('text' => __("** Can't upload!",  WPeMatico::TEXTDOMAIN ), 'below-h2'=>false, 'error' => true ) );
		}
				
	} else {
		WPeMatico::add_wp_notice( array('text' => __("** Can't upload! Just .txt files allowed!",  WPeMatico::TEXTDOMAIN ), 'below-h2'=>false, 'error' => true ) );
	}
	wp_redirect($_POST['_wp_http_referer']);
	exit;

}
function wpematico_filelist() {
	
	$current_os = get_current_os();

	$full_path_home = plugin_dir_path( __FILE__ ). 'content-extractor/config/custom/';
	if(!fullcontent_is_folder_exist(false))  {
		chdir( wpematico_fullcontent_foldercreator()); // source dir
	}
	else {
		chdir( wpematico_fullcontent_foldercreator(false));  // custom dir
	}
	
	$full_path_custom = plugin_dir_path( __FILE__ ). 'content-extractor/config/custom/';


	echo $current_os.' '.__('Path', WPeMatico::TEXTDOMAIN ).':'.htmlspecialchars($full_path_custom);
	echo '<input type="hidden" name="c" value="'.htmlspecialchars($full_path_custom).'"/><hr/>';
	
	if (!is_writable($full_path_custom)) {
		echo '<font color=red>'.__('(Not writable)', WPeMatico::TEXTDOMAIN ).'</font><br/>';
	}
	
	$ls = wscandir($full_path_custom);
	foreach ($ls as $f) {
		if (is_dir($f)) {  //don't shows directories
		}elseif( !in_array(str_replace('.','',strrchr($f, '.')),explode(',','txt')) ) { //allowed extensions
			
		}else {
			echo "<a class='fileonlist' href=# nonce='".wp_create_nonce( 'nonce-' . $f )."' data='".$full_path_custom.$f."'>";
			if (is_writable($full_path_custom.$f)) {
				echo "<font title='".__("Click to edit",  WPeMatico::TEXTDOMAIN )."'  color=green>".$f."</font>";
			} else {
				echo "<font title='".__("(Not writable)",  WPeMatico::TEXTDOMAIN )."' color=#999>".$f."</font>";
			}
			echo "</a><br />";
		}
	}

	echo '<hr>
		<form method="post" enctype="multipart/form-data" action="'.admin_url( 'admin-post.php' ).'">
			<input type="hidden" name="c" value="'. $full_path_custom .'"/>
			<input type="hidden" name="action" value="full_filelist_upload_action"/>';
			wp_nonce_field('full_filelist_upload', 'full_wpnonce');
	echo 'Upload .txt file: <input type="file" name="f" style="overflow: hidden; width: 85%;"/>
	<input type="submit" value=">>"></form>';

}

add_filter('wpematico_sysinfo_after_wpematico_config', 'full_debug_data', 4);
function full_debug_data($return) {
    // WPeMatico Full Content configuration
  
    $return .= "\n" . '-- WPeMatico Full Content Configuration' . "\n\n";
    $return .= 'Version:                  ' . WPEFULLCONTENT_VERSION . "\n";

    $plugins_args = array();
	$plugins_args = apply_filters('wpematico_plugins_updater_args', $plugins_args);
	$plugin_args_name = 'fullcontent';
	$args_plugin = $plugins_args[$plugin_args_name];
	$license = wpematico_licenses_handlers::get_key($plugin_args_name);
	$license_status = wpematico_licenses_handlers::get_license_status($plugin_args_name);
	$expire_license = 'No expiration';
	if ($license != false) {		
		$args_check = array(
			'license' 	=> $license,
			'item_name' => urlencode($args_plugin['api_data']['item_name']),
			'url'       => home_url(),
			'version' 	=> $args_plugin['api_data']['version'],
			'author' 	=> 'Esteban Truelsegaard'	
		);
		$api_url = $args_plugin['api_url'];
		$license_data = wpematico_licenses_handlers::check_license($api_url, $args_check);
		if (is_object($license_data)) {
						
			$expires = $license_data->expires;
			$expires = substr( $expires, 0, strpos( $expires, " "));
						
			if (!empty($license_data->payment_id) && !empty($license_data->license_limit)) {
				$expire_license = $expires;
			}
		}
	}

	if ($license_status == false) {
		$license_status = 'No license';
	}
    $return .= 'License Status:           ' . $license_status . "\n";
    $return .= 'License Expiration:       ' . $expire_license . "\n";
    return $return;
}
