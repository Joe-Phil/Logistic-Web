<script setup lang="ts">
withDefaults(defineProps<{
    class?: string;
}>(), {
    class: '',
});
</script>

<template>
    <svg
        viewBox="0 0 24 24"
        fill="currentColor"
        xmlns="http://www.w3.org/2000/svg"
        :class="class"
    >
        <!-- Simple box/package icon for logistics -->
        <path
            d="M3 7L12 2L21 7V17L12 22L3 17V7Z"
        />
    </svg>
</template>

