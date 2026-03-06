import axios from "axios";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.axios = axios;

// Basic headers
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Enable credentials and XSRF token for Sanctum authentication
window.axios.defaults.withCredentials = true;
// Laravel automatically looks for the XSRF token in the XSRF-TOKEN cookie,
// so you don’t need withXSRFToken (it’s not a valid axios option).
// Just ensure cookies are sent with requests.

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    authorizer: (channel, options) => {
        return {
            authorize: (socketId, callback) => {
                window.axios.post("/api/broadcasting/auth", {
                    socket_id: socketId,
                    channel_name: channel.name,
                })
                .then(response => {
                    callback(false, response.data);
                })
                .catch(error => {
                    callback(true, error);
                });
            },
        };
    },
});
