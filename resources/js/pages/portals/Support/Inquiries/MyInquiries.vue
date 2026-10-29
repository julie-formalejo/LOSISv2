<script setup>
import { usePage } from '@inertiajs/vue3';

const page = usePage();
// Makukuha nito ang pangalan ng page mula sa URL o props
</script>

<template>
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h3 class="fw-bold text-success mb-2">
                    <i class="fa-solid fa-folder-open me-2"></i> {{ page.component }}
                </h3>
                <p class="text-muted">
                    This module is successfully migrated to LOSIS v2 Vue 3 / Inertia architecture.
                </p>
                <hr>
                <div class="alert alert-secondary mb-0">
                    <small>Module path: <code>resources/js/Pages/{{ page.component }}.vue</code></small>
                </div>
            </div>
        </div>
    </div>
</template><script setup>
import { usePage } from '@inertiajs/vue3';

const page = usePage();
// Makukuha nito ang pangalan ng page mula sa URL o props
</script>

<template>
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h3 class="fw-bold text-success mb-2">
                    <i class="fa-solid fa-folder-open me-2"></i> {{ page.component }}
                </h3>
                <p class="text-muted">
                    This module is successfully migrated to LOSIS v2 Vue 3 / Inertia architecture.
                </p>
                <hr>
                <div class="alert alert-secondary mb-0">
                    <small>Module path: <code>resources/js/Pages/{{ page.component }}.vue</code></small>
                </div>
            </div>
        </div>
    </div>
</template>
