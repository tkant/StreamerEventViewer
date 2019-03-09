<template>
    <section class="section section-shaped section-lg mt-10">
        <div class="container">
            <form>
                <div class="form-group">
                    <h5>Choose and view different activities happening over your favorite stream</h5>
                    <div class="input-group mb-3">
                        <input v-model="channel_name" type="text" class="form-control"
                               placeholder="Set your favorite Twitch streamer name"
                               aria-label="Twitch's streamer name">
                        <div class="input-group-append">
                            <button class="btn btn-dark" v-on:click="embedTwitch" type="button">Stream</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="container">
            <div class="row mt-3">
                <div class="col-sm">
                    <div v-if="errors && errors.length">
                        <div v-for="error of errors">
                            <b-alert show dismissible fade variant="danger">Error: {{error.message}}</b-alert>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm">
                    <div id="twitch-embed"></div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm">
                    <h5>Recent Events <small class="text-muted">Topic: Stream Changed</small>
                    </h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" v-for="event in events">{{ event }}</li>
                    </ul>
                </div>
            </div>
        </div>

    </section>
</template>

<script>
  import axios from 'axios';
  import Pusher from 'pusher-js'

  export default {
    data() {
      return {
        channel_name    : '',
        streamer_id     : '',
        errors          : [],
        twitchOAuthToken: '',
        events          : [],
        ws              : '',
        twitchOAuthState: ''
      }
    },

    mounted() {
      if (localStorage.channel_name) {
        this.whenChannelNameSets();
      }

      this.twitchOAuthToken         = this.getHashValue('access_token');
      localStorage.twitchOAuthState = localStorage.twitchOAuthState === "undefined" ? this.nonce(15) : localStorage.twitchOAuthState;
      this.twitchOAuthState         = localStorage.twitchOAuthState;
      // For twitch embed:
      let twitchEmbed               = document.createElement('script');
      twitchEmbed.setAttribute('src', 'https://embed.twitch.tv/embed/v1.js');
      document.head.appendChild(twitchEmbed);
    },

    methods: {
      whenChannelNameSets() {
        this.channel_name = localStorage.channel_name;
      },
      getHashValue(key) {
        let matches = this.$route.hash.match(new RegExp(key + '=([^&]*)'));
        return matches ? matches[1] : null;
      },
      embedTwitch() {
        document.getElementById("twitch-embed").innerHTML = "";
        let embed = new Twitch.Embed("twitch-embed", {
          width   : 854,
          height  : 480,
          channel : localStorage.channel_name,
          layout  : "video-with-chat",
          autoplay: false
        });

        embed.addEventListener(Twitch.Embed.VIDEO_READY, () => {
          let player = embed.getPlayer();
          player.play();
        });

        // Get streamer user-id and invoke subscription to stream, web-hook!
        axios.get('./api/streamer/' + this.channel_name + '/' + this.twitchOAuthToken)
             .then(response => {
               // JSON responses are automatically parsed.
               this.streamer_id = response.data;
               let pusher = new Pusher('c66297d877b979b6d1f5', {
                 cluster: 'ap2'
               });
               pusher.logToConsole = true;
               // Subscribe for incoming messages from pubsub, channel organized by streamer name.
               pusher.subscribe(this.channel_name);
               pusher.bind('stream_changed', data => {
                 console.log('Incoming data via pusher..', data);
                 this.events.push(data.message);
               });
             })
             .catch(e => {
               this.errors.push(e)
             })
      }
    },

    watch  : {
      channel_name(newChannelName) {
        localStorage.channel_name = newChannelName;
        this.whenChannelNameSets();
      },
      '$route': 'embedTwitch'
    }
  };
</script>
<style>
</style>
