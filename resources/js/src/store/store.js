import { createStore } from "vuex";
import createPersistedState from 'vuex-persistedstate';

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
        auth(state, { user, token }) {
            store.commit("setIsAuth", true);
            store.commit("setUser", user);
            store.commit("setToken", token);
        },
        logout(state) {
            store.commit("setIsAuth", false);
            store.commit("setUser", null);
            store.commit("setToken", null);
        }
    },
    plugins: [createPersistedState()],
});

export default store;
