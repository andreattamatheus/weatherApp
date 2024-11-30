<template>
    <div>
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
            {{ fieldLabel }}
        </label>
        <input type="text"  @input="$emit('update:modelValue', $event.target.value)" :value="modelValue" id="city"
            class="flex h-10 w-full rounded-lg border bg-input px-3 py-2.5 text-sm caret-primary transition-all placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-background disabled:cursor-not-allowed disabled:opacity-50 focus-visible:ring-ring"
            :placeholder="'Enter the ' + fieldName" required />
        <span class="text-sm text-red-500 mt-1 ml-1" v-if="validationErrors">{{
            validationErrors
            }}</span>
    </div>
</template>

<script setup>
const props = defineProps({
    modelValue: {
        type: String,
        required: false
    },
    fieldLabel: {
        type: String,
        required: true
    },
    fieldName: {
        type: String,
        required: true
    },
    validationErrors: {
        type: String,
        required: false
    },
});


</script>


<style></style>
