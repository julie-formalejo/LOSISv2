<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    // Pwedeng ipasa mula sa PortalLayout: 'admin', 'client', 'field', 'specialist', 'superadmin', 'supervisor', 'support', 'telesupervisor'
    portalType: {
        type: String,
        default: null,
    },
});

const emit = defineEmits([
    'toggle-sidebar',
    'switch-account',
    'lock-account',
]);

const page = usePage();

// 1. Dynamic Config para sa lahat ng 8 Portals
const PORTAL_HEADERS = {
    admin: {
        headerTitle: 'LOSIS Admin',
        searchPlaceholder: 'Search users, teams, endorsements...',
        routePrefix: 'admin',
    },
    client: {
        headerTitle: 'LOSIS Client',
        searchPlaceholder: 'Search endorsements, applications...',
        routePrefix: 'client',
    },
    field: {
        headerTitle: 'LOSIS Field',
        searchPlaceholder: 'Search assigned field endorsements...',
        routePrefix: 'field',
    },
    specialist: {
        headerTitle: 'LOSIS Specialist',
        searchPlaceholder: 'Search reports, checks, endorsements...',
        routePrefix: 'specialist',
    },
    superadmin: {
        headerTitle: 'LOSIS SuperAdmin',
        searchPlaceholder: 'Search logs, audits, users, settings...',
        routePrefix: 'superadmin',
    },
    supervisor: {
        headerTitle: 'LOSIS Supervisor',
        searchPlaceholder: 'Search endorsements, reviews, reports...',
        routePrefix: 'supervisor',
    },
    support: {
        headerTitle: 'LOSIS Support',
        searchPlaceholder: 'Search verifications, findings, endorsements...',
        routePrefix: 'support',
    },
    telesupervisor: {
        headerTitle: 'LOSIS Telesupervisor',
        searchPlaceholder: 'Search QA endorsements, reports...',
        routePrefix: 'telesupervisor',
    },
};

// Kunin ang active portal (mula sa prop, session, o default sa 'client')
const activePortalKey = computed(() => {
    return (
        props.portalType ||
        page.props.auth?.user?.portal_type ||
        'client'
    ).toLowerCase();
});

const currentPortal = computed(() => {
    return PORTAL_HEADERS[activePortalKey.value] || PORTAL_HEADERS.client;
});

// 2. User Data (may fallback para sa Design Preview gaya ng nasa header.txt)
const user = computed(() => {
    const authUser = page.props.auth?.user;
    const rawUserId = authUser?.user_id || 'test-client-id';
    const cleanUserId = String(rawUserId).replace(/[^A-Za-z0-9\-_]/g, '');
    const userImage = authUser?.user_image || '';

    return {
        id: cleanUserId,
        fullName: authUser?.full_name || 'Client Preview User',
        avatarUrl: userImage
            ? `/files/profile-pictures/${cleanUserId}/${userImage}`
            : '/assets/images/icons/default.png',
        hasMultipleUsertypes: Boolean(authUser?.has_multiple_usertypes),
    };
});

// 3. Notifications Data (katumbas ng tbl_notification queries sa header.txt)
const unreadCount = computed(() => page.props.notifications?.unread_count ?? 0);
const notificationList = computed(() => page.props.notifications?.latest ?? []);

const formattedUnreadCount = computed(() => {
    return unreadCount.value > 9 ? '9+' : unreadCount.value;
});

// 4. Interactive States (kapalit ng header.js at search-results.js)
const isNotifOpen = ref(false);
const isUserMenuOpen = ref(false);
const searchQuery = ref('');
const searchResults = ref([]);
const isSearching = ref(false);

const notifDropdownRef = ref(null);
const userDropdownRef = ref(null);
const searchWrapperRef = ref(null);

// Global Search Handler
let searchTimeout = null;
const handleGlobalSearch = () => {
    clearTimeout(searchTimeout);

    if (!searchQuery.value.trim()) {
        searchResults.value = [];
        return;
    }

    searchTimeout = setTimeout(async () => {
        isSearching.value = true;
        try {
            const response = await axios.get(
                `/${currentPortal.value.routePrefix}/global-search`,
                { params: { q: searchQuery.value } }
            );
            searchResults.value = response.data?.results || [];
        } catch (error) {
            console.error('Global search error:', error);
            searchResults.value = [];
        } finally {
            isSearching.value = false;
        }
    }, 300);
};

// Toggle Dropdowns
const toggleNotifications = () => {
    isNotifOpen.value = !isNotifOpen.value;
    isUserMenuOpen.value = false;
};

const toggleUserMenu = () => {
    isUserMenuOpen.value = !isUserMenuOpen.value;
    isNotifOpen.value = false;
};

// Isara ang dropdown kapag nag-click sa labas
const handleClickOutside = (event) => {
    if (notifDropdownRef.value && !notifDropdownRef.value.contains(event.target)) {
        isNotifOpen.value = false;
    }
    if (userDropdownRef.value && !userDropdownRef.value.contains(event.target)) {
        isUserMenuOpen.value = false;
    }
    if (searchWrapperRef.value && !searchWrapperRef.value.contains(event.target)) {
        searchResults.value = [];
    }
};

// Logout Handler (kapalit ng global-logout.js)
const handleLogout = () => {
    router.post('/logout');
};

onMounted(() => document.addEventListener('click', handleClickOutside));
onUnmounted(() => document.removeEventListener('click', handleClickOutside));
</script>

<template>
    <!-- PORTAL HEADER -->
    <header class="portal-header">
        <!-- LEFT -->
        <div class="header-left">
            <button
                type="button"
                class="menu-toggle"
                id="menuToggle"
                aria-label="Toggle Sidebar Navigation"
                @click="emit('toggle-sidebar')"
            >
                <i class="fa-solid fa-bars" aria-hidden="true"></i>
            </button>
            <h5 class="header-title">{{ currentPortal.headerTitle }}</h5>
        </div>

        <!-- RIGHT -->
        <div class="header-right">
            <!-- GLOBAL SEARCH -->
            <div class="header-search-wrapper" ref="searchWrapperRef">
                <div class="header-search">
                    <input
                        type="text"
                        id="globalSearch"
                        v-model="searchQuery"
                        :placeholder="currentPortal.searchPlaceholder"
                        autocomplete="off"
                        @input="handleGlobalSearch"
                    >
                    <i class="fa-solid fa-search" aria-hidden="true"></i>
                </div>
                <div
                    v-show="searchQuery.trim().length > 0"
                    id="globalSearchResults"
                    class="global-search-results"
                >
                    <div class="search-header">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        <span>Search Results</span>
                    </div>
                    <div class="global-search-body">
                        <div v-if="isSearching" class="p-3 text-center text-muted">
                            Searching...
                        </div>
                        <template v-else-if="searchResults.length > 0">
                            <Link
                                v-for="item in searchResults"
                                :key="item.id"
                                :href="item.url"
                                class="search-result-item"
                            >
                                {{ item.title }}
                            </Link>
                        </template>
                        <div v-else class="p-3 text-center text-muted">
                            No results found.
                        </div>
                    </div>
                </div>
            </div>

            <!-- NOTIFICATIONS DROPDOWN -->
            <div class="header-dropdown user-profile-dropdown" ref="notifDropdownRef">
                <div
                    class="header-icon notification-trigger"
                    id="notifToggle"
                    role="button"
                    :aria-expanded="isNotifOpen"
                    aria-label="View Notifications"
                    @click.stop="toggleNotifications"
                >
                    <i class="fa-regular fa-bell" aria-hidden="true"></i>
                    <span v-if="unreadCount > 0" class="notif-counter">
                        {{ formattedUnreadCount }}
                    </span>
                </div>

                <div
                    class="dropdown-menu notification-dropdown"
                    :class="{ show: isNotifOpen }"
                    id="notificationDropdown"
                >
                    <div class="dropdown-header">
                        <div class="dropdown-header-left">
                            <h6>Notifications</h6>
                            <span>Latest updates and activities</span>
                        </div>
                        <div v-if="unreadCount > 0" class="dropdown-header-right">
                            <span class="dropdown-unread-pill">
                                {{ unreadCount }} New
                            </span>
                        </div>
                    </div>

                    <div class="dropdown-notification-list">
                        <template v-if="notificationList.length > 0">
                            <Link
                                v-for="notif in notificationList"
                                :key="notif.notif_id"
                                :href="notif.url || '#'"
                                class="dropdown-notification-item"
                                :class="{ unread: String(notif.status) === '0' }"
                            >
                                <div class="notif-item-content">
                                    <h6>{{ notif.subject }}</h6>
                                    <p>{{ notif.message }}</p>
                                </div>
                            </Link>
                        </template>
                        <div v-else class="dropdown-empty">
                            <div class="dropdown-empty-icon">
                                <i class="fa-regular fa-bell-slash" aria-hidden="true"></i>
                            </div>
                            <h6>No Notifications</h6>
                            <p>You're all caught up for now.</p>
                        </div>
                    </div>

                    <div class="dropdown-footer">
                        <Link :href="`/${currentPortal.routePrefix}/my-notifications`">
                            <span>View all notifications</span>
                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- USER PROFILE DROPDOWN -->
            <div class="header-dropdown user-profile-dropdown" ref="userDropdownRef">
                <div
                    class="user-trigger"
                    id="userTrigger"
                    role="button"
                    :aria-expanded="isUserMenuOpen"
                    @click.stop="toggleUserMenu"
                >
                    <img
                        :src="user.avatarUrl"
                        alt="User Profile Avatar"
                    >
                    <span class="user-name">
                        {{ user.fullName }}
                    </span>
                    <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                </div>

                <div
                    class="dropdown-menu dropdown-menu-left"
                    :class="{ show: isUserMenuOpen }"
                    id="userDropdownMenu"
                >
                    <Link
                        :href="`/${currentPortal.routePrefix}/my-profile`"
                        data-bs-toggle="tooltip"
                        title="View and update your profile information."
                    >
                        <i class="fa-solid fa-user" aria-hidden="true"></i>
                        <span>Profile</span>
                    </Link>
                    <Link
                        :href="`/${currentPortal.routePrefix}/my-settings`"
                        data-bs-toggle="tooltip"
                        title="Manage your account and system preferences."
                    >
                        <i class="fa-solid fa-gear" aria-hidden="true"></i>
                        <span>Settings</span>
                    </Link>
                    <Link
                        :href="`/${currentPortal.routePrefix}/my-inquiries`"
                        data-bs-toggle="tooltip"
                        title="View and manage your inquiries and requests."
                    >
                        <i class="fa-solid fa-headset" aria-hidden="true"></i>
                        <span>Inquiries</span>
                    </Link>
                    <Link
                        :href="`/${currentPortal.routePrefix}/my-messaging`"
                        data-bs-toggle="tooltip"
                        title="Access your messages and conversations."
                    >
                        <i class="fa-solid fa-message" aria-hidden="true"></i>
                        <span>Messaging</span>
                    </Link>

                    <div class="dropdown-divider"></div>

                    <a
                        v-if="user.hasMultipleUsertypes"
                        href="#"
                        id="btnSwitchAccount"
                        data-bs-toggle="tooltip"
                        title="Switch between your available account types."
                        @click.prevent="emit('switch-account')"
                    >
                        <i class="fa-solid fa-repeat" aria-hidden="true"></i>
                        <span>Switch Account</span>
                    </a>
                    <a
                        href="#"
                        id="btnLockAccount"
                        data-bs-toggle="tooltip"
                        title="Temporarily lock your session without signing out."
                        @click.prevent="emit('lock-account')"
                    >
                        <i class="fa-solid fa-lock" aria-hidden="true"></i>
                        <span>Lock Account</span>
                    </a>
                    <a
                        href="#"
                        id="btnLogout"
                        class="text-danger"
                        data-bs-toggle="tooltip"
                        title="Sign out and end your current session."
                        @click.prevent="handleLogout"
                    >
                        <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </header>
</template>
