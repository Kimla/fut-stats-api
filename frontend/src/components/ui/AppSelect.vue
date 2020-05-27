<template>
    <select
        :id="name"
        :value="value"
        :name="name"
        class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
        @input="$emit('input', $event.target.value)"
    >
        <option
            v-for="item in items"
            :key="item.value"
            :value="item.value"
        >
            {{ item.label }}
        </option>
    </select>
</template>

<script>
export default {
    props: {
        name: {
            type: String,
            required: true
        },

        value: {
            type: [Boolean, String, Number],
            default: null
        },

        items: {
            type: Array,
            required: true
        }
    }
};
</script>
