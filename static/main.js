const loading =  { template:`    <v-col cols="6" class="text-subtitle-1 text-center">
                                        Saving ...
                                    <v-progress-linear
                                      color="deep-purple accent-4"
                                      :active="loading"
                                      :indeterminate="loading"
                                      rounded
                                      height="6"
                                    ></v-progress-linear>
                                  </v-col>`,
                                  data: () => ({
                                    loading: true,
                                  }),
                  }

const main = {
              template: ` <v-simple-table>
                              <template v-slot:default>
                                <thead v-if="options.length > 0">
                                  <tr>
                                    <th class="text-left">
                                      Email
                                    </th>
                                    <th class="text-left">
                                      Code
                                    </th>
                                    <th class="text-left">
                                      Delete
                                    </th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr
                                    v-for="item in options"
                                    :key="item.name"
                                  >
                                    <td>{{ item.email }}</td>
                                    <td>{{ item.code }}</td>
                                    <td

                                    class="row-pointer"
                                    >
                                    <v-icon @click="deleteit(item.code)">mdi-delete</v-icon>
                                    </td>
                                  </tr>
                                </tbody>
                                <v-alert
                                    v-if="options.length == 0"
                                    border="bottom"
                                    colored-border
                                    type="warning"
                                    elevation="2"
                                  >
                                   No Leadform forwarding configured
                                   Klick on plus to create one
                                  </v-alert>
                              </template>
                            </v-simple-table>`,
                          data: function (){
                            return{
                              options: asemt_option

                            }
                          },
                          methods:{
                            deleteit(code){

                              vm.swapComponent('view01');
                              var xhttp = new XMLHttpRequest();
                               xhttp.onreadystatechange = function() {
                                 if (this.readyState == 4 && this.status == 200) {

                                  var response = xhttp.response;

                                       console.log(response)
                                       if(response != 0){
                                         window.asemt_option = JSON.parse(response);
                                         setTimeout(function(){
                                          vm.swapComponent('view02');
                                         },1500);
                                     }else{
                                           setTimeout(function(){vm.swapComponent('view02');},1000);
                                     }
                                 }
                               };
                               xhttp.open("POST", asemt_ajax_url + '?action=asemt_change_settings_activate', true);
                               xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                               xhttp.send(JSON.stringify({'update':'0','code':code}));
                            }
                          }
                        }

const add = {
              template: `   <div>
                              <v-text-field
                                label="Insert Your Email and press enter"
                                v-model="email"
                                @keyup.enter="addEmail"
                              ></v-text-field>
                              <v-list subheader two-line flat>
                                <v-subheader class="subheading" v-if="emails.length == 0">You have 0 Emails, add some</v-subheader>
                                <v-subheader class="subheading" v-else="emails.length == 1">Your Emails</v-subheader>

                                <v-list-item-group>
                                  <v-list-item v-for="(mail, i) in emails">
                                    <template>
                                      <v-list-item-content>
                                        <v-list-item-subtitle>{{mail}}</v-list-item-subtitle>
                                      </v-list-item-content>
                                      <v-btn fab ripple small color="red"  @click="removeEmail(i)">
                                        <v-icon class="white--text">mdi-close</v-icon>
                                      </v-btn>
                                    </template>
                                  </v-list-item>
                                </v-list-item-group>
                              </v-list>

                              <v-text-field
                              class="mt-5"
                              label="Your Secret Code"
                              :rules="rules2"
                              v-model="code"
                              width="100%;"
                              >

                              </v-text-field>
                              <v-btn
                                  class="mt-5"
                                  color="primary"
                                  elevation="2"
                                  @click="update()"
                                >Create</v-btn>
                                <v-spacer></v-spacer>
                                <v-alert
                                   :value="alert"
                                    class="mt-5"
                                    border="top"
                                    colored-border
                                    type="error"
                                    elevation="2"
                                  >
                                    Secret Code allready exist. Create a new one that is unique
                              </v-alert>
                                <v-alert
                                    class="mt-5"
                                    border="top"
                                    colored-border
                                    type="info"
                                    elevation="2"
                                  >
                                    The Code should be unique and difficult. Example:
                                    AyS+23349dWE
                              </v-alert>
                            </div>

                        `,
                        data: () => {
                          return{
                               rules2: [
                                 value => !!value || 'Required.'

                               ],
                               email:'',
                               emails:[],
                               code:'',
                               options: asemt_option,
                               alert: false,
                         }
                         },
                         methods:{
                           update(){
                              _this = this

                               if(_this.options.length !== 0 && _this.emails.length !== 0 && _this.code !== ''){
                                   var exist = false;
                                   for(i=0;i<_this.options.length;i++){

                                        if(_this.options[i].code == _this.code){
                                          exist = true
                                          break;
                                        }

                                   }
                                   if(exist == true){
                                      _this.alert = true
                                      setTimeout(function(){_this.alert = false}, 8000)
                                   }else{
                                     vm.swapComponent('view01');
                                     var xhttp = new XMLHttpRequest();
                                      xhttp.onreadystatechange = function() {
                                        if (this.readyState == 4 && this.status == 200) {

                                         var response = xhttp.response;

                                              console.log(response)
                                              if(response != 0){
                                                window.asemt_option = JSON.parse(response);
                                                setTimeout(function(){
                                                  vm.swapComponent('view02');
                                                },1500);
                                            }else{
                                              vm.swapComponent('view03');
                                            }
                                        }
                                      };
                                      xhttp.open("POST", asemt_ajax_url + '?action=asemt_change_settings_activate', true);
                                      xhttp.setRequestHeader("Content-type", "application/javascript");
                                      xhttp.send(JSON.stringify({'update':'1','email':this.emails,'code':this.code}));
                                   }

                                }else if(_this.emails.length !== 0 && _this.code !== ''){

                                      vm.swapComponent('view01');
                                      var xhttp = new XMLHttpRequest();
                                       xhttp.onreadystatechange = function() {
                                         if (this.readyState == 4 && this.status == 200) {

                                          var response = xhttp.response;

                                               console.log(response)
                                               if(response != 0){
                                                 window.asemt_option = JSON.parse(response);
                                                 setTimeout(function(){
                                                   vm.swapComponent('view02');
                                                 },1500);
                                             }else{
                                               vm.swapComponent('view03');
                                             }
                                         }
                                       };
                                       xhttp.open("POST", asemt_ajax_url + '?action=asemt_change_settings_activate', true);
                                       xhttp.setRequestHeader("Content-type", "application/javascript");
                                       xhttp.send(JSON.stringify({'update':'1','email':this.emails,'code':this.code}));
                                }


                           },
                            addEmail() {
                                    const value = this.email && this.email.trim();
                                    if (!value) {
                                      return;
                                    }

                                    this.emails.push(this.email);
                                    this.email = "";
                                  },

                            removeEmail(index) {
                                  this.emails.splice(index, 1);
                            },
                         }
            }

vm = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
    data: () => ({
      currentComponent: 'view02',
      main_hadline: 'Asemt Video',
      url: asemt_root+"wp-json/asemt/adlead"

    }),
    components: {

        'view01': loading,
        'view02': main,
        'view03': add
      },
      methods: {
        swapComponent: function(component) {
            this.currentComponent = component;
        }
    }
  })
