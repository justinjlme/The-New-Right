<?php

/* mcp_queue.html */
class __TwigTemplate_c75b7e0b8d4c748c62a565d29eb9bac0249cf9e7a75cef109b7c575712de1a3d extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        $location = "mcp_header.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("mcp_header.html", "mcp_queue.html", 1)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 2
        echo "
<form id=\"mcp\" method=\"post\" action=\"";
        // line 3
        echo (isset($context["S_MCP_ACTION"]) ? $context["S_MCP_ACTION"] : null);
        echo "\">

<fieldset class=\"forum-selection\">
\t<label for=\"fo\">";
        // line 6
        echo $this->env->getExtension('phpbb')->lang("FORUM");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo " <select name=\"f\" id=\"fo\">";
        echo (isset($context["S_FORUM_OPTIONS"]) ? $context["S_FORUM_OPTIONS"] : null);
        echo "</select></label>
\t<input type=\"submit\" name=\"sort\" value=\"";
        // line 7
        echo $this->env->getExtension('phpbb')->lang("GO");
        echo "\" class=\"button2\" />
\t";
        // line 8
        echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
        echo "
</fieldset>

<h2>";
        // line 11
        echo $this->env->getExtension('phpbb')->lang("TITLE");
        echo "</h2>

<div class=\"panel\">
\t<div class=\"inner\">

\t<p>";
        // line 16
        echo $this->env->getExtension('phpbb')->lang("EXPLAIN");
        echo "</p>

\t";
        // line 18
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "postrow", array()))) {
            // line 19
            echo "\t\t<div class=\"action-bar bar-top\">
\t\t\t<div class=\"pagination\">
\t\t\t\t";
            // line 21
            echo (isset($context["TOTAL"]) ? $context["TOTAL"] : null);
            echo "
\t\t\t\t";
            // line 22
            if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination", array()))) {
                // line 23
                echo "\t\t\t\t\t";
                $location = "pagination.html";
                $namespace = false;
                if (strpos($location, '@') === 0) {
                    $namespace = substr($location, 1, strpos($location, '/') - 1);
                    $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                    $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
                }
                $this->loadTemplate("pagination.html", "mcp_queue.html", 23)->display($context);
                if ($namespace) {
                    $this->env->setNamespaceLookUpOrder($previous_look_up_order);
                }
                // line 24
                echo "\t\t\t\t";
            } else {
                // line 25
                echo "\t\t\t\t\t &bull; ";
                echo (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null);
                echo "
\t\t\t\t";
            }
            // line 27
            echo "\t\t\t</div>
\t\t</div>

\t\t<ul class=\"topiclist missing-column\">
\t\t\t<li class=\"header\">
\t\t\t\t<dl>
\t\t\t\t\t<dt><div class=\"list-inner\">";
            // line 33
            if ((isset($context["S_TOPICS"]) ? $context["S_TOPICS"] : null)) {
                echo $this->env->getExtension('phpbb')->lang("TOPIC");
            } else {
                echo $this->env->getExtension('phpbb')->lang("POST");
            }
            echo "</div></dt>
\t\t\t\t\t<dd class=\"moderation\"><span>";
            // line 34
            if ( !(isset($context["S_TOPICS"]) ? $context["S_TOPICS"] : null)) {
                echo $this->env->getExtension('phpbb')->lang("TOPIC");
                echo " &amp; ";
            }
            echo $this->env->getExtension('phpbb')->lang("FORUM");
            echo "</span></dd>
\t\t\t\t\t<dd class=\"mark\">";
            // line 35
            echo $this->env->getExtension('phpbb')->lang("MARK");
            echo "</dd>
\t\t\t\t</dl>
\t\t\t</li>
\t\t\t</ul>
\t\t\t<ul class=\"topiclist cplist missing-column responsive-show-all\">

\t\t";
            // line 41
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "postrow", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["postrow"]) {
                // line 42
                echo "
\t\t";
                // line 43
                if ($this->getAttribute($context["postrow"], "S_DELETED_TOPIC", array())) {
                    // line 44
                    echo "\t\t\t<li><p class=\"notopics\">";
                    echo $this->env->getExtension('phpbb')->lang("DELETED_TOPIC");
                    echo "</p></li>
\t\t";
                } else {
                    // line 46
                    echo "
\t\t<li class=\"row";
                    // line 47
                    if (($this->getAttribute($context["postrow"], "S_ROW_COUNT", array()) % 2 == 1)) {
                        echo " bg1";
                    } else {
                        echo " bg2";
                    }
                    echo "\">
\t\t\t<dl>
\t\t\t\t<dt>
\t\t\t\t\t<div class=\"list-inner\">
\t\t\t\t\t\t<a href=\"";
                    // line 51
                    echo $this->getAttribute($context["postrow"], "U_VIEW_DETAILS", array());
                    echo "\" class=\"topictitle\">";
                    echo $this->getAttribute($context["postrow"], "POST_SUBJECT", array());
                    echo "</a>";
                    if ($this->getAttribute($context["postrow"], "S_HAS_ATTACHMENTS", array())) {
                        echo " <i class=\"icon fa-paperclip fa-fw\" aria-hidden=\"true\"></i> ";
                    }
                    echo "<br />
\t\t\t\t\t\t<span>";
                    // line 52
                    echo $this->env->getExtension('phpbb')->lang("POSTED");
                    echo " ";
                    echo $this->env->getExtension('phpbb')->lang("POST_BY_AUTHOR");
                    echo " ";
                    echo $this->getAttribute($context["postrow"], "POST_AUTHOR_FULL", array());
                    echo " &raquo; ";
                    echo $this->getAttribute($context["postrow"], "POST_TIME", array());
                    echo "</span>
\t\t\t\t\t</div>
\t\t\t\t</dt>
\t\t\t\t<dd class=\"moderation\">
\t\t\t\t\t<span>
\t\t\t\t\t\t";
                    // line 57
                    if ((isset($context["S_TOPICS"]) ? $context["S_TOPICS"] : null)) {
                        echo "<br />";
                    } else {
                        echo $this->env->getExtension('phpbb')->lang("TOPIC");
                        echo $this->env->getExtension('phpbb')->lang("COLON");
                        echo " <a href=\"";
                        echo $this->getAttribute($context["postrow"], "U_TOPIC", array());
                        echo "\">";
                        echo $this->getAttribute($context["postrow"], "TOPIC_TITLE", array());
                        echo "</a> <br />";
                    }
                    // line 58
                    echo "\t\t\t\t\t\t";
                    echo $this->env->getExtension('phpbb')->lang("FORUM");
                    echo $this->env->getExtension('phpbb')->lang("COLON");
                    echo " <a href=\"";
                    echo $this->getAttribute($context["postrow"], "U_VIEWFORUM", array());
                    echo "\">";
                    echo $this->getAttribute($context["postrow"], "FORUM_NAME", array());
                    echo "</a>
\t\t\t\t\t</span>
\t\t\t\t</dd>


\t\t \t\t<dd class=\"mark\">
\t\t\t\t\t";
                    // line 64
                    if ((isset($context["S_TOPICS"]) ? $context["S_TOPICS"] : null)) {
                        // line 65
                        echo "\t\t\t\t\t\t<input type=\"checkbox\" name=\"topic_id_list[]\" value=\"";
                        echo $this->getAttribute($context["postrow"], "TOPIC_ID", array());
                        echo "\" />
\t\t\t\t\t";
                    } else {
                        // line 67
                        echo "\t\t\t\t\t\t<input type=\"checkbox\" name=\"post_id_list[]\" value=\"";
                        echo $this->getAttribute($context["postrow"], "POST_ID", array());
                        echo "\" />
\t\t\t\t\t";
                    }
                    // line 69
                    echo "\t\t\t\t</dd>
\t\t\t</dl>
\t\t</li>
\t\t";
                }
                // line 73
                echo "\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['postrow'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 74
            echo "\t\t</ul>

\t\t<div class=\"action-bar bottom\">
\t\t\t";
            // line 77
            $location = "display_options.html";
            $namespace = false;
            if (strpos($location, '@') === 0) {
                $namespace = substr($location, 1, strpos($location, '/') - 1);
                $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
            }
            $this->loadTemplate("display_options.html", "mcp_queue.html", 77)->display($context);
            if ($namespace) {
                $this->env->setNamespaceLookUpOrder($previous_look_up_order);
            }
            // line 78
            echo "\t\t\t";
            if ((isset($context["TOPIC_ID"]) ? $context["TOPIC_ID"] : null)) {
                echo "<label><input type=\"checkbox\" class=\"radio\" name=\"t\" value=\"";
                echo (isset($context["TOPIC_ID"]) ? $context["TOPIC_ID"] : null);
                echo "\" checked=\"checked\" onClick=\"document.getElementById('mcp').submit()\" /> <strong>";
                echo $this->env->getExtension('phpbb')->lang("ONLY_TOPIC");
                echo "</strong></label>";
            }
            // line 79
            echo "
\t\t\t<div class=\"pagination\">
\t\t\t\t";
            // line 81
            echo (isset($context["TOTAL"]) ? $context["TOTAL"] : null);
            echo "
\t\t\t\t";
            // line 82
            if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination", array()))) {
                // line 83
                echo "\t\t\t\t\t";
                $location = "pagination.html";
                $namespace = false;
                if (strpos($location, '@') === 0) {
                    $namespace = substr($location, 1, strpos($location, '/') - 1);
                    $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                    $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
                }
                $this->loadTemplate("pagination.html", "mcp_queue.html", 83)->display($context);
                if ($namespace) {
                    $this->env->setNamespaceLookUpOrder($previous_look_up_order);
                }
                // line 84
                echo "\t\t\t\t";
            } else {
                // line 85
                echo "\t\t\t\t\t &bull; ";
                echo (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null);
                echo "
\t\t\t\t";
            }
            // line 87
            echo "\t\t\t</div>
\t\t</div>

\t";
        } else {
            // line 91
            echo "\t\t<p class=\"notopics\"><strong>
\t\t\t";
            // line 92
            if ((isset($context["S_RESTORE"]) ? $context["S_RESTORE"] : null)) {
                // line 93
                echo "\t\t\t\t";
                if ((isset($context["S_TOPICS"]) ? $context["S_TOPICS"] : null)) {
                    echo $this->env->getExtension('phpbb')->lang("NO_TOPICS_DELETED");
                } else {
                    echo $this->env->getExtension('phpbb')->lang("NO_POSTS_DELETED");
                }
                // line 94
                echo "\t\t\t";
            } else {
                // line 95
                echo "\t\t\t\t";
                if ((isset($context["S_TOPICS"]) ? $context["S_TOPICS"] : null)) {
                    echo $this->env->getExtension('phpbb')->lang("NO_TOPICS_QUEUE");
                } else {
                    echo $this->env->getExtension('phpbb')->lang("NO_POSTS_QUEUE");
                }
                // line 96
                echo "\t\t\t";
            }
            // line 97
            echo "\t\t</strong></p>
\t";
        }
        // line 99
        echo "
\t</div>
</div>

";
        // line 103
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "postrow", array()))) {
            // line 104
            echo "\t<fieldset class=\"display-actions\">
\t\t";
            // line 105
            if ((isset($context["S_RESTORE"]) ? $context["S_RESTORE"] : null)) {
                // line 106
                echo "\t\t<input class=\"button2\" type=\"submit\" name=\"action[delete]\" value=\"";
                echo $this->env->getExtension('phpbb')->lang("DELETE");
                echo "\" />&nbsp;
\t\t<input class=\"button1\" type=\"submit\" name=\"action[restore]\" value=\"";
                // line 107
                echo $this->env->getExtension('phpbb')->lang("RESTORE");
                echo "\" />
\t\t";
            } else {
                // line 109
                echo "\t\t<input class=\"button2\" type=\"submit\" name=\"action[disapprove]\" value=\"";
                echo $this->env->getExtension('phpbb')->lang("DISAPPROVE");
                echo "\" />&nbsp;
\t\t<input class=\"button1\" type=\"submit\" name=\"action[approve]\" value=\"";
                // line 110
                echo $this->env->getExtension('phpbb')->lang("APPROVE");
                echo "\" />
\t\t";
            }
            // line 112
            echo "\t\t<div>
\t\t\t";
            // line 113
            if ((isset($context["S_TOPICS"]) ? $context["S_TOPICS"] : null)) {
                // line 114
                echo "\t\t\t\t<a href=\"#\" onclick=\"marklist('mcp', 'topic_id_list', true); return false;\">";
                echo $this->env->getExtension('phpbb')->lang("MARK_ALL");
                echo "</a> :: <a href=\"#\" onclick=\"marklist('mcp', 'topic_id_list', false); return false;\">";
                echo $this->env->getExtension('phpbb')->lang("UNMARK_ALL");
                echo "</a>
\t\t\t";
            } else {
                // line 116
                echo "\t\t\t\t<a href=\"#\" onclick=\"marklist('mcp', 'post_id_list', true); return false;\">";
                echo $this->env->getExtension('phpbb')->lang("MARK_ALL");
                echo "</a> :: <a href=\"#\" onclick=\"marklist('mcp', 'post_id_list', false); return false;\">";
                echo $this->env->getExtension('phpbb')->lang("UNMARK_ALL");
                echo "</a>
\t\t\t";
            }
            // line 118
            echo "\t\t</div>
\t</fieldset>
";
        }
        // line 121
        echo "</form>

";
        // line 123
        $location = "mcp_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("mcp_footer.html", "mcp_queue.html", 123)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
    }

    public function getTemplateName()
    {
        return "mcp_queue.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  399 => 123,  395 => 121,  390 => 118,  382 => 116,  374 => 114,  372 => 113,  369 => 112,  364 => 110,  359 => 109,  354 => 107,  349 => 106,  347 => 105,  344 => 104,  342 => 103,  336 => 99,  332 => 97,  329 => 96,  322 => 95,  319 => 94,  312 => 93,  310 => 92,  307 => 91,  301 => 87,  295 => 85,  292 => 84,  279 => 83,  277 => 82,  273 => 81,  269 => 79,  260 => 78,  248 => 77,  243 => 74,  237 => 73,  231 => 69,  225 => 67,  219 => 65,  217 => 64,  202 => 58,  190 => 57,  176 => 52,  166 => 51,  155 => 47,  152 => 46,  146 => 44,  144 => 43,  141 => 42,  137 => 41,  128 => 35,  120 => 34,  112 => 33,  104 => 27,  98 => 25,  95 => 24,  82 => 23,  80 => 22,  76 => 21,  72 => 19,  70 => 18,  65 => 16,  57 => 11,  51 => 8,  47 => 7,  40 => 6,  34 => 3,  31 => 2,  19 => 1,);
    }
}
/* <!-- INCLUDE mcp_header.html -->*/
/* */
/* <form id="mcp" method="post" action="{S_MCP_ACTION}">*/
/* */
/* <fieldset class="forum-selection">*/
/* 	<label for="fo">{L_FORUM}{L_COLON} <select name="f" id="fo">{S_FORUM_OPTIONS}</select></label>*/
/* 	<input type="submit" name="sort" value="{L_GO}" class="button2" />*/
/* 	{S_FORM_TOKEN}*/
/* </fieldset>*/
/* */
/* <h2>{L_TITLE}</h2>*/
/* */
/* <div class="panel">*/
/* 	<div class="inner">*/
/* */
/* 	<p>{L_EXPLAIN}</p>*/
/* */
/* 	<!-- IF .postrow -->*/
/* 		<div class="action-bar bar-top">*/
/* 			<div class="pagination">*/
/* 				{TOTAL}*/
/* 				<!-- IF .pagination -->*/
/* 					<!-- INCLUDE pagination.html -->*/
/* 				<!-- ELSE -->*/
/* 					 &bull; {PAGE_NUMBER}*/
/* 				<!-- ENDIF -->*/
/* 			</div>*/
/* 		</div>*/
/* */
/* 		<ul class="topiclist missing-column">*/
/* 			<li class="header">*/
/* 				<dl>*/
/* 					<dt><div class="list-inner"><!-- IF S_TOPICS -->{L_TOPIC}<!-- ELSE -->{L_POST}<!-- ENDIF --></div></dt>*/
/* 					<dd class="moderation"><span><!-- IF not S_TOPICS -->{L_TOPIC} &amp; <!-- ENDIF -->{L_FORUM}</span></dd>*/
/* 					<dd class="mark">{L_MARK}</dd>*/
/* 				</dl>*/
/* 			</li>*/
/* 			</ul>*/
/* 			<ul class="topiclist cplist missing-column responsive-show-all">*/
/* */
/* 		<!-- BEGIN postrow -->*/
/* */
/* 		<!-- IF postrow.S_DELETED_TOPIC -->*/
/* 			<li><p class="notopics">{L_DELETED_TOPIC}</p></li>*/
/* 		<!-- ELSE -->*/
/* */
/* 		<li class="row<!-- IF postrow.S_ROW_COUNT is odd --> bg1<!-- ELSE --> bg2<!-- ENDIF -->">*/
/* 			<dl>*/
/* 				<dt>*/
/* 					<div class="list-inner">*/
/* 						<a href="{postrow.U_VIEW_DETAILS}" class="topictitle">{postrow.POST_SUBJECT}</a><!-- IF postrow.S_HAS_ATTACHMENTS --> <i class="icon fa-paperclip fa-fw" aria-hidden="true"></i> <!-- ENDIF --><br />*/
/* 						<span>{L_POSTED} {L_POST_BY_AUTHOR} {postrow.POST_AUTHOR_FULL} &raquo; {postrow.POST_TIME}</span>*/
/* 					</div>*/
/* 				</dt>*/
/* 				<dd class="moderation">*/
/* 					<span>*/
/* 						<!-- IF S_TOPICS --><br /><!-- ELSE -->{L_TOPIC}{L_COLON} <a href="{postrow.U_TOPIC}">{postrow.TOPIC_TITLE}</a> <br /><!-- ENDIF -->*/
/* 						{L_FORUM}{L_COLON} <a href="{postrow.U_VIEWFORUM}">{postrow.FORUM_NAME}</a>*/
/* 					</span>*/
/* 				</dd>*/
/* */
/* */
/* 		 		<dd class="mark">*/
/* 					<!-- IF S_TOPICS -->*/
/* 						<input type="checkbox" name="topic_id_list[]" value="{postrow.TOPIC_ID}" />*/
/* 					<!-- ELSE -->*/
/* 						<input type="checkbox" name="post_id_list[]" value="{postrow.POST_ID}" />*/
/* 					<!-- ENDIF -->*/
/* 				</dd>*/
/* 			</dl>*/
/* 		</li>*/
/* 		<!-- ENDIF -->*/
/* 		<!-- END postrow -->*/
/* 		</ul>*/
/* */
/* 		<div class="action-bar bottom">*/
/* 			<!-- INCLUDE display_options.html -->*/
/* 			<!-- IF TOPIC_ID --><label><input type="checkbox" class="radio" name="t" value="{TOPIC_ID}" checked="checked" onClick="document.getElementById('mcp').submit()" /> <strong>{L_ONLY_TOPIC}</strong></label><!-- ENDIF -->*/
/* */
/* 			<div class="pagination">*/
/* 				{TOTAL}*/
/* 				<!-- IF .pagination -->*/
/* 					<!-- INCLUDE pagination.html -->*/
/* 				<!-- ELSE -->*/
/* 					 &bull; {PAGE_NUMBER}*/
/* 				<!-- ENDIF -->*/
/* 			</div>*/
/* 		</div>*/
/* */
/* 	<!-- ELSE -->*/
/* 		<p class="notopics"><strong>*/
/* 			<!-- IF S_RESTORE -->*/
/* 				<!-- IF S_TOPICS -->{L_NO_TOPICS_DELETED}<!-- ELSE -->{L_NO_POSTS_DELETED}<!-- ENDIF -->*/
/* 			<!-- ELSE -->*/
/* 				<!-- IF S_TOPICS -->{L_NO_TOPICS_QUEUE}<!-- ELSE -->{L_NO_POSTS_QUEUE}<!-- ENDIF -->*/
/* 			<!-- ENDIF -->*/
/* 		</strong></p>*/
/* 	<!-- ENDIF -->*/
/* */
/* 	</div>*/
/* </div>*/
/* */
/* <!-- IF .postrow -->*/
/* 	<fieldset class="display-actions">*/
/* 		<!-- IF S_RESTORE -->*/
/* 		<input class="button2" type="submit" name="action[delete]" value="{L_DELETE}" />&nbsp;*/
/* 		<input class="button1" type="submit" name="action[restore]" value="{L_RESTORE}" />*/
/* 		<!-- ELSE -->*/
/* 		<input class="button2" type="submit" name="action[disapprove]" value="{L_DISAPPROVE}" />&nbsp;*/
/* 		<input class="button1" type="submit" name="action[approve]" value="{L_APPROVE}" />*/
/* 		<!-- ENDIF -->*/
/* 		<div>*/
/* 			<!-- IF S_TOPICS -->*/
/* 				<a href="#" onclick="marklist('mcp', 'topic_id_list', true); return false;">{L_MARK_ALL}</a> :: <a href="#" onclick="marklist('mcp', 'topic_id_list', false); return false;">{L_UNMARK_ALL}</a>*/
/* 			<!-- ELSE -->*/
/* 				<a href="#" onclick="marklist('mcp', 'post_id_list', true); return false;">{L_MARK_ALL}</a> :: <a href="#" onclick="marklist('mcp', 'post_id_list', false); return false;">{L_UNMARK_ALL}</a>*/
/* 			<!-- ENDIF -->*/
/* 		</div>*/
/* 	</fieldset>*/
/* <!-- ENDIF -->*/
/* </form>*/
/* */
/* <!-- INCLUDE mcp_footer.html -->*/
/* */
