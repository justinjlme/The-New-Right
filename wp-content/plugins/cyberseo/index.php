<?php
@require_once("../../../wp-config.php");
status_header(404);
nocache_headers();
@include(get_404_template());
?>