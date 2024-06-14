<template></template>

<script setup>
import axios from "axios";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

const store = useStore();
const router = useRouter();

const refreshToken = async () => {
    try {
        const response = await axios.post("/api/refresh");
        const token = response.data.token;
        store.commit("auth", token);
        return token;
    } catch (error) {
        router.push("/login");
        throw error;
    }
};

const handleUnauthorized = async (error) => {
    if (error.response.status === 401) {
        const originalRequest = error.config;
        if (!originalRequest._retry) {
            originalRequest._retry = true;
            const token = await refreshToken();
            originalRequest.headers.Authorization = `Bearer ${token}`;
            return axios(originalRequest);
        } else {
            store.commit("logout");            
            router.push("/login");
        }
    }
    return Promise.reject(error);
};
</script>
