<template>
    <Header v-if="isAuth"></Header>
    <main class="main">
        <router-view></router-view>
    </main>
</template>

<script setup>
import Header from "./components/Header.vue";
import { handleUnauthorized } from "@/scripts/handleUnauthorized.js";

import { computed, ref, onMounted } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import axios from "axios";

const store = useStore();
const router = useRouter();

const token = computed(() => store.state.token);
const isAuth = computed(() => store.state.isAuth);

if (!token.value) {
    store.commit("logout");
    router.push("/login");
}

const takeUser = () => {
    if (store.state.token) {
        axios
            .post(
                "api/me",
                {},
                { headers: { Authorization: `Bearer ${store.state.token}` } }
            )
            .then((res) => {
                store.commit("setUser", res.data.user);
            })
            .catch((error) => {
                if (error.response.status === 401) {
                    handleUnauthorized(error).then(() => takeUser());
                }
            });
    }
};

onMounted(() => {
    takeUser();
});
</script>

<style>
.main {
    flex: 1;
    display: flex;
    flex-direction: column;
}
</style>
