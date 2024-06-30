<template>
    <section class="distribution">
        <div class="container distributon__container">
            <button @click="distribution" class="btn-main">Распределить</button>
            <p class="distr-coming" v-if="isDistribution">Идет распределение...</p>
        </div>
    </section>
</template>

<script setup>
import { useStore } from "vuex";
import axios from "axios";
import { ref } from "vue";
import { handleUnauthorized } from "@/scripts/handleUnauthorized.js";

const store = useStore();

const isDistribution = ref(false);

const distribution = () => {
    console.log("Распределение пошло");
    isDistribution.value = true;
    axios
        .get("/api/requests/distribution", {headers: {Authorization: `Bearer ${store.state.token}`}})
        .then((response) => {
            console.log(response);
            isDistribution.value = false;
        })
        .catch((error) => {
            if (error.response.status === 401) {
                handleUnauthorized(error).then(() => {
                    distribution();
                });
            }
            isDistribution.value = false;
        });
};
</script>

<style scoped>
.distribution {
    margin-top: 7rem;
}

.distr-coming {
    margin-top: 2rem;
}
</style>
