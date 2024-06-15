<template>
    <Header v-if="isAuth"></Header>
    <transition name="slide-aside">
        <Aside v-if="isAuth && isAsideOpen"></Aside>
    </transition>
    <main class="main" :class="{ main_collapsed: isAuth && isAsideOpen, main_expanded: !isAsideOpen }">
        <router-view></router-view>
    </main>
</template>

<script setup>
import Header from "./components/Header.vue";
import Aside from "./components/Aside.vue";
import { handleUnauthorized } from "@/scripts/handleUnauthorized.js";

import { computed } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import axios from "axios";

const store = useStore();
const router = useRouter();

const token = computed(() => store.state.token);
const isAuth = computed(() => store.state.isAuth);
const isAsideOpen = computed(() => store.state.isAsideOpen)

if (!token.value) {
    store.commit("logout");
    router.push("/login");
}

const takeUser = () => {
    if (store.state.token || !store.state.user) {
        axios
        .post(
            "/api/me",
            {},
            { headers: { Authorization: `Bearer ${store.state.token}` } }
            )
            .then((res) => {
                store.commit("setUser", res.data.user);
                store.commit("setIsAuth", true);
            })
            .catch((error) => {
                if (error.response.status === 401) {
                    handleUnauthorized(error).then(() => takeUser());
                }
            });
    }
};

takeUser();
</script>

<style>
.main {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.main {
  transition: margin-left 0.3s ease;
}

.main_collapsed {
  margin-left: 29rem; /* ширина aside + отступ */
}

.main_expanded {
  margin-left: 0;
}

.slide-aside-enter-active {
  transition: all 0.3s ease-out;
}

.slide-aside-leave-active {
  transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-aside-enter-from,
.slide-aside-leave-to {
  transform: translateX(-30rem);
  opacity: 0;
}

</style>
