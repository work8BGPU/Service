<template>
    <main class="main">
        <router-view></router-view>
    </main>
</template>

<script setup>
import { computed } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

const store = useStore();
const router = useRouter();

const token = computed(() => store.state.token);
const user = computed(() => store.state.user);

if (!(token.value && user.value)) router.push("/login");
</script>

<style>
.main {
    flex: 1;
    display: flex;
    flex-direction: column;
}
</style>
