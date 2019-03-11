# Twitch Streamer Event Listener

Live DEMO: [LIVE website](https://shielded-temple-18336.herokuapp.com/#/login)

This repo is an investment around utilization of best available solution around solving a simple problem of demostrating following traits:
- Third party authentication & authorization, OAuth+Twitch
- Third party video & chat streams embed, Twitch
- Enabling client to choose which stream he wants to listen to, without polling for events
- Enabling 3rd party webhooks to let application (server) know any event happened on a particular event client is listening to.
- Utilization of managed pubsub provider (pusher.com), for sending events to connected clients (push based model).
- Hosting application on a cloud provider, Heroku.
- Merge to master for deployment, KanBan Flow.

## Technologies used:

- [Lumen website](https://lumen.laravel.com/docs) : Microframework for managing application state which cant be exposed to clients: Laravel love!
- [Vue.JS website](https://vuejs.org/v2/guide/) : Frontend framework
- [Bootstrap](https://getbootstrap.com/docs) : Frontend styling
- Backend datastore: None! Persisting any data is not in scope.
- 3rd Party APIs: [Pusher](https://pusher.com/) | [Twitch](https://dev.twitch.tv/docs/)
- [GitHub website](https://github.com/tkant/StreamerEventViewer) Source code management
- [LIVE website](https://shielded-temple-18336.herokuapp.com/#/login) Deployment done over Heroku.

## Interfaces

This app consists of two different front facing pages:
- https://shielded-temple-18336.herokuapp.com/#/login -> LOGIN: This link will redirect you to authenticate using a twitch account. From this page you can sign-in (or sign-up) into twitch account.
- https://shielded-temple-18336.herokuapp.com/#/home#access_token=?..OTHER_PARAMS Once you authorize this application to use resources, you will be redirected to home page which have an option to enter twitch streamer name, username basically. 

## Project description

Once you enter a twitch streamer name and press stream button you will be able to see the live stream in iframe embedded space. This embed comes with the combination of live video stream and chat dialogue box to text and react on the events happening in the live video. The chat option is shared across all the users watching that stream, so you can find real people expressing themselves on the stream being played, using a combination of emoji and text.

Below this embed section you will see a placeholder to display real-time events happening on the stream using a combination of webhook and pubsub. Twitch sends a post request on the endpoint exposed for webhook request. This application then sends a message to pusher channel to transmit the received message to all connected clients. Since we are dependent on twitch streamer username, hence I had used streamer username as channel name. There's only one available topic this application subscribe to upon the *"Stream"* button click. Here is the reference of that webhook: https://dev.twitch.tv/docs/api/webhooks-reference/#topic-stream-changed

So if any events as mentioned under above reference happens, twitch makes a POST request on */api/callback*, this endpoint is exposed to handle both *GET* (for Subscription Verify Request (from Twitch to Client)) & *POST* request. Using the data received in post, code parses that packet to draft a sample message and makes a call to pusher for transmission of message to all connected client watching respective stream. 

On client, Vue is used to manage state and demonstrating a cleaner way of managing codebase. Using JS in vue's view, on click of *"Stream"* button event, the entered streamer name makes a websocket connection with Pusher's exposed server, termed as channel, its a kind of subscription to let client know any events happening around, a push based model with full duplex support.

## Hosting on AWS

![StreamerAppOnAWS](https://user-images.githubusercontent.com/11471896/54080764-7eaeac80-431d-11e9-96e5-d685c7f0022b.png)

Let me go though components one by one:
- EC2 Group -> Placed in horizontal scaling group in multiple Availability Zones. We can use containers (via ECS|EKS) as well.
- ELB ->  Load balancing incoming traffic
- S3 + Cloudfront (CDN) -> For hosting static assets, the compiled app.js & app.css used in conjunction with unique hash.
- Route53 -> For managing DNS records
- RDS -> Though this app wont uses any persistent data-store but for future enhancements we will for sure need it, hence added in Master / Slave configuration
- SNS -> In this app we had utilized Pusher for pubsub, on AWS it can be replaced with SNS. 
- Rest depends on how Twitch API is performing, since this app is highly dependent over that. But this architecture on AWS can handle millions of request. Scaling needs to be configured on the basis of *cloudwatch alarms* setted-up on top of CPU & Memory utilization matrices collected from each horizontally placed instances. Keep the minimum instance count, and set maximum as per budget and predetermined traffic case.


## Pusher PubSub DEMO:

![pusher_integration](https://user-images.githubusercontent.com/11471896/54080988-6db46a00-4322-11e9-91fe-e5f985d03d5a.gif)

Video link on youtube: https://www.youtube.com/watch?v=svDayUVu2gM

^^ Left side shows pusher dashboard, from where custom message is being sent on channel by streamer username.

^^ Right side shows a connected client watching a live stream, below there the client subscriber renders message upon receiving any message from pusher via websocket.

## Twitch webhook callback endpoint DEMO:

![twitch_callback_demo](https://user-images.githubusercontent.com/11471896/54096674-c8a59a00-43d2-11e9-942e-ee4f32d66f10.gif)

^^ Left side shows Postman interface where POST request is being made on the webhook callback API over "Tfue" channel.

^^ Right side shows a connected client watching a live stream, and upon callback completion pushes the message on "Tfue" channel in near realtime.