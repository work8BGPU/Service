import { createStore } from "vuex";

const store = createStore({
    state() {
        return {
            isAuth: false,
            user: null,
            token: null,
        };
    },
    mutations: {
        setIsAuth(state, value) {
            state.isAuth = value;
        },
        setUser(state, user) {
            state.user = user;
        },
        setToken(state, token) {
            state.token = token;
        },
        setAuth(state, user, token) {
            store.commit("setIsAuth", true);
            store.commit("setUser", user, token);
        },
    },
});

export default store;
