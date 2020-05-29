<template>
    <button
        :class="[
            bg,
            {
                'p-3 text-sm': size === 'lg',
                'py-2 px-5 text-xs': size === 'sm'
            }
        ]"
        class="uppercase w-full text-white rounded tracking-wider font-medium"
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
        },

        size: {
            type: String,
            default: 'lg'
        }
    }
};
</script>

<style>
</style>
