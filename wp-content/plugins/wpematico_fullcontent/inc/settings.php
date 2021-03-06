<?php
if ( !defined('ABSPATH') ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

function wpematicopro_fullcontent_tab($tabs) {
	$tabs['fullcontent'] = __( 'Full Content', 'wpematico' );
	return $tabs;
}
add_filter( 'wpematico_settings_tabs',  'wpematicopro_fullcontent_tab');


add_action( 'wpematico_settings_tab_fullcontent', 'wpematico_fullcontent_form' );
function wpematico_fullcontent_form(){
	?>
		<div id="poststuff" class="has-right-sidebar">
			<div id="side-info-column" class="inner-sidebar">
				<div id="side-sortables" class="meta-box-sortables ui-sortable">
				<div class="postbox">
				<h3 class="handle"><?php _e( 'Config Files on Folder', 'wpematico' );?></h3>
				<div class="inside">
					
					<?php if(!fullcontent_is_folder_exist()) {
						// copy folder to uploads
						?><a class="button centered movetouploads button-primary"> <?php _e('Move to Uploads', 'wpematico' ); ?></a><?php
					}
					?><p><?php // show files
					wpematico_filelist();
					?>
					</p>
				</div>
				</div>
				<div class="postbox">
				<h3 class="handle"><?php _e( 'About', 'wpematico' );?></h3>
				<div class="inside">
					<p id="left1" style="text-align:center; background-color: rgb(255, 255, 255); background-position: initial initial; background-repeat: initial initial; ">
						<a href="https://etruel.com/downloads/wpematico-full-content/" target="_Blank" title="Go to etruel WebSite">
							<img style="background: transparent;border-radius: 15px;" src="<?php echo home_url(PLUGINDIR .'/'. basename(WPEFULLCONTENT_PATH)) ?>/assets/wpemfc250.jpg" title="">
						</a>
						<br />
						<?php echo WPEFULLCONTENT_ITEM_NAME ; ?> <?php echo WPEFULLCONTENT_VERSION ; ?>
					</p>
					<p><?php _e( 'Thanks for use and enjoy this plugin.', 'wpematico' );?></p>
					<p><?php _e( 'If you like it and want to thank, you can write a 5 star review on Wordpress.', 'wpematico' );?></p>
					<style type="text/css">#linkrate:before { content: "\2605\2605\2605\2605\2605";font-size: 18px;}
					#linkrate { font-size: 18px;}</style>
					<p style="text-align: center;">
						<a href="https://wordpress.org/support/view/plugin-reviews/wpematico?filter=5&rate=5#postform" id="linkrate" class="button" target="_Blank" title="Click here to rate plugin on Wordpress">  Rate</a>
					</p>
					<p></p>
				</div>
				</div>
				<?php //do_action('wpematico_wp_ratings'); ?>
				</div>
			</div>
			<div id="post-body">
				<div id="post-body-content">
					<div id="normal-sortables" class="meta-box-sortables ui-sortable">
						<div class="postbox inside">
							<div style="height:52px;"><h3 id="statusmessage" class="notice"></h3></div>
							<div class="inside">
								<form method="post" action="">
									<p>
									<?php wp_nonce_field('wpematicopro-fullcontent'); ?>
									<input type="hidden" name="pathfilename" value=" " />
									<label style="display: flex;">Domain:&nbsp;<input type="text" class="large-text" readonly="readonly" name="filename" id="filename" value=" " /><a class="button addfile"> Save as</a></label>
									<label id="savingas"></label>
									<textarea name="textfile" style="width:100%;height:300px;"><?php _e('Select file to edit on right column.', 'wpematico' ); ?></textarea><br />

									<span id="saving">
										<?php submit_button( __( 'Save file', 'wpematico' ), 'primary button-disabled', 'wpematico-save-fullcontent', false ); ?>
											<span id="savemessage" class="notice-success"></span>
									</span>
									<a class="button play preview_txt right"> Preview</a> 
									</p>
								</form>
							</div>
						</div>
						<div class="postbox inside">
							<h3>Test single URL</h3>
							<div id="testurl" class="inside" style="">
								<label style="display: flex;"><?php _e('URL', 'wpematico' ); ?>:&nbsp;<input type="text" class="large-text" name="single_uri" id="single_uri" value="" /></label>
								<span id="" class="description"><?php _e('Test the html code obtained and parsed as is from a single URL.', 'wpematico' ); ?></span>
								<a class="button play preview_uri right"> Preview</a>
								<div class="clear"></div>
							</div>
						</div>
						<div class="postbox inside">
							<div id="testtabs" class="hiddenn" style="">
								<ul class="tabNavigation"><h3 id="" class="" style="float: left;"><?php _e('Sample html code obtained and parsed from test_url', 'wpematico' ); ?></h3>
									<li style="float: right;"><a href="#visual"><?php _e( 'Rich text' ); ?></a></li>
									<li style="float: right;"><a href="#text"><?php _e( 'HTML' ); ?></a></li>
								</ul><div class="clear"></div>								
								<div id="visual">
								
								</div>
								<div id="text">
									
								</div>
								<div class="clear"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
}


/**
 * Help in config tab
 */
add_action('admin_init', 'wpematico_fullcontent_cfghelp');
function wpematico_fullcontent_cfghelp(){
	if ( ( isset( $_GET['page'] ) && $_GET['page'] == 'wpematico_settings' ) && 
			( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'wpematico' ) &&
			( isset( $_GET['tab'] ) && $_GET['tab'] == 'fullcontent' ) ) {
		
		if( !fullcontent_is_folder_exist() ) add_filter ('admin_notices','wpefullcontent_folder_notice');
		
		wp_enqueue_script( 'jquery-ui-tabs' );
		//wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
		add_action('admin_head', 'wpematico_fullcontent_admin_head' );  // en functions
	    
		$screen = WP_Screen::get('wpematico_page_wpematico_settings ');
		$screen->add_help_tab( array(
			'id'	=> 'some_tips',
			'title'	=> __('Help and Tips'),
			'content'=> '<h2>' . __( 'How to\'s Full content Addon.' ) . '</h2>',
			'callback'=> 'wpematico_fullcontent_howtos',
		) );
		$screen->add_help_tab( array(
			'id'	=> 'editor_commands',
			'title'	=> __('Command reference'),
			'content'=> '<p>' . __( 'These commands are allowed in text file to get full content.' ) . '</p>',
			'callback'=> 'wpematico_fullcontent_commandshelp',
		) );
		$screen->add_help_tab( array(
			'id'	=> 'xpath_tutorial',
			'title'	=> __('XPath Tutorial'),
			'content'=> '<p>' . __( 'XPath as filesystem addressing.' ) . '</p>',
			'callback'=> 'wpematico_fullcontent_xpathtutorial',
		) );
/*		$screen->add_help_tab( array(
			'id'	=> 'editor_README',
			'title'	=> __('About README.txt'),
			'content'=> '<p>' . __( 'This content is the help file included in WPeMatico Full Contentcontent-extractor.' ) . '</p>',
			'callback'=> 'wpematico_fullcontent_readmehelp',
		) );
*/
	}
}


function wpematico_fullcontent_readmehelp(){
	$path_dst = '/content-extractor/config/README.txt';
	$readme = __DIR__ . $path_dst;
	$content = @WPeMatico::wpematico_get_contents($readme, false);
	echo '<div style="height:250px;">'.nl2br($content, true).'</div>';
}
function wpematico_fullcontent_howtos(){
	?><style type="text/css">
	span.localLink {text-decoration: underline;font-weight: bold;font-size: 1.1em;}
	div.xpath {margin-left:3em; font-weight:bold}
	div.contentsDescription {margin-left:1em; font-style:italic}
</style> 
    <div style="margin-top:1ex">
		<p>Full Content Add-on works for almost all sites but If you see that can’t gets automatically the content from a website, you will need a configuration file to get full content.<br />
		In this page you can see and set the configuration files to get it to work with a 99% of the websites.<br/>
		Use the fields below to edit or upload the files.
		</p>
	</div>
    <div style="margin-top:1ex">
		<span class="localLink">Config Files on Folder.</span> 
	</div>
    <div class="contentsDescription">Check where resides your configurations files. 
        Recommended: <div class="xpath">/wp-content/uploads/wpematicopro/config/custom/</div>
	</div>
	The browse button allows to upload new txt files.  You must follow the xpath rules.  See previous files to get examples and also the Command Reference Help.
    <div class="contentsDescription">
        See more instructions on the FAQs: 
		<div class="xpath"><a target="_blank" href="https://etruel.com/question/cant-get-full-content-full-content-add/">Using the editor of configuration files.</a></div>
		<div class="xpath"><a target="_blank" href="https://etruel.com/question/pull-content-source/">How to pull more content from source ?</a></div>
    </div>
    <div style="margin-top:1ex">
		<span class="localLink">Test a single URL.</span> 
	</div>
    <div class="contentsDescription">
		You can make some tests to see what you can get from an URL, with or without a configuration file from that domain.<br/>
		Just type the URL in the input field and click on the Preview button below the field.
    </div>
    <div style="margin-top:1ex">
		<span class="localLink">Editing a file.</span> 
	</div>
    <div class="contentsDescription">Click on the file name at the right to load the file and remember click on Save file to don't lose the changes.<br/>
		When you click the Preview button (the first one, below the text area) the plugin get the content of the URL from the field 'test_url' and will shows you the results below in the preview area.
    </div>
	<?php
}

function wpematico_fullcontent_xpathtutorial(){
	?><style type="text/css">
	span.localLink {text-decoration: underline;font-weight: bold;font-size: 1.1em;}
	div.xpath {margin-left:3em; font-weight:bold}
	div.contentsDescription {margin-left:1em; font-style:italic}
</style> 
<div style="height:300px;">
	<h2 id="entry_title">XPath 1.0 Tutorial</h2>
	<div class="description">
    <div style="margin-top:1ex"><span class="localLink Pages">XPath as filesystem addressing</span> </div>
    <div class="contentsDescription">The basic XPath syntax is similar to filesystem addressing. If the path starts with the slash / , then it represents an absolute path to the required element.
        <div class="xpath">/AAA</div>
        <div class="xpath">/AAA/CCC</div>
        <div class="xpath">/AAA/DDD/BBB</div>
    </div>
    <div style="margin-top:1ex"><span class="localLink Pages">Start with //</span> </div>
    <div class="contentsDescription">If the path starts with // then all elements in the document which fulfill following criteria are selected.
        <div class="xpath">//BBB</div>
        <div class="xpath">//DDD/BBB</div>
    </div>
    <div style="margin-top:1ex"><span class="localLink Pages">All elements: *</span> </div>
    <div class="contentsDescription">The star * selects all elements located by preceeding path
        <div class="xpath">/AAA/CCC/DDD/*</div>
        <div class="xpath">/*/*/*/BBB</div>
        <div class="xpath">//*</div>
    </div>
    <div style="margin-top:1ex"><span class="localLink Pages">Further conditions inside []</span> </div>
    <div class="contentsDescription">Expresion in square brackets can further specify an element. A number in the brackets gives the position of the element in the selected set. The function last() selects the last element in the selection.
        <div class="xpath">/AAA/BBB[1]</div>
        <div class="xpath">/AAA/BBB[last()]</div>
    </div>
    <div style="margin-top:1ex"><span class="localLink Pages">Attributes</span> </div>
    <div class="contentsDescription">Attributes are specified by @ prefix.
        <div class="xpath">//@id</div>
        <div class="xpath">//BBB[@id]</div>
        <div class="xpath">//BBB[@name]</div>
        <div class="xpath">//BBB[@*]</div>
        <div class="xpath">//BBB[not(@*)]</div>
    </div>
    <div style="margin-top:1ex"><span class="localLink Pages">Attribute values</span> </div>
    <div class="contentsDescription">Values of attributes can be used as selection criteria. Function normalize-space removes leading and trailing spaces and replaces sequences of whitespace characters by a single space.
        <div class="xpath">//BBB[@id='b1']</div>
        <div class="xpath">//BBB[@name='bbb']</div>
        <div class="xpath">//BBB[normalize-space(@name)='bbb']</div>
    </div>
    <div style="margin-top:1ex"><span class="localLink Pages">Nodes counting</span> </div>
    <div class="contentsDescription">Function count() counts the number of selected elements
        <div class="xpath">//*[count(BBB)=2]</div>
        <div class="xpath">//*[count(*)=2]</div>
        <div class="xpath">//*[count(*)=3]</div>
    </div>
    <div style="margin-top:1ex"><span class="localLink Pages">Playing with names of selected elements</span> </div>
    <div class="contentsDescription">Function name() returns name of the element, the starts-with function returns true if the first argument string starts with the second argument string, and the contains function returns true if the first argument string contains the second argument string.
        <div class="xpath">//*[name()='BBB']</div>
        <div class="xpath">//*[starts-with(name(),'B')]</div>
        <div class="xpath">//*[contains(name(),'C')]</div>
    </div>
    <div style="margin-top:1ex"><span class="localLink Pages">Length of string</span> </div>
    <div class="contentsDescription">The string-length function returns the number of characters in the string. You must use &amp;lt; as a substitute for &lt; and &amp;gt; as a substitute for &gt; .
        <div class="xpath">//*[string-length(name()) = 3]</div>
        <div class="xpath">//*[string-length(name()) &lt; 3]</div>
        <div class="xpath">//*[string-length(name()) &gt; 3]</div>
    </div>
    <div style="margin-top:1ex"><span class="localLink Pages">Combining XPaths with |</span> </div>
    <div class="contentsDescription">Several paths can be combined with | separator.
        <div class="xpath">//CCC | //BBB</div>
        <div class="xpath">/AAA/EEE | //BBB</div>
        <div class="xpath">/AAA/EEE | //DDD/CCC | /AAA | //BBB</div>
    </div>
    <div style="margin-top:1ex"><span class="localLink Pages">Child axis</span> </div>
    <div class="contentsDescription">The child axis contains the children of the context node. The child axis is the default axis and it can be omitted.
        <div class="xpath">/AAA</div>
        <div class="xpath">/child::AAA</div>
        <div class="xpath">/AAA/BBB</div>
        <div class="xpath">/child::AAA/child::BBB</div>
        <div class="xpath">/child::AAA/BBB</div>
    </div>
    <div style="margin-top:1ex"><span class="localLink Pages">Descendant axis</span> </div>
    <div class="contentsDescription">The descendant axis contains the descendants of the context node; a descendant is a child or a child of a child and so on; thus the descendant axis never contains attribute or namespace nodes
        <div class="xpath">/descendant::*</div>
        <div class="xpath">/AAA/BBB/descendant::*</div>
        <div class="xpath">//CCC/descendant::*</div>
        <div class="xpath">//CCC/descendant::DDD</div>
    </div>
    <div style="margin-top:1ex"><span class="localLink Pages">Parent axis</span> </div>
    <div class="contentsDescription">The parent axis contains the parent of the context node, if there is one.
        <div class="xpath">//DDD/parent::*</div>
    </div>
    <div style="margin-top:1ex"><span class="localLink Pages">Ancestor axis</span> </div>
    <div class="contentsDescription">The ancestor axis contains the ancestors of the context node; the ancestors of the context node consist of the parent of context node and the parent's parent and so on; thus, the ancestor axis will always include the root node, unless the context node is the root node.
        <div class="xpath">/AAA/BBB/DDD/CCC/EEE/ancestor::*</div>
        <div class="xpath">//FFF/ancestor::*</div>
    </div>
    <div style="margin-top:1ex"><span class="localLink Pages">Following-sibling axis</span> </div>
    <div class="contentsDescription">The following-sibling axis contains all the following siblings of the context node.
        <div class="xpath">/AAA/BBB/following-sibling::*</div>
        <div class="xpath">//CCC/following-sibling::*</div>
    </div>
    <div style="margin-top:1ex"><span class="localLink Pages">Preceding-sibling axis</span> </div>
    <div class="contentsDescription">The preceding-sibling axis contains all the preceding siblings of the context node
        <div class="xpath">/AAA/XXX/preceding-sibling::*</div>
        <div class="xpath">//CCC/preceding-sibling::*</div>
    </div>
    <div style="margin-top:1ex"><span class="localLink Pages">Following axis</span> </div>
    <div class="contentsDescription">The following axis contains all nodes in the same document as the context node that are after the context node in document order, excluding any descendants and excluding attribute nodes and namespace nodes.
        <div class="xpath">/AAA/XXX/following::*</div>
        <div class="xpath">//ZZZ/following::*</div>
    </div>
    <div style="margin-top:1ex"><span class="localLink Pages">Preceding axis</span> </div>
    <div class="contentsDescription">The preceding axis contains all nodes in the same document as the context node that are before the context node in document order, excluding any ancestors and excluding attribute nodes and namespace nodes
        <div class="xpath">/AAA/XXX/preceding::*</div>
        <div class="xpath">//GGG/preceding::*</div>
    </div>
    <div style="margin-top:1ex"><span class="localLink Pages">Descendant-or-self axis</span> </div>
    <div class="contentsDescription">The descendant-or-self axis contains the context node and the descendants of the context node
        <div class="xpath">/AAA/XXX/descendant-or-self::*</div>
        <div class="xpath">//CCC/descendant-or-self::*</div>
    </div>
    <div style="margin-top:1ex"><span class="localLink Pages">Ancestor-or-self axis</span> </div>
    <div class="contentsDescription">The ancestor-or-self axis contains the context node and the ancestors of the context node; thus, the ancestor-or-self axis will always include the root node.
        <div class="xpath">/AAA/XXX/DDD/EEE/ancestor-or-self::*</div>
        <div class="xpath">//GGG/ancestor-or-self::*</div>
    </div>
    <div style="margin-top:1ex"><span class="localLink Pages">Orthogonal axes</span> </div>
    <div class="contentsDescription"> The ancestor, descendant, following, preceding and self axes partition a document (ignoring attribute and namespace nodes): they do not overlap and together they contain all the nodes in the document.
        <div class="xpath">//GGG/ancestor::*</div>
        <div class="xpath">//GGG/descendant::*</div>
        <div class="xpath">//GGG/following::*</div>
        <div class="xpath">//GGG/preceding::*</div>
        <div class="xpath">//GGG/self::*</div>
        <div class="xpath">//GGG/ancestor::* | //GGG/descendant::* | //GGG/following::* | //GGG/preceding::* | //GGG/self::*</div>
    </div>
    <div style="margin-top:1ex"><span class="localLink Pages">Numeric operations</span> </div>
    <div class="contentsDescription">The div operator performs floating-point division, the mod operator returns the remainder from a truncating division. The floor function returns the largest (closest to positive infinity) number that is not greater than the argument and that is an integer.The ceiling function returns the smallest (closest to negative infinity) number that is not less than the argument and that is an integer.
        <div class="xpath">//BBB[position() mod 2 = 0 ]</div>
        <div class="xpath">//BBB[ position() = floor(last() div 2 + 0.5) or position() = ceiling(last() div 2 + 0.5) ]</div>
        <div class="xpath">//CCC[ position() = floor(last() div 2 + 0.5) or position() = ceiling(last() div 2 + 0.5) ]</div>
	</div>
	</div>
</div>
<?php
}

	
function wpematico_fullcontent_commandshelp(){
?><div style="height:300px;">
Command reference<br />
---------------------------------------<br />
<h3>XPath</h3>

<p>To select elements for extraction or removal, we use XPath. If you're not familiar with the syntax, there's a nice tutorial here: <a href="#" onclick="jQuery('#tab-link-xpath_tutorial a').click();">XPath 1.0 tutorial</a>.</p>

<h3>Pattern Format</h3>

<p>The pattern format has been borrowed from Instapaper. You'll find plenty of examples by opening the text files inside the /wpematico_fullcontent/inc/content-extractor/config/standard folder.</p>

<p>We currently recognise the following directives:</p>

<h4>title: [XPath]</h4>

<p>The page title. XPaths evaluating to strings are also accepted. Multiple statements accepted. Will evaluate in order until a result is found. If not specified or no match found, it will be auto-detected, but will be used just if the option in campaign is checked.</p>

<dl>
	<dt>Example</dt>
	<dd>
	<pre>		<code>title: //h1[@id='title']</code></pre>
	</dd>
</dl>

<h4>body: [XPath]</h4>

<p>The body of the article. Multiple statements accepted. Will evaluate in order until a result is found. If not specified or no match found, it will be auto-detected.</p>

<dl>
	<dt>Example</dt>
	<dd>
	<pre>		<code>body: //div[@id='body']
		# also possible to specify multiple 
		# elements to be concatenated:
		body: //div[@class='summary'] | //div[@id='body']
</code></pre>
	</dd>
</dl>

<h4>date: [XPath]</h4>

<p>The publication date. XPaths evaluating to strings are also accepted. Multiple statements accepted. Will evaluate in order until a result is found.</p>

<dl>
	<dt>Example</dt>
	<dd>
	<pre>		<code>date: //span[@class='date']</code></pre>
	</dd>
</dl>

<h4>author: [XPath]</h4>

<p>The author(s) of the piece. XPaths evaluating to strings are also accepted. Multiple statements accepted. Will evaluate in order until a result is found.</p>

<dl>
	<dt>Example</dt>
	<dd>
	<pre>		<code>author: //span[@class='author']</code></pre>
	</dd>
</dl>

<h4>strip: [XPath]</h4>

<p>Strip any matching elements and their children. Multiple statements accepted.</p>

<dl>
	<dt>Example</dt>
	<dd>
	<pre>		<code>strip: //div[@class='hidden']
		strip: //div[@id='content']//p[@class='promo']

</code></pre>
	</dd>
</dl>

<h4>strip_id_or_class: [string]</h4>

<p>Strip any element whose @id or @class contains this substring. Multiple statements accepted.</p>

<dl>
	<dt>Example</dt>
	<dd>
	<pre>		<code>strip_id_or_class: hidden
		strip_id_or_class: navigation</code></pre>
	</dd>
</dl>

<h4>strip_image_src: [string]</h4>

<p>Strip any &lt;img&gt; element where @src attribute contains this substring. Multiple statements accepted.</p>

<dl>
	<dt>Example</dt>
	<dd>
	<pre>		<code>strip_image_src: /advert/
		strip_image_src: /tracker/
</code></pre>
	</dd>
</dl>

<h4>tidy: [yes|no] (default: yes)</h4>

<p>Preprocess with Tidy. Tidy usually helps clean up the HTML for processing. It can, however, sometimes make matters worse. If it does, try setting this to no. (This setting may affect the final DOM tree produced, and with it affect your xpath expressions – so if your xpath is failing to match the desired elements, try setting this to 'no' to see if helps.)</p>

<dl>
	<dt>Example</dt>
	<dd>
	<pre>		<code>tidy: no
</code></pre>
	</dd>
</dl>

<h4>prune: [yes|no] (default: yes)</h4>

<p>Strip elements within body that do not resemble content elements. Sometimes this leads to elements which you'd like to keep from being stripped. If that happens, set this to no.</p>

<dl>
	<dt>Example</dt>
	<dd>
	<pre>		<code>prune: no
</code></pre>
	</dd>
</dl>

<h4>autodetect_on_failure: [yes|no] (default: yes)</h4>

<p>If set to no, we will not attempt to auto-detect the title or content block if the given title/body expressions fail to match.</p>

<dl>
	<dt>Example</dt>
	<dd>
	<pre>		<code>autodetect_on_failure: no
</code></pre>
	</dd>
</dl>

<h4>single_page_link: [XPath]</h4>

<p>Identifies a link element or URL pointing to the page holding the entire article. This is useful for sites which split their articles across multiple pages. Links to such pages tend to display the first page with links to the other pages at the bottom. Often there is also a link to a page which displays the entire article on one page (e.g. 'print view' or 'single page'). This should be an XPath expression identifying the link to that page. If present and we find a match, we will retrieve that page and the site config options will be applied to the new page.</p>

<dl>
	<dt>Examples</dt>
	<dd>
	<pre>		<code>single_page_link: //span[@class='singlePage']/a
		single_page_link: //a[contains(@href, '/print/')]
</code></pre>
	</dd>
</dl>

<h4>single_page_link_in_feed: [XPath]</h4>

<p>Same as above, but applied to item description HTML taken from feed. Please be aware that the same article URL may appear in a variety of feeds which do not always contain the same item description. If both single_page_link and single_page_link_in_feed appear in the site config, single_page_link_in_feed will be ignored.</p>

<dl>
	<dt>Example</dt>
	<dd>
	<pre>		<code>single_page_link_in_feed: //a
</code></pre>
	</dd>
</dl>

<h4>next_page_link: [XPath]</h4>

<p>Identifies a link element or URL pointing to the next page of a multi-page article. This is useful for sites which split their articles across multiple pages but do not offer a single page view (if a single page view is provided, please use single_page_link instead - it'll be much faster). If present and we find a match, we will retrieve that page and the site config options will be applied to the new page. If the next_page_link matches a link on this new page, the process will continue. Finally the content will be joined together.&nbsp;<span style="font-size: 13px;">(Introduced in version 3.0.)</span></p>

<dl>
	<dt>Examples</dt>
	<dd>
	<pre>		<code>next_page_link: //a[@id='next-page']
		next_page_link: //a[contains(text(), 'Next page')]
</code></pre>
	</dd>
</dl>

<h4>replace_string([string to find]): [replacement string]</h4>

<p>Simple find and replace to be performed on HTML before processing.</p>

<dl>
	<dt>Example</dt>
	<dd>
	<pre>		<code>replace_string(&lt;p /&gt;): &lt;br /&gt;&lt;br /&gt;
		# alternatively in version 3.0 you can write the above as: 
		find_string: &lt;p /&gt; 
		replace_string: &lt;br /&gt;&lt;br /&gt;
</code></pre>
	</dd>
</dl>

<h4>parser: [string]</h4>

<p>By default we&nbsp;rely on PHP’s fast libxml parser. For sites where this proves problematic, you can now specify <a href="http://code.google.com/p/html5lib/">html5lib</a> – a PHP implementation of a HTML parser based on the HTML5 spec. (Introduced in version 3.0.)</p>

<p><strong>Note:</strong> html5lib is a slower parser and still quite buggy.</p>

<dl>
	<dt>Example</dt>
	<dd>
	<pre>		<code>parser: html5lib
</code></pre>
	</dd>
</dl>

<h4>test_url: [string]</h4>

<p>A URL to use to test the pattern. In future, we’ll have a tool which will use this to automatically test if the patterns in the file are still valid. Must be URL of an article from this site, not the site's front page. One or more.</p>

<dl>
	<dt>Example</dt>
	<dd>
	<pre>		<code>test_url: http://www.example.org/2011/05/what-a-day/
</code></pre>
	</dd>
</dl>

<h4># comments</h4>

<p>Lines beginning with # are ignored.</p>

<dl>
	<dt>Example</dt>
	<dd>
	<pre>		<code># this is an advert
		strip: //img[@class='ad']<font face="sans-serif, Arial, Verdana, Trebuchet MS" size="2"><span style="white-space: normal;">
</span></font></code></pre>
	</dd>
</dl>
</div>
<?php
}


/**
 * Save txt file by ajax 
 */
add_action( 'wp_ajax_wpematico_save_fullcontent', 'wpematicopro_fullcontent_save' );
function wpematicopro_fullcontent_save() {
	if( ! wp_verify_nonce( $_POST['_wpnonce' ], 'wpematicopro-fullcontent' ) ) {		
		wp_send_json_error( array( 'error' => true, 'message' => __( 'File not found.', 'wpematico' ) ) );
	}
	check_admin_referer('wpematicopro-fullcontent');
	if ( 'POST' === $_SERVER[ 'REQUEST_METHOD' ] ) {
		if ( get_magic_quotes_gpc() ) {
			$_POST = array_map( 'stripslashes', $_POST );
		}
		
		if (isset($_POST['pathfilename']))	{
			$pathname = $_POST['pathfilename'];
			$file = (isset($pathname) && !empty($pathname) ) ? $pathname : false ;
			if (isset($_POST['filename']) && $_POST['filename']!="NO" )	{
				//$exist = checkdnsrr( idn_to_ascii( $_POST['filename'] ), 'ANY' );
				$exist = checkdnsrr( $_POST['filename'] , 'ANY' );
				if(!$exist) {
					wp_send_json_error( array( 'error' => true, 'message' => __( 'Domain does not exist. Please type a correct domain name without schema, ie: "etruel.com"', 'wpematico' ) ) );
				}
				$file = trailingslashit(dirname($pathname)).$_POST['filename'].'.txt';
			}
			
			if(!$file) {
				wp_send_json_error( array( 'error' => true, 'message' => __( 'File does not exist. Please type a correct domain name without schema, ie: "etruel.com".', 'wpematico' ) ) );
			}else{
				$textarea =  stripslashes($_POST['textfile']); 
				$saved = file_put_contents($file, $textarea );
				if ($saved===FALSE) {
					wp_send_json_error( array( 'error' => true, 'message' => __( 'Can\'t save file!', 'wpematico' ) ) );
				}
			}

			$ret = array( 'pathfilename'=> $file,'filename'=> basename($file) );
			wp_send_json_success($ret);
		}
	}
}




/**
 * More wpematico options
 */
/*add_filter('wpematico_more_options', 'wpematico_fullcontent_options',10 ,2);  
function wpematico_fullcontent_options($cfg, $options){
	$cfg['pvefromemail']	= (!isset($options['pvefromemail']) || empty($options['pvefromemail']) ) ? 'publisher.email.address@'.str_ireplace('www.', '', parse_url(get_option('siteurl'), PHP_URL_HOST)) : $options['pvefromemail'];
	$cfg['pvefromname']		= (!isset($options['pvefromname']) or empty($options['pvefromname']) ) ? 'WPeMatico Publish 2 Email' : $options['pvefromname'];
	$cfg['pvetoemail']		= (!isset($options['pvetoemail']) || empty($options['pvetoemail']) ) ? 'destiny.email.address@'.str_ireplace('www.', '', parse_url(get_option('siteurl'), PHP_URL_HOST)) : $options['pvetoemail'];
	return $cfg;
}
*/

/**
 * Save more wpematico options
 */
/*add_action( 'wpematico_save_fullcontent', 'wpematico_fullcontent_save' );
function wpematico_fullcontent_save() {
	if ( 'POST' === $_SERVER[ 'REQUEST_METHOD' ] ) {
		if ( get_magic_quotes_gpc() ) {
			$_POST = array_map( 'stripslashes_deep', $_POST );
		}
		# evaluation goes here
		check_admin_referer('wpematico-fullcontent');
		$errlev = error_reporting();
		error_reporting(E_ALL & ~E_NOTICE);  // desactivo los notice que aparecen con los _POST

		$cfg = get_option(WPeMatico :: OPTION_KEY);
		$cfg = array_merge($cfg, $_POST);
		$cfg = apply_filters('wpematico_check_options',$cfg);
				
		if( update_option( WPeMatico::OPTION_KEY, $cfg ) ) {
			?><div class="notice notice-success is-dismissible"><p> <?php _e( 'Settings saved.', 'wpematico_fullcontent' );?></p></div><?php
		}

		error_reporting($errlev);

	}
}
*/