<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

defineProps<{
    href: string
}>()
</script>

<template>
    <Link
        :href="href"
        class="text-blue-600 hover:text-blue-800 hover:underline"
    >
        <slot />
    </Link>
</template>
