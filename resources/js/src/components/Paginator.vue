<template>
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item" :class="{ disabled: currentPage <= 1 }">
                <a
                    class="page-link"
                    href="#"
                    @click.prevent="changePage(currentPage - 1)"
                    >Предыдущая</a
                >
            </li>
            <li
                class="page-item page-number"
                :class="{ active: page === currentPage }"
                v-for="page in pages"
                :key="page"
            >
                <a
                    class="page-link"
                    href="#"
                    @click.prevent="changePage(page)"
                    >{{ page }}</a
                >
            </li>
            <li
                class="page-item"
                :class="{ disabled: currentPage >= totalPages }"
            >
                <a
                    class="page-link"
                    href="#"
                    @click.prevent="changePage(currentPage + 1)"
                    >Следующая</a
                >
            </li>
        </ul>
    </nav>
</template>

<script setup>
import { computed, defineProps } from "vue";

const props = defineProps({
    totalPages: Number,
    currentPage: Number,
});

const emit = defineEmits(["page-changed"]);

const pages = computed(() => {
    let pages = [];
    for (let i = 1; i <= props.totalPages; i++) {
        pages.push(i);
    }
    return pages;
});

function changePage(page) {
    if (page < 1 || page > props.totalPages) return;
    emit("page-changed", page);
}
</script>

<style scoped>
nav {
    display: flex;
    justify-content: flex-end;
    margin-top: 5rem;
}

.pagination {
    display: flex;
    align-items: center;
    list-style: none;
    padding: 0;
    gap: 0.5rem;
}

.page-item {    
    margin: 0 0.5rem;
}

.page-item.disabled .page-link,
.page-item.active .page-link {
    pointer-events: none;
}

.page-number {
    background-color: var(--color-btn);
    border-radius: 0.45rem;    
    padding: 0.5rem 1rem;
    transition: var(--transition);
}

.page-number a {
    color: #fff;
}

.page-number:hover {
    background-color: var(--color-btn-hover);
}

.page-number.active {
    background-color: var(--color-btn-active);
}
</style>
