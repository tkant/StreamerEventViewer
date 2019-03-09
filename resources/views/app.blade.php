<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.5, user-scalable=1, shrink-to-fit=no" name="viewport">
    <meta name="description" content="Start your development with a Design System for Bootstrap 4 and Vue.js.">
    <meta name="author" content="Creative Tim, Cristi Jora">
    <link rel="icon" href="/favicon.png">
    <title>Twitch Listener</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="/dist/app.css" />
  </head>
  <body>
    <noscript>
      <strong>We're sorry but this system doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
    </noscript>
    <div id="app">
        <h1>Hello App!</h1>
        {{--<p>--}}
            {{--<router-link to="/foo">Go to Foo</router-link>--}}
            {{--<router-link to="/bar">Go to Bar</router-link>--}}
        {{--</p>--}}

        {{--<router-view></router-view>--}}
    </div>

    <!-- Add a placeholder for the Twitch embed -->
    {{--<div id="twitch-embed"></div>--}}

    {{--<!-- Load the Twitch embed script -->--}}
    {{--<script src="https://embed.twitch.tv/embed/v1.js"></script>--}}

    {{--<!-- Create a Twitch.Embed object that will render within the "twitch-embed" root element. -->--}}
    {{--<script type="text/javascript">--}}
      {{--new Twitch.Embed("twitch-embed", {--}}
        {{--width: 854,--}}
        {{--height: 480,--}}
        {{--channel: "monstercat"--}}
      {{--});--}}
    {{--</script>--}}

    <script src="/dist/app.js"></script>
  </body>
</html>
