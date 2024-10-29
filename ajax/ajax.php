<?php
if (!function_exists('asemt_change_settings')) {
   function asemt_change_settings(){

$data = json_decode(file_get_contents('php://input'), true);

if(isset( $data['update'])){

    if (!function_exists('asemt_recursive_sanitize_text_field_update')) {
      function asemt_recursive_sanitize_text_field_update($array) {
              foreach ( $array as $key => &$value ) {
                  if ( is_array( $value ) ) {
                      $value = asemt_recursive_sanitize_text_field_update($value);
                  }
                  else {
                      $value = sanitize_text_field( $value );
                  }
              }

              return $array;
          }
      }

      if (!function_exists('asemt_recursive_sanitize_email_update')) {
        function asemt_recursive_sanitize_email_update($array) {

                for ( $i=0; $i<count($array);$i++ ) {

                        sanitize_email( $array[$i] );

                }

                return $array;
            }
        }



       $update =  sanitize_text_field( $data['update'] );
       if(isset( $data['email'])){

         $emails =  asemt_recursive_sanitize_email_update($data['email'] );
        }
       if(isset( $data['code'])){
         $code =  sanitize_text_field( $data['code'] );
       }


       if($update == 1){//addnew

               if (get_option('asemt_ads_hook')) {

                        $option = asemt_recursive_sanitize_text_field_update(json_decode(get_option('asemt_ads_hook'),true));

                        $array = Array(
                                  'email' => json_encode($emails),
                                  'code' => $code
                                );

                        array_push($option,$array);

                        if(update_option('asemt_ads_hook', json_encode($option))){
                        echo json_encode($option); exit();
                      }
               }else{

                 $array = Array(
                            Array(
                              'email' => json_encode($emails),
                              'code' => $code
                              )
                            );

                      add_option("asemt_ads_hook", json_encode($array));
                      echo json_encode($array); exit;
               }

       }elseif($update == 0){//delete
         //echo "hallo";
              $option = asemt_recursive_sanitize_text_field_update(json_decode(get_option('asemt_ads_hook'),true));
              $array = Array();
                for($i=0;$i<count($option);$i++) {
                  if($option[$i]['code'] == $code){

                        //do nothing later maybe slice etc

                  }else{
                    array_push($array, $option[$i]);
                  }
              }

              update_option('asemt_ads_hook', json_encode($array));
              echo json_encode($array);

                exit();
       }

}

   }
   add_action( 'wp_ajax_' . 'asemt_change_settings_activate', 'asemt_change_settings' );
   add_action( 'wp_ajax_nopriv_' . 'asemt_change_settings_activate', 'asemt_change_settings' );
}
