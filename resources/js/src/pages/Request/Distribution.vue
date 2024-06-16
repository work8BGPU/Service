<template>
    <section class="distribution">
        <div class="container distributon__container">
            <button @click="distribution" class="btn-main">Распределить</button>
        </div>
    </section>
</template>

<script setup>
import { useStore } from "vuex"
import axios from "axios"
import { handleUnauthorized } from "@/scripts/handleUnauthorized.js";

// const distribution = () => {
//     axios.get("/api/requests/distribution").then(response => {
//         console.log(response);
//     }).catch(error => {
//     })
// }

</script>

<style scoped>
.distribution {
    margin-top: 7rem;
}
</style>