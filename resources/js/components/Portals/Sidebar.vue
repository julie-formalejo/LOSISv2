<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    portalType: {
        type: String,
        default: 'client',
    },
    collapsed: {
        type: Boolean,
        default: false,
    },
});

const page = usePage();

// State para sa mga naka-open na dropdown/accordion menus
const openDropdowns = ref({});

const toggleDropdown = (label) => {
    openDropdowns.value[label] = !openDropdowns.value[label];
};

// Active page detection
const activePage = computed(() => {
    const urlParams = new URLSearchParams(page.url.split('?')[1] || '');
    if (urlParams.get('page')) return urlParams.get('page');

    const segments = page.url.split('?')[0].split('/').filter(Boolean);
    return segments[segments.length - 1] || 'dashboard';
});

const routePrefix = computed(() => `/${(props.portalType || 'client').toLowerCase()}`);

// Master Menu Registry na may sub-items (Dropdowns) batay sa listahan mo
const PORTAL_MENUS = {
    client: [
        { label: 'Dashboard', page: 'dashboard', icon: 'fa-solid fa-chart-line' },
        { label: 'New Endorsement', page: 'new-endorsement', icon: 'fa-solid fa-file-circle-plus' },
        { label: 'Manage Endorsements', page: 'manage-endorsements', icon: 'fa-solid fa-folder-open' },
        { label: 'Monitor Endorsements', page: 'monitor-endorsements', icon: 'fa-solid fa-chart-simple' },
        { label: 'Manage Applications', page: 'manage-applications', icon: 'fa-solid fa-file-lines' },
    ],
    supervisor: [
        { label: 'Dashboard', page: 'dashboard', icon: 'fa-solid fa-chart-line' },
        { label: 'New Endorsement', page: 'new-endorsement', icon: 'fa-solid fa-file-circle-plus' },
        {
            label: 'Endorsements',
            icon: 'fa-solid fa-folder-tree',
            children: [
                { label: 'Assign Endorsements', page: 'assign-endorsements' },
                { label: 'Report Review', page: 'report-review-final' },
                { label: 'Update Results', page: 'update-results' },
                { label: 'Document Review', page: 'document-review' },
            ]
        },
        { label: 'Monitor Endorsements', page: 'monitor-endorsements', icon: 'fa-solid fa-chart-simple' },
    ],
    specialist: [
        { label: 'Dashboard', page: 'dashboard', icon: 'fa-solid fa-chart-line' },
        {
            label: 'Endorsements',
            icon: 'fa-solid fa-folder-tree',
            children: [
                { label: 'Encode Reports', page: 'encode-reports' },
                { label: 'Update Results', page: 'update-checks' },
                { label: 'Document Review', page: 'document-review' },
            ]
        },
        { label: 'Monitor Endorsements', page: 'monitor-endorsements', icon: 'fa-solid fa-chart-simple' },
    ],
    support: [
        { label: 'Dashboard', page: 'dashboard', icon: 'fa-solid fa-chart-line' },
        {
            label: 'Verify Endorsements',
            icon: 'fa-solid fa-square-check',
            children: [
                { label: 'Assigned Endorsements', page: 'assign-endorsements' },
                { label: 'Cleared Endorsements', page: 'cleared-endorsements' },
                { label: 'With Findings', page: 'view-findings' },
            ]
        },
        { label: 'Monitor Endorsements', page: 'monitor-endorsements', icon: 'fa-solid fa-chart-simple' },
    ],
    admin: [
        { label: 'Dashboard', page: 'dashboard', icon: 'fa-solid fa-chart-line' },
        { label: 'Monitor Endorsements', page: 'monitor-endorsements', icon: 'fa-solid fa-chart-simple' },
        { label: 'Client Management', page: 'client-management', icon: 'fa-solid fa-building-user' },
        { label: 'User Management', page: 'user-management', icon: 'fa-solid fa-users-gear' },
        { label: 'Team Management', page: 'team-management', icon: 'fa-solid fa-people-group' },
        { label: 'Target Goal', page: 'target-goal', icon: 'fa-solid fa-bullseye' },
        { label: 'Monthly Performance', page: 'monthly-performance', icon: 'fa-solid fa-calendar-days' },
        { label: 'KPI Monitoring', page: 'kpi-monitoring', icon: 'fa-solid fa-gauge-high' },
    ],
    field: [
        { label: 'Dashboard', page: 'dashboard', icon: 'fa-solid fa-chart-line' },
    ],
    superadmin: [
        { label: 'Dashboard', page: 'dashboard', icon: 'fa-solid fa-chart-line' },
        {
            label: 'Operational Oversight',
            icon: 'fa-solid fa-binoculars',
            children: [
                { label: 'Admin Activity Summary', page: 'admin-activity-summary' },
                { label: 'Endorsement Oversight', page: 'endorsement-oversight' },
                { label: 'Record Audit Tracker', page: 'record-audit-tracker' },
                { label: 'Report Verification Log', page: 'report-verification-log' },
            ]
        },
        {
            label: 'Configuration & Control',
            icon: 'fa-solid fa-gears',
            children: [
                { label: 'System Settings', page: 'system-settings' },
                { label: 'Integration Settings', page: 'integration-settings' },
                { label: 'Access Control Panel', page: 'access-control-panel' },
                { label: 'Maintenance Tools', page: 'maintenance-tools' },
            ]
        },
        {
            label: 'Data Integrity & Audit',
            icon: 'fa-solid fa-shield-halved',
            children: [
                { label: 'Data Change Logs', page: 'data-change-logs' },
                { label: 'User Access Logs', page: 'user-access-logs' },
                { label: 'System Event History', page: 'system-event-history' },
                { label: 'Audit Export', page: 'audit-export' },
            ]
        },
        {
            label: 'Performance Analytics',
            icon: 'fa-solid fa-chart-pie',
            children: [
                { label: 'Operational Metrics', page: 'operational-metrics' },
                { label: 'Team Efficiency', page: 'team-efficiency' },
                { label: 'Goal vs Actual Analytics', page: 'goal-vs-actual-analytics' },
                { label: 'Performance Trends', page: 'performance-trends' },
            ]
        },
        {
            label: 'Reports & Data Management',
            icon: 'fa-solid fa-file-lines',
            children: [
                { label: 'Consolidated Reports', page: 'consolidated-reports' },
                { label: 'Custom Report Builder', page: 'custom-report-builder' },
                { label: 'Report Archive', page: 'report-archive' },
                { label: 'Export Center', page: 'export-center' },
            ]
        },
        {
            label: 'Account Management',
            icon: 'fa-solid fa-users',
            children: [
                { label: 'All Registered Users', page: 'all-registered-users' },
                { label: 'Role Management', page: 'role-management' },
                { label: 'Admin Accounts', page: 'admin-accounts' },
                { label: 'Access Activity Logs', page: 'access-activity-logs' },
            ]
        },
        {
            label: 'Compliance & Documentation',
            icon: 'fa-solid fa-book-bookmark',
            children: [
                { label: 'Audit Summary Reports', page: 'audit-summary-reports' },
                { label: 'Process Documentation', page: 'process-documentation' },
                { label: 'Data Retention Logs', page: 'data-retention-logs' },
                { label: 'System Compliance Tracker', page: 'system-compliance-tracker' },
            ]
        },
        {
            label: 'Communication & Announcements',
            icon: 'fa-solid fa-bullhorn',
            children: [
                { label: 'System Announcements', page: 'system-announcements' },
                { label: 'Admin Notices', page: 'admin-notices' },
                { label: 'Notification Settings', page: 'notification-settings' },
                { label: 'Inquiry Management', page: 'inquiry-management' },
            ]
        },
    ],
};

const menuItems = computed(() => {
    const key = (props.portalType || 'client').toLowerCase();
    return PORTAL_MENUS[key] || PORTAL_MENUS.client;
});
</script>

<template>
    <!-- =========================================================
       PORTAL SIDEBAR (Enhanced Executive Standard)
    ========================================================= -->
    <aside class="portal-sidebar" id="sidebar" aria-label="Main Navigation Sidebar">

        <!-- BRAND LOGO -->
        <div class="sidebar-logo">
            <Link :href="`${routePrefix}?page=dashboard`" class="sidebar-logo-link" aria-label="LOSIS v2 Dashboard">
                <img src="/assets/images/lendell/LOSISv2.png" alt="LOSIS v2 Logo">
            </Link>
        </div>

        <!-- SIDEBAR CATEGORY LABEL -->
        <div class="sidebar-nav-header">
            <span>Main Navigation</span>
        </div>

        <!-- NAVIGATION MENU -->
        <nav class="sidebar-menu">
            <template v-for="item in menuItems" :key="item.label">

                <!-- SINGLE MENU ITEM (Walang sub-items) -->
                <Link
                    v-if="!item.children"
                    :href="`${routePrefix}?page=${item.page}`"
                    class="menu-item"
                    :class="{ active: activePage === item.page }"
                >
                    <div class="menu-icon-wrapper">
                        <i :class="item.icon" aria-hidden="true"></i>
                    </div>
                    <span>{{ item.label }}</span>
                </Link>

                <!-- DROPDOWN / ACCORDION PARENT (May sub-items) -->
                <div v-else class="menu-dropdown-wrapper" :class="{ open: openDropdowns[item.label] }">
                    <a
                        href="#"
                        class="menu-item dropdown-toggle-btn"
                        @click.prevent="toggleDropdown(item.label)"
                    >
                        <div class="menu-icon-wrapper">
                            <i :class="item.icon" aria-hidden="true"></i>
                        </div>
                        <span class="menu-label">{{ item.label }}</span>
                        <i class="fa-solid fa-chevron-down dropdown-arrow" aria-hidden="true"></i>
                    </a>

                    <!-- SUB-ITEMS (Children) -->
                    <div class="submenu-container" v-show="openDropdowns[item.label]">
                        <Link
                            v-for="sub in item.children"
                            :key="sub.page"
                            :href="`${routePrefix}?page=${sub.page}`"
                            class="submenu-item"
                            :class="{ active: activePage === sub.page }"
                        >
                            <span>{{ sub.label }}</span>
                        </Link>
                    </div>
                </div>

            </template>
        </nav>

        <!-- SIDEBAR FOOTER CARD / INFO -->
        <div class="sidebar-footer-info">
            <div class="sidebar-support-card">
                <i class="fa-solid fa-headset" aria-hidden="true"></i>
                <div class="support-text">
                    <span>Need Assistance?</span>
                    <Link :href="`${routePrefix}?page=help`">Contact Support</Link>
                </div>
            </div>
        </div>

    </aside>
</template>
