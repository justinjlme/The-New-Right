<?php

//last version sync
if( ! defined( 'LAST_THEME_VERSION_FMK_SYNC' ) ) define( 'LAST_THEME_VERSION_FMK_SYNC' , '3.3.10' );//<= used only in the free addons, but has to be defined here because invoked in addons/ha-czr.php
if( ! defined( 'MINIMAL_AUTHORIZED_THEME_VERSION' ) ) define( 'MINIMAL_AUTHORIZED_THEME_VERSION' , '3.3.0' );

/* ------------------------------------------------------------------------- *
 *  LOADS PRO ADDONS CLASSES
/* ------------------------------------------------------------------------- */
//PRO HEADER
if ( ! is_object( HU_AD() -> pro_header ) ) {
  require_once( HA_BASE_PATH . 'addons/pro/header/init-pro-header.php' );
  HU_AD() -> pro_header = new PC_HAPH();
  // require_once( HA_BASE_PATH . 'addons/header-posts-slider/init-posts-slider.php' );
  // HU_AD() -> posts_slider_header = new HU_POSTS_SLIDER_HEADER();
}

//MASONRY
if ( ! is_object( HU_AD() -> pro_grids ) ) {
  require_once( HA_BASE_PATH . 'addons/pro/grids/init-pro-grids.php' );
  HU_AD() -> pro_grids = new PC_HAPGRIDS();
}

//INFINITE
if ( ! is_object( HU_AD() -> pro_infinite ) ) {
  require_once( HA_BASE_PATH . 'addons/pro/infinite/init-pro-infinite.php' );
  HU_AD() -> pro_infinite_scroll = new PC_HAPINF();
}

//WFC
//this autoloads
require_once( HA_BASE_PATH . 'addons/pro/wfc/wordpress-font-customizer.php' );

//SKINS
// if ( ! is_object( HU_AD() -> pro_skins ) ) {
//   require_once( HA_BASE_PATH . 'addons/pro/skins/init-pro-skins.php' );
//   HU_AD() -> pro_skins = new PC_HASKINS();
// }


/* ------------------------------------------------------------------------- *
*  LOAD PRO MODULES AND INPUTS TEMPLATES
/* ------------------------------------------------------------------------- */
function hu_load_pro_module_tmpl() {
  $_tmpl = array(
      //MODULES
      HA_BASE_PATH . 'addons/pro/czr_tmpl/mods/text_editor-module-tmpl.php',
      HA_BASE_PATH . 'addons/pro/czr_tmpl/mods/slide-module-tmpl.php',
      //HA_BASE_PATH . 'addons/pro/czr_tmpl/mods/posts-slider-module-tmpl.php',

      //INPUTS
      HA_BASE_PATH . 'addons/pro/czr_tmpl/inputs/text_editor-input-tmpl.php'
  );
  foreach ($_tmpl as $_path) {
      require_once( $_path );
  }
}
hu_load_pro_module_tmpl();

//LOADS RESOURCES :
//=> Enqueue a WP Editor instance
//=> Extends the preview callbacks for pro modules
require_once( HA_BASE_PATH . 'addons/pro/czr_resources/modules-resources.php' );