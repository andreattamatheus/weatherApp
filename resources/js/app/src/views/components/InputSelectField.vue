<template>
    <div>
        <label class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2 block" for="country-select">
            {{ fieldLabel }}
        </label>
        <select @input="$emit('update:modelValue', $event.target.value)" :value="modelValue" id="country-select"
            class="flex h-10 w-full rounded-lg border bg-input px-3 py-2.5 text-sm caret-primary transition-all placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-background disabled:cursor-not-allowed disabled:opacity-50 focus-visible:ring-ring"
            required>
            <option disabled value="">Select a {{ fieldName }}</option>
            <option v-for="option in options" :key="option.code" :value="option.name">
                {{ option.name }}
            </option>
        </select>
        <span class="text-sm text-red-500 mt-1 ml-1" v-if="validationErrors.state">
            {{ validationErrors.state }}
        </span>
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
    options: {
        type: Array,
        required: false
    }
});


</script>

<style></style>
