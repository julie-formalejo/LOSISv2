<script setup>
import { ref, watch, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    // Pwedeng kontrolin manually kung gusto mo itago o ipakita
    show: {
        type: Boolean,
        default: false,
    },
    // Duration kung gaano katagal bago mawawala (in milliseconds)
    duration: {
        type: Number,
        default: 2000,
    },
});

const isLoading = ref(props.show);

// Para sa initial page load (katumbas ng DOMContentLoaded mo)
onMounted(() => {
    isLoading.value = true;
    setTimeout(() => {
        isLoading.value = false;
    }, props.duration);
});

// Awtomatikong lilitaw tuwing lumilipat ng page gamit ang Inertia.js
router.on('start', () => {
    isLoading.value = true;
});

router.on('finish', () => {
    setTimeout(() => {
        isLoading.value = false;
    }, 400); // Mabilis na fade out pagkatapos mag-load ng data
});
</script>

<template>
    <!-- =========================================================
       GLOBAL LOADER (Enhanced Executive Standard)
    ========================================================= -->
    <div
        v-if="isLoading"
        class="global-loader show"
        id="globalLoader"
        role="alert"
        aria-busy="true"
    >
        <div class="global-loader-card">

            <!-- Company / Corporate Security Icon & Animated Rings -->
            <div class="global-loader-icon">
                <i class="fa-solid fa-building-shield" aria-hidden="true"></i>
            </div>

            <!-- Loader Content, Status & Headings -->
            <div class="global-loader-content">
                <div class="global-loader-status">
                    Secure Portal<span>.</span><span>.</span><span>.</span>
                </div>
                <h5 class="global-loader-title">
                    Loading Workspace
                </h5>
                <p class="global-loader-text">
                    Initializing secure platform, please wait...
                </p>
            </div>

            <!-- Progress Track & Bar -->
            <div class="global-loader-track">
                <div class="global-loader-progress"></div>
            </div>

        </div>
    </div>
</template>
