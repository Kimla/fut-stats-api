<template>
    <div class="mb-3">
        <label
            v-if="label"
            :for="name"
            class="inline-block mb-2"
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
        },

        name: {
            type: String,
            default: null
        }
    }
};
</script>
