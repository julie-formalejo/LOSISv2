
<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';

import '../../../css/auth/sign-in.css';
import '../../../css/responsive/auth/sign-in-responsive.css';

const email = ref('');
const password = ref('');
const rememberMe = ref(false);
const showPassword = ref(false);
const currentYear = new Date().getFullYear();
</script>

<template>
    <Head title="Sign In" />
    <main class="signin-page">
        <!-- LEFT PANEL: LOSIS BRANDING -->
        <aside
            class="signin-showcase"
            aria-label="LOSIS introduction"
        >
            <div
                class="showcase-decoration showcase-decoration-one"
                aria-hidden="true"
            ></div>

            <div
                class="showcase-decoration showcase-decoration-two"
                aria-hidden="true"
            ></div>

            <div class="showcase-content">
                <div class="showcase-brand">
                    <img
                        :src="'/assets/images/lendell/LOSISv2.png'"
                        alt="LOSIS - Lendell Online Standards Integrated System"
                    >
                </div>

                <div class="showcase-copy">
                    <span class="showcase-eyebrow">
                        <span class="status-dot" aria-hidden="true"></span>
                        LOSIS PORTAL
                    </span>

                    <h1>
                        Welcome to
                        <span>LOSIS.</span>
                    </h1>

                    <p>
                        Your centralized portal for accessing your
                        authorized LOSIS services and account.
                    </p>
                </div>

                <div class="showcase-feature">
                    <div class="feature-icon" aria-hidden="true">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>

                    <div class="showcase-feature-copy">
                        <h2>Eight portals. One LOSIS.</h2>
                        <p>
                            Sign in to access your assigned portal
                            and workspace.
                        </p>
                    </div>
                </div>
            </div>

            <footer class="showcase-footer">
                <span>LOSIS</span>
                <span
                    class="showcase-footer-divider"
                    aria-hidden="true"
                ></span>
                <span>Authorized access only</span>
            </footer>
        </aside>

        <!-- RIGHT PANEL: SIGN-IN FORM -->
        <section
            class="signin-main"
            aria-labelledby="signinHeading"
        >
            <div class="login-card" id="loginCard">
                <header class="login-header">
                <div class="login-mobile-logo">
                    <img
                        :src="'/assets/images/lendell/LOSISv2.png'"
                        alt="LOSIS Logo"
                    >
                </div>

                    <span class="login-eyebrow">ACCOUNT ACCESS</span>

                    <h2 id="signinHeading">Sign in to your account</h2>

                    <p class="login-subtitle">
                        Enter your credentials to continue.
                    </p>
                </header>

                <!-- Login alerts placeholder -->
                <div
                    class="login-alert-wrapper"
                    aria-live="polite"
                    aria-atomic="true"
                >
                    <div
                        id="loginAlertContainer"
                        class="login-alert-container"
                        role="status"
                    ></div>
                </div>

                <!-- Login functionality will be added later -->
                <form
                    id="loginForm"
                    autocomplete="on"
                    novalidate
                    @submit.prevent
                >
                    <!-- Email -->
                    <div class="field" id="emailField">
                        <label for="signin-email">
                            Email address
                        </label>

                        <div class="input-shell">
                            <i
                                class="fa-solid fa-envelope icon-left"
                                aria-hidden="true"
                            ></i>

                            <input
                                v-model="email"
                                type="email"
                                name="email"
                                id="signin-email"
                                placeholder=" "
                                autocomplete="username"
                                inputmode="email"
                                autocapitalize="none"
                                spellcheck="false"
                                aria-describedby="emailCapsWarning"
                                required
                            >
                        </div>

                        <div
                            class="caps-warning"
                            id="emailCapsWarning"
                            aria-live="polite"
                            hidden
                        >
                            <i
                                class="fa-solid fa-triangle-exclamation"
                                aria-hidden="true"
                            ></i>
                            Caps Lock is ON
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="field" id="passwordField">
                        <label for="signin-password">Password</label>

                        <div class="input-shell">
                            <i
                                class="fa-solid fa-lock icon-left"
                                aria-hidden="true"
                            ></i>

                            <input
                                v-model="password"
                                :type="showPassword ? 'text' : 'password'"
                                id="signin-password"
                                name="password"
                                placeholder=" "
                                autocomplete="current-password"
                                aria-describedby="passwordCapsWarning"
                                required
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                id="togglePasswordBtn"
                                :aria-label="showPassword ? 'Hide password' : 'Show password'"
                                aria-controls="signin-password"
                                :aria-pressed="showPassword"
                                @click="showPassword = !showPassword"
                            >
                                <i
                                    :class="showPassword
                                        ? 'fa-solid fa-eye-slash'
                                        : 'fa-solid fa-eye'"
                                    aria-hidden="true"
                                ></i>
                            </button>
                        </div>

                        <div
                            class="caps-warning"
                            id="passwordCapsWarning"
                            aria-live="polite"
                            hidden
                        >
                            <i
                                class="fa-solid fa-triangle-exclamation"
                                aria-hidden="true"
                            ></i>
                            Caps Lock is ON
                        </div>
                    </div>

                    <!-- Remember me and Forgot password -->
                    <div class="login-options">
                        <div class="form-check">
                            <input
                                v-model="rememberMe"
                                class="form-check-input"
                                type="checkbox"
                                id="signin-remember"
                                name="loginrem"
                            >

                            <label
                                class="form-check-label"
                                for="signin-remember"
                            >
                                Remember me
                            </label>
                        </div>

                        <a
                            class="forgot-link"
                            href="/forgot-password"
                        >
                            Forgot password?
                        </a>
                    </div>

                    <!-- Sign-in button -->
                    <button
                        type="submit"
                        class="btn btn-success"
                        name="signin"
                        id="loginBtn"
                    >
                        <span class="btn-text">Sign In</span>

                        <i
                            class="fa-solid fa-arrow-right"
                            aria-hidden="true"
                        ></i>
                    </button>

                    <!-- Account lock placeholder -->
                    <div
                        id="lockCountdown"
                        class="text-center mt-2 text-danger small d-none"
                        role="status"
                        aria-live="polite"
                    >
                        <i
                            class="fa-solid fa-clock"
                            aria-hidden="true"
                        ></i>

                        Account temporarily locked.
                        Try again in
                        <strong>
                            <span id="lockTimer">00:00</span>
                        </strong>
                    </div>

                    <!-- Support -->
                    <div class="login-support">
                        <span
                            class="support-icon"
                            aria-hidden="true"
                        >
                            <i class="fa-regular fa-circle-question"></i>
                        </span>

                        <div class="support-copy">
                            <span class="support-title">
                                Need assistance?
                            </span>

                            <a
                                href="#inquiryModal"
                                data-bs-toggle="modal"
                                data-bs-target="#inquiryModal"
                                aria-controls="inquiryModal"
                            >
                                Contact support
                                <i
                                    class="fa-solid fa-arrow-up-right-from-square"
                                    aria-hidden="true"
                                ></i>
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Security notice -->
                <div class="login-security-note">
                    <i
                        class="fa-solid fa-shield-halved"
                        aria-hidden="true"
                    ></i>

                    <p>
                        This portal is intended for authorized users.
                        Keep your sign-in credentials private.
                    </p>
                </div>
            </div>

            <!-- Page footer -->
            <footer class="signin-footer">
                <span>&copy; {{ currentYear }} LOSIS</span>

                <span
                    class="footer-separator"
                    aria-hidden="true"
                ></span>

                <span>All rights reserved.</span>
            </footer>
        </section>
    </main>
</template>
