<template>
    <div class="mb-8">
        <Trophy class="text-indigo-500 w-16 h-16 mb-4" />

        <h1 class="text-2xl mb-1">
            {{ heading }}
        </h1>
        <p>
            {{ text }}
        </p>
    </div>
</template>

<script>
import Trophy from '@/assets/trophy.svg';

export default {
    components: {
        Trophy
    },

    props: {
        heading: {
            type: String,
            required: true
        },

        text: {
            type: String,
            required: true
        }
    }
};
</script>
