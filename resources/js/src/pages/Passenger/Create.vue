<template>
    <section class="passenger_create">
        <div class="container passenger_create__container">
            <form @submit.prevent="create" class="form-main">
                <div class="form__blocks">
                    <div class="form__block">
                        <label for="lastname">Фамилия</label>
                        <input
                            class="input-main"
                            v-model="form.lastname"
                            type="text"
                            name="lastname"
                            placeholder="Иванов"
                        />
                        <p v-if="errors.lastname" class="error">
                            <small>{{ errors.lastname[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="name">Имя</label>
                        <input
                            class="input-main"
                            v-model="form.name"
                            type="text"
                            name="name"
                            placeholder="Иван"
                        />
                        <p v-if="errors.name" class="error">
                            <small>{{ errors.name[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="patronymic">Отчество</label>
                        <input
                            class="input-main"
                            v-model="form.patronymic"
                            type="text"
                            name="patronymic"
                            placeholder="Иванович"
                        />
                        <p v-if="errors.patronymic" class="error">
                            <small>{{ errors.patronymic[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="sex">Пол</label>
                        <Select
                            v-model="form.sex"
                            :options="sex"
                            optionLabel="title"
                            id="sex"
                            placeholder="Выберите значение"
                            class="select-main"
                        />
                        <p v-if="errors.sex" class="error">
                            <small>{{ errors.sex[0] }}</small>
                        </p>
                    </div>
                </div>
                <div class="form__blocks">
                    <div class="form__block">
                        <label for="category">Категория</label>
                        <Select
                            v-model="form.category"
                            editable
                            :options="categories"
                            optionLabel="short_title"
                            id="category"
                            placeholder="Выберите или укажите значение"
                            class="select-main"
                        />
                        <p v-if="errors.category" class="error">
                            <small>{{ errors.category[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="comment">Комментарий</label>
                        <textarea
                            class="input-main"
                            v-model="form.comment"
                            name="comment"
                            placeholder="Комментарий"
                            rows="5"
                        ></textarea>
                        <p v-if="errors.comment" class="error">
                            <small>{{ errors.comment[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="CP">ЭКС</label>
                        <Select
                            v-model="form.CP"
                            :options="cpValues"
                            optionLabel="title"
                            id="CP"
                            placeholder="Выберите значение"
                            class="select-main"
                        />
                        <p v-if="errors.CP" class="error">
                            <small>{{ errors.CP[0] }}</small>
                        </p>
                    </div>
                </div>
                <div class="form__blocks passenger__phones">
                    <div class="form__block">
                        <div class="passenger__phones__title">
                            <h3>Контактные номера</h3>
                            <button
                                type="button"
                                class="btn-phone btn-add_phone"
                                @click="addPhoneField"
                            >
                                <svg
                                    width="40"
                                    height="40"
                                    viewBox="0 0 40 40"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M9.58317 20C9.58317 19.0795 10.3294 18.3333 11.2498 18.3333H28.7498C29.6703 18.3333 30.4165 19.0795 30.4165 20C30.4165 20.9204 29.6703 21.6666 28.7498 21.6666L11.2498 21.6666C10.3294 21.6666 9.58317 20.9204 9.58317 20Z"
                                        fill="#1451EE"
                                    />
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M19.9998 9.58329C20.9203 9.58329 21.6665 10.3295 21.6665 11.25V28.75C21.6665 29.6704 20.9203 30.4166 19.9998 30.4166C19.0794 30.4166 18.3332 29.6704 18.3332 28.75V11.25C18.3332 10.3295 19.0794 9.58329 19.9998 9.58329Z"
                                        fill="#1451EE"
                                    />
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M19.9998 4.99996C11.7156 4.99996 4.99984 11.7157 4.99984 20C4.99984 28.2842 11.7156 35 19.9998 35C28.2841 35 34.9998 28.2842 34.9998 20C34.9998 11.7157 28.2841 4.99996 19.9998 4.99996ZM1.6665 20C1.6665 9.87474 9.87462 1.66663 19.9998 1.66663C30.1251 1.66663 38.3332 9.87474 38.3332 20C38.3332 30.1252 30.1251 38.3333 19.9998 38.3333C9.87462 38.3333 1.6665 30.1252 1.6665 20Z"
                                        fill="#1451EE"
                                    />
                                </svg>
                            </button>
                        </div>
                        <div
                            class="passenger__phone"
                            v-for="(phone, index) in form.phones"
                            :key="index"
                        >
                            <div class="form__block">
                                <label for="phone">Телефон</label>
                                <InputMask
                                    v-model="phone.phone"
                                    id="phone"
                                    mask="+9 (999) 999 99-99"
                                    placeholder="+9 (999) 999 99-99"
                                    unstyled
                                ></InputMask>
                                <p v-if="errors.phone" class="error">
                                    <small>{{ errors.phone[0] }}</small>
                                </p>
                            </div>
                            <div class="form__block">
                                <label for="description">Описание</label>
                                <textarea
                                    class="input-main"
                                    v-model="phone.description"
                                    name="description"
                                    placeholder="Описание"
                                    rows="3"
                                ></textarea>
                                <p v-if="errors.description" class="error">
                                    <small>{{ errors.description[0] }}</small>
                                </p>
                            </div>
                            <button
                                class="btn-phone btn-remove_phone"
                                type="button"
                                @click="removePhoneField(index)"
                            >
                                <svg
                                    width="40"
                                    height="40"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M5.75 12C5.75 11.4477 6.19772 11 6.75 11L17.25 11C17.8023 11 18.25 11.4477 18.25 12C18.25 12.5523 17.8023 13 17.25 13L6.75 13C6.19772 13 5.75 12.5523 5.75 12Z"
                                        fill="white"
                                    />
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3ZM1 12C1 5.92487 5.92487 1 12 1C18.0751 1 23 5.92487 23 12C23 18.0751 18.0751 23 12 23C5.92487 23 1 18.0751 1 12Z"
                                        fill="white"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <p v-if="errors.phones" class="error">
                        <small>{{ errors.phones[0] }}</small>
                    </p>
                </div>
                <button class="btn-main">Зарегистрировать пассажира</button>
            </form>
        </div>
    </section>
</template>

<script setup>
import Select from "primevue/select";
import InputMask from "primevue/inputmask";
import { handleUnauthorized } from "@/scripts/handleUnauthorized.js";

import { useStore } from "vuex";
import { useRouter } from "vue-router";
import { ref, onMounted } from "vue";

const form = ref({
    name: "",
    lastname: "",
    patronymic: "",
    sex: "",
    category: "",
    comment: "",
    CP: null,
    phones: [],
});

const addPhoneField = () => {
    form.value.phones.push({ phone: "", description: "" });
};

const removePhoneField = (index) => {
    form.value.phones.splice(index, 1);
};

const store = useStore();
const router = useRouter();

const categories = ref([]);
const errors = ref([]);

const sex = ref([
    { title: "Женский", id: 0 },
    { title: "Мужской", id: 1 },
]);

const cpValues = ref([
    { title: "Есть", id: 1 },
    { title: "Нет", id: 0 },
]);

const createData = () => {
    axios
        .get("/api/passengers/createData", {
            headers: { Authorization: `Bearer ${store.state.token}` },
        })
        .then((res) => {
            categories.value = res.data.data.categories;
        })
        .catch((error) => {
            if (error.response.status === 401) {
                handleUnauthorized(error).then(() => {
                    createData();
                });
            }
        });
};

const create = () => {
    for (let phone of form.value.phones) {
        phone.phone = phone.phone.replace(/\D/g, "");
    }
    errors.value = {};

    axios
        .post("/api/passengers", form.value, {
            headers: { Authorization: `Bearer ${store.state.token}` },
        })
        .then((res) => {
            if (res.status === 201) {
                router.push("/"); //TODO: push на заявку
            }
        })
        .catch((error) => {
            if (error.response.status === 422) {
                errors.value = error.response.data.errors;
            } else if (error.response.status === 401) {
                handleUnauthorized(error).then(() => {
                    create();
                });
            }
        });
};

createData();
</script>

<style scoped>
.passenger_create {
    margin-top: 7rem;
}

.passenger__phones {
    width: 100%;
}

.passenger__phones > .form__block {
    gap: 4rem;
}

.passenger__phones__title {
    display: flex;
    align-items: center;
    gap: 2.5rem;
}

.passenger__phone {
    display: flex;
    align-items: center;
    gap: 5rem;
}

.passenger__phone > .form__block {
    width: 35%;
}

.btn-phone svg {
    width: 4rem;
}

.btn-add_phone path {
    fill: var(--color-btn);
    transition: var(--transition);
}

.btn-add_phone:hover path {
    fill: var(--color-btn-hover);
    transition: var(--transition);
}

.btn-add_phone:active path {
    fill: var(--color-btn-active);
    transition: var(--transition);
}

.btn-remove_phone path {
    fill: red;
    transition: var(--transition);
}

.btn-remove_phone:hover path {
    fill: rgb(157, 2, 2);
}

.btn-remove_phone:active path {
    fill: rgb(110, 0, 0);
}
</style>
