<?php
if (!function_exists('adfox_addUTM')) {
   function adfox_addUTM(){



if(isset( $_POST['shorturl'])){

  if (!function_exists('adfox_esc_js')) {
      function adfox_esc_js($array) {
          //echo json_encode($array);
          foreach ( $array as $key => &$value ) {
              if ( is_array( $value ) ) {

                  $value = adfox_esc_js($value);

              }
              else {
                foreach ($value as $key => $val) {
                    $val = esc_js($val);
                    }
              }
          }

          return $array;
      }
  }

       $shorturl =  sanitize_url( $_POST['shorturl'] );
       $longurl =  sanitize_url( $_POST['longurl'] );

       global $wpdb;
       $table_name = $wpdb->prefix . 'adfox_utm';
       if($wpdb->insert($table_name, array('shorturl' => $shorturl, 'longurl' => $longurl))){


             //get all urls for updating table
            $wpdb_prefix = $wpdb->prefix;
            $wpdb_tablename = $wpdb_prefix.'adfox_utm';
            $result = $wpdb->get_results(sprintf('SELECT * FROM '. $wpdb_tablename));
            echo json_encode($result);
            exit;



       }



}

   }
   add_action( 'wp_ajax_' . 'adfox_addUTM_activate', 'adfox_addUTM' );
   add_action( 'wp_ajax_nopriv_' . 'adfox_addUTM_activate', 'adfox_addUTM' );
}


if (!function_exists('adfox_removeUTM')) {
   function adfox_removeUTM(){

     if(isset( $_POST['shorturl'])){

       if (!function_exists('adfox_esc_js')) {
           function adfox_esc_js($array) {
               //echo json_encode($array);
               foreach ( $array as $key => &$value ) {
                   if ( is_array( $value ) ) {

                       $value = adfox_esc_js($value);

                   }
                   else {
                     foreach ($value as $key => $val) {
                         $val = esc_js($val);
                         }
                   }
               }

               return $array;
           }
       }



         $shorturl =  sanitize_url( $_POST['shorturl'] );
          global $wpdb;
          $wpdb_prefix = $wpdb->prefix;
          $wpdb_tablename = $wpdb_prefix.'adfox_utm';
          //delete table by short url
          $result = $wpdb->delete( $wpdb_tablename, array( 'shorturl' => $shorturl ));
          if($result == 1){
              //get all urls for updating table
             $wpdb_prefix = $wpdb->prefix;
             $wpdb_tablename = $wpdb_prefix.'adfox_utm';
             $result = $wpdb->get_results(sprintf('SELECT * FROM '. $wpdb_tablename));
             echo json_encode($result);
             exit;
           }
   }


   }
   add_action( 'wp_ajax_' . 'adfox_removeUTM_activate', 'adfox_removeUTM' );
   add_action( 'wp_ajax_nopriv_' . 'adfox_removeUTM_activate', 'adfox_removeUTM' );
}
