<template>
    <button
        class="w-16 h-16 bg-indigo-500 text-white rounded-full flex items-center justify-center fixed right-0 bottom-0 shadow-xl m-6 z-10"
        type="button"
    >
        <Plus class="w-8 h-8" />
    </button>
</template>

<script>
import Plus from '@/assets/plus.svg';

export default {
    components: {
        Plus
    }
};
</script>
