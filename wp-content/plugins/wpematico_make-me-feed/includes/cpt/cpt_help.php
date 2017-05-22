<?php
// don't load directly 
if ( !defined('ABSPATH') ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

$screen = $current_screen; //WP_Screen::get('wpematico_page_wpematico_settings ');
$screen->add_help_tab( array(
	'id'		=> 'mmftuto',
	'title'		=> 'Create a feed Tutorial',
	'content'	=> '',
	'callback'	=>'wpematico_mmf_cpt_help'
) );
$screen->set_help_sidebar(
	'<p>' . 
	'<a href="http://etruel.com/question/tutorial-create-feed-page/" target="_blank">' .
	__('See the full tutorial!', 'make-me-feed' ).
	'</a>'.
	'</p>'.
	'<p>' . 
	'<a href="http://etruel.com/my-account/support/" target="_blank">' .
	__('Get Support.', 'make-me-feed' ).
	'</a>'.
	'</p>'.
	'<p>' . 
	'<a href="http://etruel.com/downloads/premium-support/" target="_blank">' .
	__('Get Premium Support!', 'make-me-feed' ).
	'</a>'.
	'</p>'.
	''
);

function wpematico_mmf_cpt_help( $screen, $tab ){
	?><h3 class="title active">Tutorial on how to create a feed page</h3>
<ol>
 	<li>I will use www.wpematico.com to make a feed.</li>
 	<li>Go to Wordpress Admin.</li>
 	<li><strong>WPeMatico Settings</strong>-&gt; <strong>Full Content <em>Tab</em></strong>.</li>
 	<li>I modified the <strong>wpematico.com.txt</strong> configuration file to get the content I want.</li>
 	<li>I'm going now to the list of Feeds created (none yet) and I give click to Add Feed.</li>
 	<li>Typing "WPeMatico.com test" as a title.</li>
 	<li>I add the <strong>index</strong> <strong>URL of the site </strong>which I will read the titles and links of remote items.</li>
 	<li>I added the maximum <strong>number of items</strong> that I want to get. Others will be ignored.</li>
 	<li>As I have installed the Addon <a href="http://etruel.com/downloads/wpematico-full-content/">WPeMatico Full Content</a>, check the option to get the full content of each article: "<strong>Get Full Content from source permalinks to use as feed item content</strong>."</li>
 	<li>I open the remote site in a new tab, by clicking on the link to the right of the text field.</li>
 	<li>Now we need to find the element in the DOM that contains titles with a link to the article. I come to find classes or IDs CSS containing the element:
<ul>
 	<li>Using <strong>Firefox</strong> (in chrome is very similar), give right click on any of the titles that I want to get, and in the drop-down menu on "<strong>Inspect Element</strong>".</li>
 	<li>Looking for the id or class of the HTML tag surrounding the link, in this case the code that interests me is as follows:</li>
 	<li>&lt;<span style="color: #ff0000;">h2</span> class="<span style="color: #ff0000;">entry-title</span>" itemprop="headline"&gt;
&lt;<span style="color: #ff0000;">a</span> href="<span style="color: #008000;">http://www.wpematico.com/set-images-featured-wpematico/</span>" rel="bookmark"&gt;<span style="color: #008000;">Get and Set Images as Featured with WPeMatico</span>&lt;/<span style="color: #ff0000;">a</span>&gt;
&lt;/<span style="color: #ff0000;">h2</span>&gt;</li>
 	<li style="text-align: left;">I will use what is in red to obtain what is green: as "<span style="color: #ff0000;">a</span>" label does not identify or CSS class, I'll use the "<span style="color: #ff0000;">h2</span>" that contains it.</li>
 	<li>The identifier for this element is found with the "<strong>entry-title</strong>" starting with a "<strong>.</strong>" for being a class. (If it's id is added "<strong>#</strong>") and the identifier in the same way that is done with jQuery, we will add it in the next field in the edition of the feed:
<strong>.entry-title a</strong></li>
 	<li>If there are more titles with different format, I can continue to add IDs and they will be adding to the list by eliminating the repeated links.</li>
</ul>
</li>
 	<li>It is almost ready! It remains to be seen if I was not wrong to choose the identifier. For this I have the button "<strong>Test Area</strong>" used after recording the data, in this instance I can save the feed as a draft for not doing this public yet. When you click on the "<strong>Test Area</strong>" button will open a popup with a sample of what you will get in the final feed.   <span style="text-decoration: underline;">The first time may take a bit to load data from the remote site</span> because you do not have generated a cache but then it goes faster.</li>
 	<li>To see the final Feed I can open "<strong>View post</strong>" or click on the "<strong>Preview Changes</strong>" button.</li>
</ol>
	<?php
}