<template>
    <v-dialog v-model="dialog" persistent max-width="600px" min-width="360px" @click:outside="$emit('close')">
        <div>
            <v-tabs v-model="tab" show-arrows background-color="grey darken-3" icons-and-text dark grow>
                <v-tabs-slider ></v-tabs-slider>
                <v-tab v-for="i in tabs" :key="i.name">
                    <v-icon large>{{ i.icon }}</v-icon>
                    <div class="py-1" color="red">{{ i.name }}</div>
                </v-tab>
                <v-tab-item>
                    <v-card class="px-4">
                        <v-card-text>
                            <v-form ref="loginForm" v-model="valid" lazy-validation>
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field v-model="loginEmail" :rules="loginEmailRules" label="E-mail" required></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field v-model="loginPassword" :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" :rules="[rules.required, rules.min]" :type="show1 ? 'text' : 'password'" name="input-10-1" label="Пароль" hint="Минимум 8 символов" counter @click:append="show1 = !show1"></v-text-field>
                                    </v-col>
                                    <v-col class="d-flex" cols="12" sm="6" xsm="12">
                                    </v-col>
                                    <v-spacer></v-spacer>
                                    <v-col class="d-flex" cols="12" align-end>
                                        <v-btn x-large block :disabled="!valid" color="primary" @click="login()">Вход</v-btn>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </v-card-text>
                    </v-card>
                </v-tab-item>
                <v-tab-item>
                    <v-card class="px-4">
                        <v-card-text>
                            <v-form ref="registerForm" v-model="valid" lazy-validation>
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field v-model="firstName" :rules="[rules.required]" label="Имя" maxlength="20" required></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field v-model="email" :rules="emailRules" label="E-mail" required></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field v-model="password" :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" :rules="[rules.required, rules.min]" :type="show1 ? 'text' : 'password'" name="input-10-1" label="Пароль" hint="Минимум 8 символов" counter @click:append="show1 = !show1"></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field block v-model="verify" :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" :rules="[rules.required, passwordMatch]" :type="show1 ? 'text' : 'password'" name="input-10-1" label="Подтверждение пароля" counter @click:append="show1 = !show1"></v-text-field>
                                    </v-col>
                                    <v-spacer></v-spacer>
                                    <v-col class="d-flex ml-auto" cols="12">
                                        <v-btn x-large block :disabled="!valid" color="primary" @click="register()">Регистрация</v-btn>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </v-card-text>
                    </v-card>
                </v-tab-item>
            </v-tabs>
        </div>
    </v-dialog>
</template>

<script>
  export default {
    name: "LoginDialog",

    computed: {
      passwordMatch() {
        return () => this.password === this.verify || "Пароли не совпадают";
      }
    },
    methods: {
      reset() {
        this.$refs.form.reset();
      },
      resetValidation() {
        this.$refs.form.resetValidation();
      },
      login(){
        if (this.$refs.loginForm.validate()) {
          console.log('login');
          this.$emit('login', {email: this.loginEmail, password: this.loginPassword});
        }
      },
      register(){
        if (this.$refs.registerForm.validate()) {
          console.log('register');
          this.$emit('register', {name: this.firstName, email: this.email, password: this.password});
        }
      },
    },
    data: () => ({
      dialog: true,
      tab: 0,
      tabs: [
        {name:"Вход", icon:"mdi-account"},
        {name:"Регистрация", icon:"mdi-account-outline"}
      ],
      valid: false,

      firstName: "",
      email: "",
      password: "",
      verify: "",
      loginPassword: "",
      loginEmail: "",
      loginEmailRules: [
        v => !!v || "Обязательно к заполнению",
        v => /.+@.+\..+/.test(v) || "Ошибка в формате email"
      ],
      emailRules: [
        v => !!v || "Обязательно к заполнению",
        v => /.+@.+\..+/.test(v) || "Ошибка в формате email"
      ],

      show1: false,
      rules: {
        required: value => !!value || "Обязательно к заполнению",
        min: v => (v && v.length >= 8) || "Минимум 8 символов"
      }
    })
  }
</script>

<style scoped>

</style>
