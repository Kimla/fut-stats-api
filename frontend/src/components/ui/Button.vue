<template>
    <button
        :class="[bg]"
        class="uppercase w-full text-white p-3 rounded tracking-wider text-sm font-medium"
        :type="type"
    >
        {{ label }}
    </button>
</template>

<script>
export default {
    props: {
        label: {
            type: String,
            required: true
        },

        type: {
            type: String,
            default: 'button'
        },

        bg: {
            type: String,
            default: 'bg-indigo-500'
        }
    }
};
</script>

<style>
</style>
