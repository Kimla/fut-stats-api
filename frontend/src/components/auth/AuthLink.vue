<template>
    <div class="text-center">
        <router-link
            :to="url"
            class="text-indigo-600 font-medium tracking-wide text-sm"
        >
            {{ title }}
        </router-link>
    </div>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            required: true
        },

        url: {
            type: String,
            required: true
        }
    }
};
</script>
