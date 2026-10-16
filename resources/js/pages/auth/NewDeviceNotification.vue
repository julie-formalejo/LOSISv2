<script setup>
import { Head } from '@inertiajs/vue3';

import '../../../css/auth/new-device-notification.css';
import '../../../css/responsive/auth/new-device-notification-responsive.css';

const currentYear = new Date().getFullYear();
</script>

<template>
    <Head title="Device Security" />

    <main class="device-security-page">

        <!-- LOSIS BRANDING -->
        <aside
            class="security-showcase"
            aria-label="LOSIS account security"
        >
            <div class="security-showcase-content">

                <div class="security-showcase-brand">
                    <img
                        :src="'/assets/images/lendell/LOSISv2.png'"
                        alt="LOSIS — Lendell Online Standards Integrated System"
                        width="150"
                    >
                </div>

                <div class="security-showcase-copy">
                    <span class="security-showcase-eyebrow">
                        <span
                            class="security-status-dot"
                            aria-hidden="true"
                        ></span>
                        ACCOUNT SECURITY
                    </span>

                    <h1>
                        Protect your <span>LOSIS account.</span>
                    </h1>

                    <p>
                        Review unfamiliar sign-ins and help keep
                        your account secure.
                    </p>
                </div>

            </div>

            <footer class="security-showcase-footer">
                <span>LOSIS</span>

                <span
                    class="security-footer-divider"
                    aria-hidden="true"
                ></span>

                <span>Authorized access only</span>
            </footer>
        </aside>

        <!-- DEVICE SECURITY CONTENT -->
        <section
            class="device-security-main"
            aria-labelledby="deviceSecurityHeading"
        >
            <div class="device-security-card">

                <!-- PAGE HEADER -->
                <header class="device-security-header">
                    <span class="security-eyebrow">
                        SECURITY CENTER
                    </span>

                    <h2 id="deviceSecurityHeading">
                        New Device Notifications
                    </h2>

                    <p class="security-subtitle">
                        Review unfamiliar sign-ins and learn how
                        LOSIS protects your account.
                    </p>
                </header>

                <!-- NOTIFICATION INFORMATION -->
                <section
                    class="security-notice"
                    aria-labelledby="securityNoticeHeading"
                >
                    <div
                        class="security-notice-icon"
                        aria-hidden="true"
                    >
                        <i class="fa-solid fa-bell"></i>
                    </div>

                    <div class="security-notice-content">
                        <h3 id="securityNoticeHeading">
                            Stay alert to new sign-ins
                        </h3>

                        <p>
                            LOSIS can notify your registered email
                            when a successful sign-in is detected
                            from a newly recognized browser or device.
                        </p>
                    </div>
                </section>

                <!-- RECENT DEVICE ACTIVITY -->
                <section
                    class="device-activity-section"
                    aria-labelledby="deviceActivityHeading"
                >
                    <div class="device-section-header">
                        <div>
                            <span class="device-section-eyebrow">
                                ACCOUNT MONITORING
                            </span>

                            <h3 id="deviceActivityHeading">
                                Recent Device Activity
                            </h3>

                            <p>
                                Review the devices and browsers
                                associated with your account.
                            </p>
                        </div>

                        <div
                            class="device-section-icon"
                            aria-hidden="true"
                        >
                            <i class="fa-solid fa-laptop"></i>
                        </div>
                    </div>

                    <!-- EMPTY STATE -->
                    <div
                        class="device-activity-list"
                        id="deviceActivityList"
                        aria-live="polite"
                        aria-atomic="false"
                    >
                        <div class="device-empty-state">
                            <div
                                class="device-empty-icon"
                                aria-hidden="true"
                            >
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>

                            <h4>
                                Device activity will appear here
                            </h4>

                            <p>
                                Recent sign-in activity will appear
                                once this page is connected to the
                                LOSIS security logging system.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- SECURITY WARNING -->
                <section
                    class="security-action-section"
                    aria-labelledby="securityActionHeading"
                >
                    <div
                        class="security-action-icon"
                        aria-hidden="true"
                    >
                        <i class="fa-solid fa-user-shield"></i>
                    </div>

                    <div class="security-action-content">
                        <h3 id="securityActionHeading">
                            Don't recognize a sign-in?
                        </h3>

                        <p>
                            Change your password and contact your
                            LOSIS administrator immediately if you
                            suspect unauthorized access.
                        </p>
                    </div>
                </section>

                <!-- SECURITY GUIDELINES -->
                <section
                    class="security-guidelines"
                    aria-labelledby="securityGuidelinesHeading"
                >
                    <h3 id="securityGuidelinesHeading">
                        Keep your account secure
                    </h3>

                    <ul>
                        <li>
                            <i
                                class="fa-solid fa-circle-check"
                                aria-hidden="true"
                            ></i>

                            <span>
                                Never share your password or
                                authentication code.
                            </span>
                        </li>

                        <li>
                            <i
                                class="fa-solid fa-circle-check"
                                aria-hidden="true"
                            ></i>

                            <span>
                                Sign out when using a shared or
                                public computer.
                            </span>
                        </li>

                        <li>
                            <i
                                class="fa-solid fa-circle-check"
                                aria-hidden="true"
                            ></i>

                            <span>
                                Contact your administrator if you
                                suspect unauthorized access.
                            </span>
                        </li>
                    </ul>
                </section>

                <!-- SECURITY NOTE -->
                <div class="device-security-note">
                    <i
                        class="fa-solid fa-lock"
                        aria-hidden="true"
                    ></i>

                    <p>
                        Account security information is restricted
                        to authorized users.
                    </p>
                </div>

            </div>

            <!-- FOOTER -->
            <footer class="device-security-footer">
                <span>&copy; {{ currentYear }} LOSIS</span>

                <span
                    class="security-footer-separator"
                    aria-hidden="true"
                ></span>

                <span>All rights reserved.</span>
            </footer>

        </section>
    </main>
</template>
