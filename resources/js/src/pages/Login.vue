<template>
    <section class="login">
        <div class="container login__container">
            <div class="login__block">
                <h1>Авторизация</h1>
                <form @submit.prevent="login">
                    <div class="form__block">
                        <label for="phone">Номер телефона</label>
                        <InputMask
                            v-model="form.phone"
                            id="phone"
                            mask="+9 (999) 999 99-99"
                            placeholder="+9 (999) 999 99-99"
                        ></InputMask>
                    </div>
                    <p v-if="errors.phone" class="error">
                        <small>{{ errors.phone[0] }}</small>
                    </p>
                    <div class="form__block">
                        <label for="password">Пароль</label>
                        <input
                            v-model="form.password"
                            class="login__input"
                            type="password"
                            name="password"
                            placeholder="password"
                        />
                    </div>
                    <p v-if="errors.password" class="error">
                        <small>{{ errors.password[0] }}</small>
                    </p>
                    <div class="form__block checkbox">
                        <input
                            v-model="form.remember"
                            type="checkbox"
                            name="remember"
                            value="1"
                        />
                        <label for="remember">Запомнить меня</label>
                    </div>
                    <p v-if="errors.incorrect" class="error">
                        <small>{{ errors.incorrect }}</small>
                    </p>
                    <button class="btn-main">Войти</button>
                </form>
            </div>
        </div>
    </section>
</template>

<script setup>
import InputMask from "primevue/inputmask";

import axios from "axios";
import { ref } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

const form = ref({
    phone: "",
    password: "",
    remember: false,
});

const errors = ref({});

const router = useRouter();

const store = useStore();

const login = () => {
    form.value.phone = form.value.phone.replace(/\D/g, "");
    errors.value = {};

    axios
        .post("/api/login", form.value)
        .then((res) => {
            if (res.status === 201) {
                store.commit("setAuth", res.data.user, res.data.token);
                router.push("/");
            }
        })
        .catch((error) => {
            if (error.response.status === 422) {
                errors.value = error.response.data.errors;
            } else if (error.response.status === 401) {
                errors.value.incorrect = error.response.data.message;
            }
        });
};
</script>

<style scoped>
.login {
    margin: auto 0;
    padding: 0;
}

.login__block {
    align-self: center;
    border-radius: 2rem;
    box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.4);
    padding: 5rem 10rem;
    width: fit-content;
}

.login h1 {
    margin-bottom: 5rem;
    text-align: center;
}

.p-inputtext,
.login__input {
    border: 0.1rem solid var(--color-second);
    border-radius: var(--border-radius);
    color: var(--color-second);
    font-size: var(--font-size-normal);
    padding: 1rem;
    width: 100%;
}

.login form {
    width: 100%;
}
</style>
