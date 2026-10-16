<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';

import '../../../css/auth/lock-screen.css';
import '../../../css/responsive/auth/lock-screen-responsive.css';

const props = defineProps({
    userName: {
        type: String,
        default: '',
    },
    userEmail: {
        type: String,
        default: '',
    },
    userImage: {
        type: String,
        default: '/assets/images/icons/default.png',
    },
});

const password = ref('');
const showPassword = ref(false);
const passwordError = ref('');
const isUnlocking = ref(false);

function togglePassword() {
    showPassword.value = !showPassword.value;
}

function unlockSession() {
    passwordError.value = '';

    if (!password.value.trim()) {
        passwordError.value = 'Please enter your password to unlock your session.';
        return;
    }

    /*
     * UI ONLY:
     * Connect this function to the Laravel session-unlock endpoint.
     * The password must be verified server-side before unlocking.
     */
    passwordError.value =
        'Session unlock is not connected yet. Your session remains locked.';
}

function signOut() {
    /*
     * UI ONLY:
     * Connect this action to your Laravel logout route.
     */
    passwordError.value =
        'Sign out is not connected yet.';
}
</script>

<template>
    <Head title="Session Locked" />

    <main class="lock-screen">
        <section
            class="lock-card"
            aria-labelledby="lockHeading"
        >
            <!-- HEADER -->
            <header class="lock-header">
                <img
                    :src="'/assets/images/lendell/LOSISv2.png'"
                    alt="LOSIS"
                    class="lock-logo"
                >

                <div class="lock-icon" aria-hidden="true">
                    <i class="fa-solid fa-lock"></i>
                </div>

                <h1 id="lockHeading">Session Locked</h1>

                <p>
                    Your LOSIS session has been temporarily locked.
                    Verify your identity to continue.
                </p>
            </header>

            <!-- USER INFORMATION -->
            <section
                class="lock-user"
                aria-label="Current user information"
            >
                <div class="lock-user-avatar">
                    <img
                        id="lockUserImage"
                        :src="userImage || '/assets/images/icons/default.png'"
                        alt="User profile"
                    >
                </div>

                <div class="lock-user-info">
                    <strong id="lockUserName">
                        {{ userName || 'User' }}
                    </strong>

                    <span id="lockUserEmail">
                        {{ userEmail || 'Registered account' }}
                    </span>
                </div>
            </section>

            <!-- SECURITY MESSAGE -->
            <div class="lock-info" role="note">
                <i
                    class="fa-solid fa-shield-halved"
                    aria-hidden="true"
                ></i>

                <span>
                    Your session is still active.
                    Enter your password to unlock your account
                    and resume your work.
                </span>
            </div>

            <!-- UNLOCK FORM -->
            <form
                id="lockForm"
                class="lock-form"
                autocomplete="on"
                novalidate
                @submit.prevent="unlockSession"
            >
                <div class="lock-form-group">
                    <label for="lockPassword">Password</label>

                    <div class="lock-password-wrapper">
                        <i
                            class="fa-solid fa-key"
                            aria-hidden="true"
                        ></i>

                        <input
                            v-model="password"
                            :type="showPassword ? 'text' : 'password'"
                            id="lockPassword"
                            name="password"
                            class="form-control"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            aria-describedby="lockPasswordError"
                            required
                            @input="passwordError = ''"
                        >

                        <!-- PASSWORD VISIBILITY -->
                        <button
                            type="button"
                            id="toggleLockPassword"
                            class="lock-password-toggle"
                            :title="showPassword ? 'Hide password' : 'Show password'"
                            :aria-label="showPassword ? 'Hide password' : 'Show password'"
                            aria-controls="lockPassword"
                            :aria-pressed="showPassword"
                            @click="togglePassword"
                        >
                            <i
                                :class="
                                    showPassword
                                        ? 'fa-solid fa-eye-slash'
                                        : 'fa-solid fa-eye'
                                "
                                aria-hidden="true"
                            ></i>
                        </button>
                    </div>

                    <!-- PASSWORD ERROR -->
                    <div
                        class="lock-error"
                        id="lockPasswordError"
                        role="alert"
                        aria-live="polite"
                    >
                        {{ passwordError }}
                    </div>
                </div>

                <!-- UNLOCK BUTTON -->
                <button
                    type="submit"
                    class="btn w-100"
                    id="unlockBtn"
                    :disabled="isUnlocking"
                >
                    <i
                        class="fa-solid fa-unlock"
                        aria-hidden="true"
                    ></i>

                    <span>
                        {{ isUnlocking ? 'Unlocking...' : 'Unlock Session' }}
                    </span>
                </button>
            </form>

            <!-- SIGN OUT -->
            <div class="lock-logout">
                <span>Not you?</span>

                <a
                    href="#"
                    id="logoutLockedSession"
                    @click.prevent="signOut"
                >
                    Sign out
                </a>
            </div>

            <!-- SECURITY FOOTER -->
            <footer class="lock-security">
                <i
                    class="fa-solid fa-shield-halved"
                    aria-hidden="true"
                ></i>

                <span>
                    Your account remains securely signed in.
                </span>
            </footer>
        </section>
    </main>
</template>
