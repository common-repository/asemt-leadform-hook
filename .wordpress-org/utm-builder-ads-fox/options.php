<?php
if (!function_exists('adfox_esc_js')) {
    function ddt_recursive_esc_js($array) {

        foreach ( $array as $key => &$value ) {
            if ( is_array( $value ) ) {

                $value = ddt_recursive_esc_js($value);

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
//echo mt_shortner_root;
global $wpdb;
$wpdb_prefix = $wpdb->prefix;
$wpdb_tablename = $wpdb_prefix.'adfox_utm';
$result = $wpdb->get_results(sprintf('SELECT * FROM '. $wpdb_tablename));

wp_register_script( 'adfox-utmscript', '' );
wp_enqueue_script( 'adfox-utmscript' );
wp_add_inline_script( 'adfox-utmscript',
                      'var adfox_root = "https://' . esc_js($_SERVER['HTTP_HOST']) .'/";
                       var adfox_ajax_url = "'.esc_js(ADFOX_AJAX).'";
                       var adfox_urls = '.json_encode(ddt_recursive_esc_js($result)).';'
                    );



?>

<div class="b-example-divider"></div>
<div id="mt_app" class="d-none" ng-app="mtShortnerApp" ng-controller="myCtrl">
    <div class="px-4 pt-5 my-5 text-center border-bottom">
      <h1 class="display-4 fw-bold">{{toolname}}</h1>
      <div class="col-lg-6 mx-auto">
        <p class="lead mb-4">{{description}}</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-5">
          <label for="staticEmail" class="col-sm-2 col-form-label">Link</label>
          <div class="col-sm-10">
            <input ng-model="link" type="text"  class="form-control-plaintext" id="mt_link" placeholder="{{placeholder}}" value="">
            <small id="emailHelp" class="form-text text-muted">Your Url to modify with UTM parameter</small>
          </div>
        </div>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-5">
          <label for="staticEmail" class="col-sm-2 col-form-label">UTM Source</label>
          <div class="col-sm-10">
            <input ng-model="source" type="text"  class="form-control-plaintext" id="mt_source" value="">
            <small id="emailHelp" class="form-text text-muted">e.g. newsletter, twitter, google, etc</small>
          </div>
        </div>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-5">
          <label for="staticEmail" class="col-sm-2 col-form-label">UTM Medium</label>
          <div class="col-sm-10">
            <input ng-model="medium" type="text"  class="form-control-plaintext" id="mt_medium" value="">
            <small id="emailHelp" class="form-text text-muted">e.g. email, social, cpc, etc</small>
          </div>
        </div>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-5">
          <label for="staticEmail" class="col-sm-2 col-form-label">UTM Campaign</label>
          <div class="col-sm-10">
            <input ng-model="campaign" type="text"  class="form-control-plaintext" id="mt_campaign" value="">
            <small id="emailHelp" class="form-text text-muted">e.g. promotion, sale, etc</small>
          </div>
        </div>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-5">
          <span id="utmlink">{{link}}?utm_source={{source}}&utm_medium={{medium}}&utm_campaign={{campaign}}</span>
        </div>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-5">
          <label for="staticEmail" class="col-sm-2 col-form-label">Short URL</label>
          <div class="col-sm-10">
            <input oninput="adfox_validate(this)" ng-disabled="!source || !medium || !campaign || !link" ng-model="shorturl" type="text"  class="form-control-plaintext" id="mt_shortURL" placeholder="{{placeholder}}" value="{{placeholder}}">
            <small id="" class="form-text text-muted">Insert your short URL e.g. {{placeholder}}your_path</small>
          </div>
        </div>
        <button ng-disabled="!source || !medium || !campaign || !link || !shorturl" ng-click="mt_shortURL()"  type="button" class="btn btn-outline-secondary btn-lg px-4">{{generateButton}}</button>
      </div>
      <div ng-show="showMe" class="d-flex justify-content-center mt-5">
        <span class="alert alert-success w-80" role="alert" >URL created {{shortUrl}}</span>
      </div>
      <div ng-show="showError" class="d-flex justify-content-center mt-5">
        <span class="alert alert-warning w-80" role="alert" >{{errorMsg}}</span>
      </div>
      <div class="overflow-hidden" style="max-height: 30vh;">
      </div>
    </div>
    <div class="px-4 pt-5 my-5 text-center border-bottom">
        <div class="display-6 fw-bold mb-3">{{table}}</div>
        <table class="table">
          <thead>
              <tr>
                <th>#</th>
                <th>Short URL</th>
                <th>Long URL</th>
                <th>Delete</th>
              <tr>
          </thead>
          <tbody>
            <tr ng-repeat="x in data">
              <td ng-if="$odd" style="background-color:#f1f1f1">{{ $index + 1 }}</td>
              <td ng-if="$odd" style="background-color:#f1f1f1">{{ x.shorturl }}</td>
              <td ng-if="$odd" style="background-color:#f1f1f1">{{ x.longurl }}</td>
              <td ng-if="$odd" style="background-color:#f1f1f1;cursor: pointer;" ng-click="mt_Delete(x.shorturl,this)" >X</td>
              <td ng-if="$even">{{ $index + 1 }}</td>
              <td ng-if="$even">{{ x.shorturl }}</td>
              <td ng-if="$even">{{ x.longurl }}</td>
              <td ng-if="$even" style="cursor:pointer;" ng-click="mt_Delete(x.shorturl,this)">X</td>
            </tr>
         </tbody>
        </table>
    </div>
</div>
