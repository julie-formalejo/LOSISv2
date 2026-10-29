<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    // Halimbawa: 'client', 'admin', 'supervisor', 'superadmin', etc.
    portalType: {
        type: String,
        default: 'client',
    },
});

const currentYear = new Date().getFullYear();

// Dynamic route prefix at version bawat portal
const portalConfig = computed(() => {
    const type = (props.portalType || 'client').toLowerCase();
    return {
        prefix: `/${type}`,
        version: `v2.0-${type}`,
    };
});
</script>

<template>
    <!-- =========================================================
       PORTAL FOOTER (Enhanced Executive Standard)
    ========================================================= -->
    <footer class="portal-footer">

        <!-- Left: Copyright & Company Details -->
        <div class="footer-left">
            <span class="footer-copyright">
                &copy; {{ currentYear }} <strong>LOSIS v2</strong> — Lendell Outsourcing Solutions Inc.
            </span>
            <span class="footer-rights-text">All rights reserved.</span>
        </div>

        <!-- Center: System Status & Version -->
        <div class="footer-center">
            <div class="system-status">
                <span class="status-dot animate-pulse"></span>
                <span class="status-text">All systems operational</span>
            </div>
            <span class="footer-divider">•</span>
            <span class="system-version">{{ portalConfig.version }}</span>
        </div>

        <!-- Right: Enterprise Navigation Links -->
        <div class="footer-right">
            <nav class="footer-nav" aria-label="Footer Navigation">
                <Link :href="`${portalConfig.prefix}/privacy`" class="footer-link">
                    Privacy Policy
                </Link>
                <Link :href="`${portalConfig.prefix}/terms`" class="footer-link">
                    Terms of Service
                </Link>
                <Link :href="`${portalConfig.prefix}/help`" class="footer-link">
                    Support & Help
                </Link>
            </nav>
        </div>

    </footer>
</template>
