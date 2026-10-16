
<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';

import '../../../css/auth/forgot-password.css';
import '../../../css/responsive/auth/forgot-password-responsive.css';

const email = ref('');
</script>

<template>
    <Head title="Forgot Password" />

    <main class="forgot-page">
        <section
            class="forgot-card"
            aria-labelledby="forgotHeading"
        >
            <!-- LOSIS Logo and Header -->
            <header class="forgot-header">
                <a
                    class="forgot-brand"
                    href="/"
                    aria-label="Return to LOSIS sign in"
                >
                    <img
                        :src="'/assets/images/lendell/LOSISv2.png'"
                        alt="LOSIS - Lendell Online Standards Integrated System"
                    >
                </a>

                <h1 id="forgotHeading">
                    Forgot Password
                </h1>

                <p>
                    Enter your email to receive a reset link
                </p>
            </header>

            <!-- Password Reset Form -->
            <form
                method="POST"
                id="forgotForm"
                autocomplete="on"
                novalidate
                @submit.prevent
            >
                <!-- Email Address -->
                <div class="field">
                    <label for="forgot-email">
                        Email address
                    </label>

                    <div class="forgot-input-shell">
                        <i
                            class="fa-solid fa-envelope icon-left"
                            aria-hidden="true"
                        ></i>

                        <input
                            v-model="email"
                            type="email"
                            name="email"
                            id="forgot-email"
                            placeholder="Email address"
                            autocomplete="email"
                            inputmode="email"
                            autocapitalize="none"
                            spellcheck="false"
                            maxlength="254"
                            required
                            aria-describedby="forgotEmailHelp"
                        >
                    </div>

                    <small
                        id="forgotEmailHelp"
                        class="forgot-field-help"
                    >
                        Use the email address registered to your account.
                    </small>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    id="forgotSubmitBtn"
                    class="btn btn-success forgot-submit-btn"
                >
                    <span class="forgot-submit-text">
                        Send Reset Link
                    </span>
                </button>

                <!-- Back to Login -->
                <div class="bottom-text">
                    <a href="/">
                        <i
                            class="fa-solid fa-arrow-left"
                            aria-hidden="true"
                        ></i>

                        <span>Back to Login</span>
                    </a>
                </div>
            </form>

            <!-- Security Reminder -->
            <div class="forgot-security-note">
                <i
                    class="fa-solid fa-shield-halved"
                    aria-hidden="true"
                ></i>

                <p>
                    Keep your password reset link private.
                </p>
            </div>
        </section>
    </main>
</template>
