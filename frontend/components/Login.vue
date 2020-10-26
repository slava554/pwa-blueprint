<template>
    <div>
        <div id="logged" v-if="$nuxt.user.isLoggedIn">
            {{$nuxt.user.name}}
            <v-tooltip bottom>
                <template v-slot:activator="{ on: tooltip }">
                    <v-btn icon @click="logoutUser()" v-on="{ ...tooltip }">
                        <v-icon>mdi-export</v-icon>
                    </v-btn>
                </template>
                <span>Выход</span>
            </v-tooltip>
        </div>
        <div id="not-logged" v-else>
            <v-btn elevation="2" color="primary" @click="showLogin = !showLogin">Вход</v-btn>
        </div>
        <login-dialog v-if="showLogin" @login="loginUser" @register="registerUser" @close="showLogin=false"/>

    </div>
</template>

<script>
  import LoginDialog from "./LoginDialog";

  export default {
    name: "Login",

    comments:[LoginDialog],

    data:()=>({
      showLogin: false,
    }),

    methods: {
      async loginUser(event){
        console.log('loginUser', event);
        try{
          const resp = await this.$axios.$post($nuxt.backend.url + 'login',{
            email: event.email,
            password: event.password,
          });
          console.log('вошли');
          $nuxt.user.token = resp.token;
          $nuxt.user.isLoggedIn = true;
          $nuxt.user.name = resp.user.name;
          window.localStorage.setItem('token', resp.token);
          this.$axios.defaults.headers.common.Authorization = 'Bearer ' + resp.token;
          this.showLogin = false;
        } catch (e) {
          console.log('неверные логин, или пароль');
        }
      },

      async registerUser(event){
        console.log('registerUser', event);
        try {
          const resp = await this.$axios.$post($nuxt.backend.url + 'register',{
            name: event.name,
            email: event.email,
            password: event.password,
          });
          console.log('зарегистрировались');
          $nuxt.user.token = resp.token;
          $nuxt.user.isLoggedIn = true;
          $nuxt.user.name = event.name;
          window.localStorage.setItem('token', resp.token);
          this.$axios.defaults.headers.common.Authorization = 'Bearer ' + resp.token;
          this.showLogin = false;
        } catch (e) {
          document.location.reload();
        }
      },

      async logoutUser(){
        console.log('logoutUser');
        window.localStorage.removeItem('token');
        const resp = await this.$axios.$post($nuxt.backend.url + 'logout');
        $nuxt.user.token = null;
        $nuxt.user.isLoggedIn = false;
        document.location.reload();
      },

      async checkLogin(){
        console.log('checkLogin');
        if($nuxt.user.token === null){
          $nuxt.user.token = window.localStorage.getItem('token');
        }
        let token = $nuxt.user.token;
        if(token === null){
          console.log('не вошли');
          return false;
        }
        this.$axios.defaults.headers.common.Authorization = 'Bearer ' + token;
        try{
          const resp = await this.$axios.$post($nuxt.backend.url + 'check-login');
          console.log('вошли', resp);
          $nuxt.user.isLoggedIn = true;
          $nuxt.user.name = resp.user.name;
        }catch(e){
          $nuxt.user.isLoggedIn = false;
          $nuxt.user.token = null;
          window.localStorage.removeItem('token');
          console.log('не получилось войти');
        }
      },

    },

    async created(){
      console.log('login created');
      //$nuxt.user.token = '8|LgWvFYTI0KbePNlWAfJkJHlRTcfHVij9GFNn3pB8';
      await this.checkLogin();
    },
  }
</script>

<style scoped>

</style>
