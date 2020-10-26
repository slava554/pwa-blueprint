<template>
    <v-row dense>
        <v-col cols="12">
            <v-card elevation="2">
                <v-card-title class="headline">
                    Pusher
                </v-card-title>
                <v-form v-if="$nuxt.user.isLoggedIn">
                    <v-container>
                        <v-text-field
                                label="Написать всем"
                                placeholder="Написать всем"
                                solo
                                v-model="text"
                        ></v-text-field>
                        <v-btn elevation="2" color="primary" block @click="sendText()">Отправить</v-btn>
                    </v-container>
                </v-form>
                <v-list>
                    <v-list-item-group>
                        <v-list-item
                                v-for="item in received"
                                :key="item.time"
                        >
                            <v-list-item-content>
                                <v-list-item-title v-text="item.text"></v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-item-group>
                </v-list>

            </v-card>
        </v-col>
    </v-row>
</template>

<script>
  export default {
    name: "Pusher",

    data:()=>({
      pusher: null,
      channel: null,
      text: '',
      received: [],
    }),

    methods:{
      async sendText(){
        console.log(this.text);
        try{
          const resp = await this.$axios.$post($nuxt.backend.url + 'pusher',{
            text: this.text,
          });
          console.log('отправили');
          this.text = '';
        } catch (e) {
          console.log('не получилось отправить сообщение');
        }
      },

    },

    async created(){
      console.log('Pusher created');
      Pusher.logToConsole = true;

      this.pusher = new Pusher($nuxt.backend.pusher.key, {
        cluster: $nuxt.backend.pusher.cluster
      });

      this.channel = this.pusher.subscribe($nuxt.backend.pusher.channel);
      this.channel.bind($nuxt.backend.pusher.event, (data)=>{
        console.log('pusher get', data);
        this.received.push({
          text: data.text,
          time: Date.now(),
        });
      });

    },
  }
</script>
