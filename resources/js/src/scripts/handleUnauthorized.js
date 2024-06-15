import axios from "axios";
import store from "@/store/store.js";
import router from "@/router/router.js";

const handleUnauthorized = (error) => {
    if (error.response.status !== 401) {
        return Promise.reject(error);
    }
    return axios
        .post(
            "/api/refresh",
            {},
            { headers: { Authorization: `Bearer ${store.state.token}` } }
        )
        .then((res) => {
            store.commit("setToken", res.data.token);
            return Promise.resolve();
        })
        .catch((err) => {
            if (err.response.data.token) {
                store.commit("setToken", err.response.data.token);
                return Promise.resolve();
            }
            store.commit("logout");
            router.push("/login");
            return Promise.reject(err);
        });
};

export { handleUnauthorized };
