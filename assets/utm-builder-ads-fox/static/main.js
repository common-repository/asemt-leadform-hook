var adfox_app = angular.module('mtShortnerApp', []);
angular.element(document).ready(function () {
      document.querySelector("#mt_app").classList.remove("d-none");
});
adfox_app.controller('myCtrl', function($scope) {
  $scope.table = "Your previeously created Links"
  $scope.toolname = "URL Shortner";
  $scope.description = "Enter your URL to short it";
  $scope.generateButton = "Generate";
  $scope.longURL = "";
  $scope.placeholder = adfox_root;
  $scope.link = adfox_root;
  $scope.source = "";
  $scope.medium = "";
  $scope.campaign = "";
  $scope.showMe = false;
  $scope.shortUrl = "";
  $scope.showError = false;

  $scope.errorMsg = "Short URL allready exist";
  $scope.data = adfox_urls;
  $scope.$watch('data', function() {
        // alert('hey, myVar has changed!');
     });
  $scope.mt_Delete = function(url,e){
      //console.log(url);
      var xhttp = new XMLHttpRequest();
          xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

             var response = xhttp.response;

             //console.log(response);
             if(response !== "0"){
                //console.log(response)
                data = JSON.parse(response);
                $scope.data = data;
                $scope.$apply();
             }

            }
          };
          xhttp.open("POST", adfox_ajax_url, true);
          xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xhttp.send('action=adfox_removeUTM_activate&shorturl='+url);
      //$scope.data = [];
      //console.log(e.remove());
      }
  $scope.mt_shortURL = function() {

          var longurl = document.querySelector("#utmlink").textContent;
          $scope.longURL = longurl;
          if($scope.data.length == 0){
            var shorturl =  document.querySelector("#mt_shortURL").value;
          }else{
            var is_url = 1;
            var shorturl =  document.querySelector("#mt_shortURL").value;
            is_url = 0;
            for(i=0;i<$scope.data.length;i++){
              console.log($scope.data[i].shorturl)
              if($scope.data[i].shorturl == shorturl){

                is_url = 1;
                break;
              }

            }
          if(is_url == 1){
              $scope.showMe = false;
              $scope.errorMsg = "Short URL allready exist";
              $scope.showError = true;
              //$scope.$apply();
              return 1;
          }
          if(shorturl.indexOf(mt_root) == -1){
            $scope.showMe = false;
            $scope.showError = true;
            $scope.errorMsg = "Cant found your domain in url";
            return 1;
          }


          }
          //console.log(shorturl);

          var xhttp = new XMLHttpRequest();
              xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                 var response = xhttp.response;

                      //console.log(response);
                 if(response !== "0"){

                    //console.log(shorturl);
                    $scope.showMe = true;
                    $scope.showError = false;
                    $scope.shortUrl = shorturl;
                    data = JSON.parse(response);
                    $scope.data = data;
                    $scope.$apply();
                 }

                }
              };
              xhttp.open("POST", adfox_ajax_url, true);
              xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
              xhttp.send('action=adfox_addUTM_activate&shorturl='+shorturl+'&longurl='+encodeURIComponent(longurl));
  }
});

function adfox_validate(input){
  if(/^\s/.test(input.value))
    input.value = '';
}

function adfox_generateUID() {
    // I generate the UID from two parts here
    // to ensure the random number provide enough bits.
    var firstPart = (Math.random() * 46656) | 0;
    var secondPart = (Math.random() * 46656) | 0;
    firstPart = ("000" + firstPart.toString(36)).slice(-3);
    secondPart = ("000" + secondPart.toString(36)).slice(-3);
    return firstPart + secondPart;
}
