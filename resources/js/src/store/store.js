import { createStore } from "vuex";

const store = createStore({
    state() {
        return {
            isAuth: false,
            user: null,
        };
    },
    mutations: {
        setIsAuth(state, value) {
            state.isAuth = value;
        },
        setUser(state, user) {
            state.user = user;
        },
        setAuth(state, user) {
            store.commit("setIsAuth", true);
            store.commit("setUser", user);
        },
    },
});

export default store;
