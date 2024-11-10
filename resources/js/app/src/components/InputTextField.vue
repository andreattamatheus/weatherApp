<template>
    <div>
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
            {{ fieldLabel }}
        </label>
        <input type="text" :value="value" id="city"
            class="flex h-10 w-full rounded-lg border bg-input px-3 py-2.5 text-sm caret-primary transition-all placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-background disabled:cursor-not-allowed disabled:opacity-50 focus-visible:ring-ring"
            placeholder="Enter the city name" required />
        <span class="text-sm text-red-500 mt-1 ml-1" v-if="this.validationErrors">{{
            this.validationErrors
            }}</span>
    </div>
</template>

<script>
export default {
    name: 'InputTextField',
    components: {},
    props: {
        fieldLabel: {
            type: String,
            required: false
        },
        value: {
            type: String,
            required: false
        },
        validationErrors: {
            type: String,
            required: false
        },
    },
    data() {
        return {
        }
    },
    watch: {},
    methods: {},
    created() { },
    mounted() { }
}
</script>

<style></style>
