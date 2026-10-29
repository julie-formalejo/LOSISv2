<script setup>
import { computed } from 'vue';

const props = defineProps({
    // Halimbawa: 'client', 'admin', 'supervisor', 'superadmin', etc.
    portalType: {
        type: String,
        default: 'client',
    },
    // Pwedeng kontrolin kung ipapakita o itatago
    show: {
        type: Boolean,
        default: false,
    },
});

// Dynamic executive badge text bawat portal
const badgeText = computed(() => {
    const type = (props.portalType || 'client').toUpperCase();
    return `${type} EXECUTIVE PORTAL`;
});
</script>

<template>
    <!-- =========================================================
       WELCOME LOADER (Ultra-Premium Executive Standard)
    ========================================================= -->
    <div
        v-if="show"
        class="welcome-loader"
        id="welcomeLoader"
        role="alert"
        aria-busy="true"
    >
        <div class="loader-content-card">

            <!-- Top Gradient Glow Accent -->
            <div class="loader-card-glow"></div>

            <!-- Brand Logo -->
            <img
                src="/assets/images/lendell/LOSISv2.png"
                class="loader-logo"
                alt="LOSIS v2 Enterprise Portal"
            >

            <!-- Executive Status Badge -->
            <div class="loader-badge-wrapper">
                <span class="loader-badge">
                    <span class="badge-dot"></span>
                    {{ badgeText }}
                </span>
            </div>

            <!-- Professional Loading Message -->
            <p class="loader-subtitle">
                Establishing secure workspace session...
            </p>

            <!-- Premium Progress Track & Animation Element -->
            <div class="loader-track">
                <span class="loader-dot"></span>
            </div>

        </div>
    </div>
</template>
