<template>
    <div class="mb-3">
        <label
            v-if="label"
            class="inline-block mb-2"
            for="outcome"
        >
            {{ label }}
        </label>

        <slot />
    </div>
</template>

<script>
export default {
    props: {
        label: {
            type: String,
            default: null
        }
    }
};
</script>
