<?php
if(!class_exists('ADFOX_Admin')){
  class ADFOX_Admin {



      public static function init() {

         add_action( 'admin_menu', array( __CLASS__, 'adminMenu' ) );


      }

      public static function adminMenu() {
          add_menu_page(
              __( 'UTM - adsfox', 'adfoxUTM-dashboard' ),
              __( 'UTM Adsfox ', 'adfoxUTM-dashboard' ),
              'manage_options',
              'adfox_UTM-dashboard',
              array( __CLASS__, 'menuPage' ),
              'dashicons-chart-bar',
              6
          );

      }

      public static function menuPage() {

          if ( is_file( ADFOX_ROOT_INCLUDE . 'options.php' ) ) {
              include_once ADFOX_ROOT_INCLUDE . 'options.php';
          }
      }




  }

  ADFOX_Admin::init();
}
