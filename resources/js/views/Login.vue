<template>
    <section class="section section-shaped section-lg mt-5">
        <div class="container pt-lg-md">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="text-muted text-center mb-3">
                        <small>Sign in with</small>
                    </div>
                    <img slot="icon" v-on:click="fetchData" class="img-thumbnail" src="img/icons/common/twitch_logo.png">
                    <div class="loading" v-if="loading">
                        Loading...
                    </div>

                    <div v-if="error" class="error">
                        {{ error }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
  export default {
    data() {
      return {
        loading: false,
        post   : null,
        error  : null
      }
    },
    created() {
      // fetch the data when the view is created and the data is
      // already being observed
      this.fetchData()
    },
    watch  : {
      // call again the method if the route changes
      '$route': 'fetchData'
    },
    methods: {
      fetchData() {
        this.error = this.post = null;
        this.loading = true;
        let that     = this;

        fetch('./api/auth')
          .then(
            function (response) {
              if (response.status !== 200) {
                console.log('Looks like there was a problem. Status Code: ' +
                            response.status);
                return;
              }

              // Examine the text in the response
              response.text().then(function (data) {
                // Redirect user to twitch's auth URL
                that.loading = false;
                window.location.href =  data;
              });
            }
          )
          .catch(function (err) {
            console.log('Fetch Error :-S', err);
          });
      }
    }
  };
</script>
<style>
</style>
