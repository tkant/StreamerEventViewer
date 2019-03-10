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
                    <div class="embed-responsive embed-responsive-16by9">
                        <div id="twitch-embed" class="embed-responsive-item"></div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm" v-if="events[0]">
                    <h5>Recent Events <small class="text-muted">Topic: Stream Changed</small>
                    </h5>
                    <div class="events-wrapper">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item" v-for="event in events">{{ event }}</li>
                        </ul>
                    </div>
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
        pusher          : new Pusher('c66297d877b979b6d1f5', {
          cluster: 'ap2'
        })
      }
    },

    mounted() {
      if (localStorage.channel_name) {
        this.whenChannelNameSets();
      }

      this.twitchOAuthToken = this.getHashValue('access_token');
      // For twitch embed:
      let twitchEmbed = document.createElement('script');
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
          width   : '100%',
          height  : 'auto',
          channel : localStorage.channel_name,
          layout  : "video-with-chat",
          theme   : 'dark',
          autoplay: false
        });

        embed.addEventListener(Twitch.Embed.VIDEO_READY, () => {
          let player = embed.getPlayer();
          player.play();
        });

        // Get streamer user-id and invoke subscription to stream, web-hook!
        axios.get('./api/streamer/' + this.channel_name + '/' + this.twitchOAuthToken)
             .then(response => {
               // Empty the events data:
               this.events = [];
               // JSON responses are automatically parsed.
               let stream_message = response.data.message;
               if (stream_message !== '') {
                 this.events.unshift(stream_message);
               }

               this.pusher.logToConsole = true;
               // Subscribe for incoming messages from pubsub, channel organized by streamer name.
               this.pusher.subscribe(this.channel_name);
               this.pusher.bind('stream_changed', data => {
                 console.log('Incoming data via pusher..', data);
                 this.events.unshift(data.message);
               });
             })
             .catch(e => {
               this.errors.push(e)
             })
      }
    },

    watch  : {
      channel_name(newChannelName) {
        // Disconnect any old WS
        let old_channel = localStorage.channel_name;
        this.pusher.unsubscribe(old_channel);
        // Unbind all events, this is to remove the duplicated message being recieved
        this.pusher.unbind_all(() => {
          localStorage.channel_name = newChannelName;
          this.whenChannelNameSets();
        });
      },
      '$route': 'embedTwitch'
    }
  };
</script>
<style>
</style>
