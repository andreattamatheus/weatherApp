<template>
    <div>
        <div @click="deleteUserLocation(location.id, location.date)"
            class="absolute top-4 right-6 cursor-pointer text-red-700 hover:text-red-800" title="Delete">
            <i class="fas fa-trash"></i>
        </div>
        <a href="https://www.freefaces.gallery/" target="_blank" rel="noopener noreferrer"
            class="relative flex w-full gap-4  bg-card px-4 py-3 transition-all duration-200 hover:bg-accent focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background max-md:items-center md:flex-col md:justify-between md:p-6 md:py">
            <div class="size-[50px] flex-shrink-0 rounded-lg border p-0.5 flex justify-between space-x-2 items-center">
                <img alt="Free Faces" :src="getIcon(location.icon)" class="size-full select-none rounded-md"
                    loading="lazy">

                <p class="desc">{{ location.condition }}</p>
            </div>
            <div class="flex gap-4 max-md:flex-1 max-md:items-center max-md:justify-between md:flex-col md:gap-4">
                <div class="flex flex-col gap-2">
                    <h3
                        class="line-clamp-2 break-all text-base font-semibold md:font-display md:text-xl md:tracking-tighter">
                        <b>{{ location.city }}</b> - {{ location.date }}
                    </h3>
                </div>
                <div class="flex justify-center align-center">
                    <p class="text-red mr-1">{{ location.max_temperature }} ° / </p>
                    <p class="text-blue"> {{ location.min_temperature }} °</p>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <div
                        class="hidden items-center gap-2 overflow-hidden rounded-full bg-navy-black-50/5 py-1.5 pl-2 pr-3 text-muted-foreground md:flex">
                        <svg viewBox="0 0 20 20" fill="none" class="size-5 flex-shrink-0">
                            <path fill="currentColor" fill-rule="evenodd"
                                d="M10.553 4.562a4.715 4.715 0 0 1 6.665 6.665l-.007.007-1.876 1.876q-.24.24-.509.44l-.001.002a4.713 4.713 0 0 1-6.597-.952.625.625 0 1 1 1-.749 3.463 3.463 0 0 0 5.223.375l1.872-1.872a3.463 3.463 0 0 0-4.902-4.893.625.625 0 1 1-.868-.9"
                                clip-rule="evenodd"></path>
                            <path fill="currentColor" fill-rule="evenodd"
                                d="M6.008 7.726a4.713 4.713 0 0 1 6.597.952.625.625 0 1 1-1 .749 3.463 3.463 0 0 0-5.223-.375L4.51 10.924a3.463 3.463 0 0 0 4.902 4.893.625.625 0 0 1 .868.9 4.713 4.713 0 0 1-6.665-6.666l.007-.007 1.876-1.876q.24-.24.509-.44z"
                                clip-rule="evenodd"></path>
                        </svg><span class="truncate break-all text-sm">More info</span>
                    </div>
                    <div class="flex size-8 flex-shrink-0 items-center justify-center rounded-full border">
                        <svg viewBox="0 0 20 20" class="size-5" fill="none">
                            <path fill="currentColor" fill-rule="evenodd"
                                d="M7.708 6.667c0-.345.28-.625.625-.625h5c.345 0 .625.28.625.625v5a.625.625 0 1 1-1.25 0V8.176l-5.6 5.6a.625.625 0 1 1-.883-.885l5.6-5.6H8.332a.625.625 0 0 1-.625-.624"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </a>
    </div>

</template>

<script>
export default {
    name: 'Card',
    components: {},
    props: {
        location: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            imageUrl: process.env.VUE_APP_IMAGE_WEATHER_URL,
        }
    },
    watch: {},
    computed: {},
    methods: {
        deleteUserLocation(id, date) {
            this.$emit('delete', id, date)
        },
        getIcon(icon) {
            return this.imageUrl + `/${icon}@2x.png`;
        },
    },
    created() { },
    mounted() { }
}
</script>

<style></style>
