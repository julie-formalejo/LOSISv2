<script setup>
import { ref, computed, watch, nextTick, onMounted, onUnmounted } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import { PORTAL_CONFIGS, GLOBAL_PAGE_STYLES } from '@/Config/portalStyles';

const props = defineProps({
    // Optional: Kung hindi ipapasa, kusa niyang kukunin sa URL o sa User Session
    portal: {
        type: String,
        default: null,
    },
    // Optional: Kung hindi ipapasa, kusa niyang kukunin sa URL (hal. /client/dashboard -> 'dashboard')
    pageName: {
        type: String,
        default: null,
    },
    showWelcomeLoader: {
        type: Boolean,
        default: false,
    },
});

const page = usePage();
const isSidebarCollapsed = ref(false);

// 1. AUTO-DETECT PORTAL (Mula sa Prop -> URL -> User Session -> fallback 'client')
const activePortalKey = computed(() => {
    if (props.portal) {
        return props.portal.toLowerCase();
    }

    // Subukang kunin sa unang bahagi ng URL (hal. "/superadmin/dashboard" -> "superadmin")
    const urlSegments = page.url.split('?')[0].split('/').filter(Boolean);
    const firstSegment = urlSegments[0]?.toLowerCase().replace('-', '');
    if (firstSegment && PORTAL_CONFIGS[firstSegment]) {
        return firstSegment;
    }

    // Kunin sa logged-in user o fallback sa 'client'
    return (page.props.auth?.user?.portal_type || 'client').toLowerCase();
});

const currentPortal = computed(() => {
    return PORTAL_CONFIGS[activePortalKey.value] || PORTAL_CONFIGS.client;
});

// 2. AUTO-DETECT CURRENT PAGE (Mula sa Prop -> URL ?page= -> URL path -> 'dashboard')
const activePageName = computed(() => {
    if (props.pageName) {
        return props.pageName.toLowerCase();
    }

    // Check kung may ?page=manage-endorsements sa URL (gaya ng $_GET['page'] sa PHP mo)
    const queryString = page.url.split('?')[1];
    if (queryString) {
        const params = new URLSearchParams(queryString);
        if (params.get('page')) {
            return params.get('page').toLowerCase();
        }
    }

    // Kunin ang huling segment ng URL (hal. "/client/manage-endorsements" -> "manage-endorsements")
    const urlSegments = page.url.split('?')[0].split('/').filter(Boolean);
    const lastSegment = urlSegments[urlSegments.length - 1]?.toLowerCase();

    if (lastSegment && lastSegment !== activePortalKey.value) {
        return lastSegment;
    }

    return 'dashboard';
});

// 3. DYNAMIC CSS LOADER BASE SA PORTAL AT PAGE
const activePortalStyles = computed(() => {
    const { folder, styles } = currentPortal.value;
    const current = activePageName.value;

    // Kung ang current page ay nasa portal CSS list, iyon lang ang i-load
    if (current && styles.includes(current)) {
        return [{
            main: `/assets/css/portals/${folder}/${current}.css`,
            responsive: `/assets/css/responsive/portals/${folder}/${current}-responsive.css`,
        }];
    }

    // Kung Global Page (hal. my-profile, my-settings, my-notifications)
    if (current && GLOBAL_PAGE_STYLES.includes(current)) {
        return [{
            main: `/assets/css/globals/pages/${current}.css`,
            responsive: `/assets/css/responsive/globals/pages/${current}-responsive.css`,
        }];
    }

    // Fallback: I-load lahat ng CSS ng active portal
    return styles.map((name) => ({
        main: `/assets/css/portals/${folder}/${name}.css`,
        responsive: `/assets/css/responsive/portals/${folder}/${name}-responsive.css`,
    }));
});

// 4. DYNAMIC JS LOADER BASE SA PORTAL AT PAGE (Kapalit ng if-elseif <script> sa header.txt)
let activeScriptElement = null;

const loadPageScript = async () => {
    await nextTick(); // Hintaying ma-render ang HTML ng Vue page bago patakbuhin ang JS

    if (activeScriptElement) {
        activeScriptElement.remove();
        activeScriptElement = null;
    }

    const { folder, styles } = currentPortal.value;
    const current = activePageName.value;
    let scriptSrc = null;

    // Check kung Portal Page JS (hal. /assets/js/portals/client/dashboard.js)
    if (current && styles.includes(current)) {
        scriptSrc = `/assets/js/portals/${folder}/${current}.js`;
    }
    // Check kung Global Page JS (hal. /assets/js/globals/pages/my-profile.js)
    else if (current && GLOBAL_PAGE_STYLES.includes(current)) {
        scriptSrc = `/assets/js/globals/pages/${current}.js`;
    }

    if (scriptSrc) {
        const script = document.createElement('script');
        script.src = scriptSrc;
        script.async = true;
        script.dataset.portalScript = `${folder}-${current}`;
        document.body.appendChild(script);
        activeScriptElement = script;
    }
};

// Patakbuhin ang JS loader tuwing magpapalit ng portal o page
watch([activePortalKey, activePageName], () => {
    loadPageScript();
});

// 5. CONNECTION LOST OVERLAY LOGIC (mula sa header.txt)
const isOffline = ref(typeof navigator !== 'undefined' ? !navigator.onLine : false);
const connectionStatusText = ref('Waiting for connection...');

const updateOnlineStatus = () => {
    isOffline.value = !navigator.onLine;
    connectionStatusText.value = isOffline.value
        ? 'Waiting for connection...'
        : 'Connected!';
};

const retryConnection = () => {
    connectionStatusText.value = 'Checking connection...';
    setTimeout(updateOnlineStatus, 600);
};

onMounted(() => {
    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);
    loadPageScript();
});

onUnmounted(() => {
    window.removeEventListener('online', updateOnlineStatus);
    window.removeEventListener('offline', updateOnlineStatus);
    if (activeScriptElement) {
        activeScriptElement.remove();
        activeScriptElement = null;
    }
});
</script>

<template>
    <Head>
        <title>{{ currentPortal.title }}</title>
        <meta name="theme-color" content="#16A34A" />

        <!-- Dynamic Portal Theme CSS -->
        <link
            rel="stylesheet"
            :href="`/assets/css/themes/${currentPortal.themeFile}`"
            :key="`theme-${activePortalKey}`"
        />

        <!-- Dynamic Portal Page CSS -->
        <link
            v-for="css in activePortalStyles"
            :key="css.main"
            rel="stylesheet"
            :href="css.main"
        />

        <!-- Dynamic Portal Responsive CSS -->
        <link
            v-for="css in activePortalStyles"
            :key="css.responsive"
            rel="stylesheet"
            :href="css.responsive"
        />
    </Head>

    <!-- CONNECTION LOST OVERLAY -->
    <div
        v-if="isOffline"
        id="connectionLostPage"
        class="connection-lost-page"
        :aria-hidden="!isOffline"
    >
        <div class="connection-lost-card">
            <div class="connection-lost-icon">
                <i class="fa-solid fa-wifi" aria-hidden="true"></i>
                <span></span>
            </div>
            <div class="connection-lost-content">
                <div class="connection-lost-label">CONNECTION LOST</div>
                <h1>No Internet Connection</h1>
                <p>
                    Your internet connection appears to be unavailable.
                    Please check your network connection and try again.
                </p>
                <div class="connection-lost-status">
                    <span class="connection-status-dot"></span>
                    <span id="connectionLostStatus">{{ connectionStatusText }}</span>
                </div>
                <button
                    type="button"
                    id="connectionRetryBtn"
                    class="connection-retry-btn"
                    @click="retryConnection"
                >
                    <i class="fa-solid fa-rotate-right" aria-hidden="true"></i>
                    Retry Connection
                </button>
            </div>
        </div>
    </div>

    <!-- MAIN PORTAL WRAPPER -->
    <div class="portal-wrapper" :class="{ 'sidebar-collapsed': isSidebarCollapsed }">
        <!-- Dynamic Header para sa lahat ng 8 Portals -->
        <Header
            :portal-type="activePortalKey"
            @toggle-sidebar="isSidebarCollapsed = !isSidebarCollapsed"
        />

        <!-- Dito ilalagay ang <Sidebar /> kapag na-convert na natin -->
        <!-- <Sidebar :portal-type="activePortalKey" :active-page="activePageName" :collapsed="isSidebarCollapsed" /> -->

        <!-- Laman ng Page -->
        <main class="portal-main-content">
            <slot />
        </main>

        <!-- Dito ilalagay ang <Footer /> at <Modals /> kapag na-convert na natin -->
    </div>
</template>
