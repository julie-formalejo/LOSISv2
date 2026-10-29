<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Sign In Page
Route::get('/', function () {
    return Inertia::render('Auth/SignIn');
})->name('login');

// Forgot Password Page
Route::get('/forgot-password', function () {
    return Inertia::render('Auth/ForgotPassword');
});

// Reset Password Page
Route::get('/reset-password', function () {
    return Inertia::render('Auth/ResetPassword');
});

// Two-Factor Authentication Page
Route::get('/2fa-verification', function () {
    return Inertia::render('Auth/TwoFactorVerification');
});

// Lock Screen Page
Route::get('/lock-screen', function () {
    return Inertia::render('Auth/LockScreen');
});

// Security Pin Page
Route::get('/security-pin', function () {
    return Inertia::render('Auth/SecurityPin');
});

// New Device Notification Page
Route::get('/new-device-notification', function () {
    return Inertia::render('Auth/NewDeviceNotification');
});

Route::prefix('portals')->group(function () {
    Route::get('/admin', fn () =>
        Inertia::render('portals/Admin/Index')
    );

    Route::get('/client', fn () =>
        Inertia::render('portals/Client/Index')
    );

    Route::get('/field', fn () =>
        Inertia::render('portals/Field/Index')
    );

    Route::get('/specialist', fn () =>
        Inertia::render('portals/Specialist/Index')
    );

    Route::get('/super-admin', fn () =>
        Inertia::render('portals/SuperAdmin/Index')
    );

    Route::get('/supervisor', fn () =>
        Inertia::render('portals/Supervisor/Index')
    );

    Route::get('/support', fn () =>
        Inertia::render('portals/Support/Index')
    );

    Route::get('/tele-supervisor', fn () =>
        Inertia::render('portals/TeleSupervisor/Index')
    );
});

Route::prefix('client')
    ->middleware(['auth', 'verified']) // I-uncomment o i-adjust depende sa middleware guards mo
    ->group(function () {

    // Helper function para sa pag-check ng welcome loader session
    $renderClientPage = function (string $componentName) {
        return Inertia::render("Client/{$componentName}", [
            'showWelcomeLoader' => session()->has('show_welcome_loader'),
        ]);
    };

    // ==========================================
    // 1. DASHBOARD
    // ==========================================
    Route::get('/dashboard', fn() => $renderClientPage('Dashboard'))->name('client.dashboard');

    // ==========================================
    // 2. ENDORSEMENTS
    // ==========================================
    Route::get('/new-endorsement', fn() => $renderClientPage('NewEndorsement'))->name('client.new-endorsement');
    Route::get('/manage-endorsements', fn() => $renderClientPage('ManageEndorsements'))->name('client.manage-endorsements');
    Route::get('/monitor-endorsements', fn() => $renderClientPage('MonitorEndorsements'))->name('client.monitor-endorsements');
    Route::get('/view-endorsement', fn() => $renderClientPage('ViewEndorsement'))->name('client.view-endorsement');

    // ==========================================
    // 3. APPLICATIONS
    // ==========================================
    Route::get('/manage-applications', fn() => $renderClientPage('ManageApplications'))->name('client.manage-applications');
    Route::get('/view-application', fn() => $renderClientPage('ViewApplication'))->name('client.view-application');
    Route::get('/approval-application', fn() => $renderClientPage('ApprovalApplication'))->name('client.approval-application');

    // ==========================================
    // 4. REPORTS
    // ==========================================
    Route::get('/view-report', fn() => $renderClientPage('ViewReport'))->name('client.view-report');
    Route::get('/view-report-history', fn() => $renderClientPage('ViewReportHistory'))->name('client.view-report-history');

    // ==========================================
    // 5. DOCUMENTS
    // ==========================================
    Route::get('/manage-documents', fn() => $renderClientPage('ManageDocuments'))->name('client.manage-documents');

    // ==========================================
    // 6. EXPORT MODULES
    // ==========================================
    Route::get('/export-csv', fn() => $renderClientPage('ExportCsv'))->name('client.export-csv');
    Route::get('/export-excel', fn() => $renderClientPage('ExportExcel'))->name('client.export-excel');
    Route::get('/export-pdf', fn() => $renderClientPage('ExportPdf'))->name('client.export-pdf');

    // ==========================================
    // 7. ACCOUNT MANAGEMENT
    // ==========================================
    Route::get('/my-profile', fn() => $renderClientPage('MyProfile'))->name('client.my-profile');
    Route::get('/edit-profile', fn() => $renderClientPage('EditProfile'))->name('client.edit-profile');
    Route::get('/my-settings', fn() => $renderClientPage('MySettings'))->name('client.my-settings');

    // ==========================================
    // 8. COMMUNICATION
    // ==========================================
    Route::get('/my-messaging', fn() => $renderClientPage('MyMessaging'))->name('client.my-messaging');
    Route::get('/my-inquiries', fn() => $renderClientPage('MyInquiries'))->name('client.my-inquiries');
    Route::get('/my-notifications', fn() => $renderClientPage('MyNotifications'))->name('client.my-notifications');

    // ==========================================
    // 9. INFORMATION & LEGAL
    // ==========================================
    Route::get('/privacy', fn() => $renderClientPage('Privacy'))->name('client.privacy');
    Route::get('/terms', fn() => $renderClientPage('Terms'))->name('client.terms');
    Route::get('/help', fn() => $renderClientPage('Help'))->name('client.help');

    // ==========================================
    // 10. SEARCH & SYSTEM FALLBACKS
    // ==========================================
    Route::get('/search-results', fn() => $renderClientPage('SearchResults'))->name('client.search-results');
    Route::get('/access-denied', fn() => $renderClientPage('AccessDenied'))->name('client.access-denied');
    Route::get('/under-maintenance', fn() => $renderClientPage('UnderMaintenance'))->name('client.under-maintenance');

    // PDF Generation Route
    Route::get('/application-form-pdf', fn() => $renderClientPage('ApplicationFormPdf'))->name('client.application-form-pdf');

    // 404 Page Not Found Fallback para sa Client Portal
    Route::fallback(fn() => Inertia::render('Client/PageNotFound')->toResponse(request())->setStatusCode(404));

});

Route::prefix('supervisor')
    ->middleware(['auth', 'verified']) // I-adjust depende sa middleware/guard mo
    ->group(function () {

    $renderSupervisorPage = function (string $componentName) {
        return Inertia::render("Supervisor/{$componentName}", [
            'showWelcomeLoader' => session()->has('show_welcome_loader'),
        ]);
    };

    // ==========================================
    // 1. DASHBOARD
    // ==========================================
    Route::get('/dashboard', fn() => $renderSupervisorPage('Dashboard'))->name('supervisor.dashboard');

    // ==========================================
    // 2. ENDORSEMENTS & OPERATIONS
    // ==========================================
    Route::get('/new-endorsement', fn() => $renderSupervisorPage('NewEndorsement'))->name('supervisor.new-endorsement');
    Route::get('/assign-endorsements', fn() => $renderSupervisorPage('AssignEndorsements'))->name('supervisor.assign-endorsements');
    Route::get('/monitor-endorsements', fn() => $renderSupervisorPage('MonitorEndorsements'))->name('supervisor.monitor-endorsements');
    Route::get('/view-endorsement', fn() => $renderSupervisorPage('ViewEndorsement'))->name('supervisor.view-endorsement');
    Route::get('/update-results', fn() => $renderSupervisorPage('UpdateResults'))->name('supervisor.update-results');

    // ==========================================
    // 3. REVIEWS & REPORTS
    // ==========================================
    Route::get('/report-review', fn() => $renderSupervisorPage('ReportReview'))->name('supervisor.report-review');
    Route::get('/review-report-initial', fn() => $renderSupervisorPage('ReviewReportInitial'))->name('supervisor.review-report-initial');
    Route::get('/review-report-partial', fn() => $renderSupervisorPage('ReviewReportPartial'))->name('supervisor.review-report-partial');
    Route::get('/review-report-final', fn() => $renderSupervisorPage('ReviewReportFinal'))->name('supervisor.review-report-final');
    Route::get('/edit-report', fn() => $renderSupervisorPage('EditReport'))->name('supervisor.edit-report');
    Route::get('/document-review', fn() => $renderSupervisorPage('DocumentReview'))->name('supervisor.document-review');
    Route::get('/view-report', fn() => $renderSupervisorPage('ViewReport'))->name('supervisor.view-report');
    Route::get('/view-report-history', fn() => $renderSupervisorPage('ViewReportHistory'))->name('supervisor.view-report-history');

    // ==========================================
    // 4. EXPORT MODULES
    // ==========================================
    Route::get('/export-csv', fn() => $renderSupervisorPage('ExportCsv'))->name('supervisor.export-csv');
    Route::get('/export-excel', fn() => $renderSupervisorPage('ExportExcel'))->name('supervisor.export-excel');
    Route::get('/export-pdf', fn() => $renderSupervisorPage('ExportPdf'))->name('supervisor.export-pdf');

    // ==========================================
    // 5. ACCOUNT & PROFILE
    // ==========================================
    Route::get('/my-profile', fn() => $renderSupervisorPage('MyProfile'))->name('supervisor.my-profile');
    Route::get('/edit-profile', fn() => $renderSupervisorPage('EditProfile'))->name('supervisor.edit-profile');
    Route::get('/my-settings', fn() => $renderSupervisorPage('MySettings'))->name('supervisor.my-settings');

    // ==========================================
    // 6. COMMUNICATION & NOTIFICATIONS
    // ==========================================
    Route::get('/my-messaging', fn() => $renderSupervisorPage('MyMessaging'))->name('supervisor.my-messaging');
    Route::get('/my-inquiries', fn() => $renderSupervisorPage('MyInquiries'))->name('supervisor.my-inquiries');
    Route::get('/my-notifications', fn() => $renderSupervisorPage('MyNotifications'))->name('supervisor.my-notifications');

    // ==========================================
    // 7. INFORMATION & LEGAL
    // ==========================================
    Route::get('/privacy', fn() => $renderSupervisorPage('Privacy'))->name('supervisor.privacy');
    Route::get('/terms', fn() => $renderSupervisorPage('Terms'))->name('supervisor.terms');
    Route::get('/help', fn() => $renderSupervisorPage('Help'))->name('supervisor.help');

    // ==========================================
    // 8. SEARCH & FALLBACKS
    // ==========================================
    Route::get('/search-results', fn() => $renderSupervisorPage('SearchResults'))->name('supervisor.search-results');

    // 404 Page Not Found Fallback para sa Supervisor Portal
    Route::fallback(fn() => Inertia::render('Supervisor/PageNotFound')->toResponse(request())->setStatusCode(404));

});

Route::prefix('specialist')
    ->middleware(['auth', 'verified']) // I-adjust depende sa middleware/guard mo
    ->group(function () {

    $renderSpecialistPage = function (string $componentName) {
        return Inertia::render("Specialist/{$componentName}", [
            'showWelcomeLoader' => session()->has('show_welcome_loader'),
        ]);
    };

    // ==========================================
    // 1. DASHBOARD
    // ==========================================
    Route::get('/dashboard', fn() => $renderSpecialistPage('Dashboard'))->name('specialist.dashboard');

    // ==========================================
    // 2. ENDORSEMENTS & ENCODING
    // ==========================================
    Route::get('/encode-reports', fn() => $renderSpecialistPage('EncodeReports'))->name('specialist.encode-reports');
    Route::get('/update-checks', fn() => $renderSpecialistPage('UpdateChecks'))->name('specialist.update-checks');
    Route::get('/monitor-endorsements', fn() => $renderSpecialistPage('MonitorEndorsements'))->name('specialist.monitor-endorsements');
    Route::get('/view-endorsement', fn() => $renderSpecialistPage('ViewEndorsement'))->name('specialist.view-endorsement');

    // ==========================================
    // 3. REPORTS & QA
    // ==========================================
    Route::get('/create-report', fn() => $renderSpecialistPage('CreateReport'))->name('specialist.create-report');
    Route::get('/edit-report', fn() => $renderSpecialistPage('EditReport'))->name('specialist.edit-report');
    Route::get('/reports-for-qa', fn() => $renderSpecialistPage('ReportsForQa'))->name('specialist.reports-for-qa');
    Route::get('/view-report', fn() => $renderSpecialistPage('ViewReport'))->name('specialist.view-report');
    Route::get('/view-report-history', fn() => $renderSpecialistPage('ViewReportHistory'))->name('specialist.view-report-history');

    // ==========================================
    // 4. ACCOUNT & PROFILE
    // ==========================================
    Route::get('/my-profile', fn() => $renderSpecialistPage('MyProfile'))->name('specialist.my-profile');
    Route::get('/edit-profile', fn() => $renderSpecialistPage('EditProfile'))->name('specialist.edit-profile');
    Route::get('/my-settings', fn() => $renderSpecialistPage('MySettings'))->name('specialist.my-settings');

    // ==========================================
    // 5. COMMUNICATION & NOTIFICATIONS
    // ==========================================
    Route::get('/my-messaging', fn() => $renderSpecialistPage('MyMessaging'))->name('specialist.my-messaging');
    Route::get('/my-inquiries', fn() => $renderSpecialistPage('MyInquiries'))->name('specialist.my-inquiries');
    Route::get('/my-notifications', fn() => $renderSpecialistPage('MyNotifications'))->name('specialist.my-notifications');

    // ==========================================
    // 6. INFORMATION & LEGAL
    // ==========================================
    Route::get('/privacy', fn() => $renderSpecialistPage('Privacy'))->name('specialist.privacy');
    Route::get('/terms', fn() => $renderSpecialistPage('Terms'))->name('specialist.terms');
    Route::get('/help', fn() => $renderSpecialistPage('Help'))->name('specialist.help');

    // ==========================================
    // 7. SEARCH & FALLBACKS
    // ==========================================
    Route::get('/search-results', fn() => $renderSpecialistPage('SearchResults'))->name('specialist.search-results');

    // 404 Page Not Found Fallback para sa Specialist Portal
    Route::fallback(fn() => Inertia::render('Specialist/PageNotFound')->toResponse(request())->setStatusCode(404));

});

Route::prefix('support')
    ->middleware(['auth', 'verified']) // I-adjust depende sa middleware/guard mo
    ->group(function () {

    $renderSupportPage = function (string $componentName) {
        return Inertia::render("Support/{$componentName}", [
            'showWelcomeLoader' => session()->has('show_welcome_loader'),
        ]);
    };

    // ==========================================
    // 1. DASHBOARD
    // ==========================================
    Route::get('/dashboard', fn() => $renderSupportPage('Dashboard'))->name('support.dashboard');

    // ==========================================
    // 2. VERIFICATION & ENDORSEMENTS
    // ==========================================
    Route::get('/endo-verification', fn() => $renderSupportPage('EndoVerification'))->name('support.endo-verification');
    Route::get('/view-verification', fn() => $renderSupportPage('ViewVerification'))->name('support.view-verification');
    Route::get('/assigned-endorsements', fn() => $renderSupportPage('AssignedEndorsements'))->name('support.assigned-endorsements');
    Route::get('/cleared-endorsements', fn() => $renderSupportPage('ClearedEndorsements'))->name('support.cleared-endorsements');
    Route::get('/with-findings', fn() => $renderSupportPage('WithFindings'))->name('support.with-findings');
    Route::get('/view-findings', fn() => $renderSupportPage('ViewFindings'))->name('support.view-findings');
    Route::get('/monitor-endorsements', fn() => $renderSupportPage('MonitorEndorsements'))->name('support.monitor-endorsements');
    Route::get('/view-endorsement', fn() => $renderSupportPage('ViewEndorsement'))->name('support.view-endorsement');
    Route::get('/view-endo-result', fn() => $renderSupportPage('ViewEndoResult'))->name('support.view-endo-result');

    // ==========================================
    // 3. REPORTS & HISTORY
    // ==========================================
    Route::get('/view-report-history', fn() => $renderSupportPage('ViewReportHistory'))->name('support.view-report-history');

    // ==========================================
    // 4. ACCOUNT & PROFILE
    // ==========================================
    Route::get('/my-profile', fn() => $renderSupportPage('MyProfile'))->name('support.my-profile');
    Route::get('/edit-profile', fn() => $renderSupportPage('EditProfile'))->name('support.edit-profile');
    Route::get('/my-settings', fn() => $renderSupportPage('MySettings'))->name('support.my-settings');

    // ==========================================
    // 5. COMMUNICATION & NOTIFICATIONS
    // ==========================================
    Route::get('/my-messaging', fn() => $renderSupportPage('MyMessaging'))->name('support.my-messaging');
    Route::get('/my-inquiries', fn() => $renderSupportPage('MyInquiries'))->name('support.my-inquiries');
    Route::get('/my-notifications', fn() => $renderSupportPage('MyNotifications'))->name('support.my-notifications');

    // ==========================================
    // 6. INFORMATION & LEGAL
    // ==========================================
    Route::get('/privacy', fn() => $renderSupportPage('Privacy'))->name('support.privacy');
    Route::get('/terms', fn() => $renderSupportPage('Terms'))->name('support.terms');
    Route::get('/help', fn() => $renderSupportPage('Help'))->name('support.help');

    // ==========================================
    // 7. SEARCH & FALLBACKS
    // ==========================================
    Route::get('/search-results', fn() => $renderSupportPage('SearchResults'))->name('support.search-results');

    // 404 Page Not Found Fallback para sa Support Portal
    Route::fallback(fn() => Inertia::render('Support/PageNotFound')->toResponse(request())->setStatusCode(404));

});

Route::prefix('admin')
    ->middleware(['auth', 'verified']) // I-adjust depende sa middleware/guard mo
    ->group(function () {

    $renderAdminPage = function (string $componentName) {
        return Inertia::render("Admin/{$componentName}", [
            'showWelcomeLoader' => session()->has('show_welcome_loader'),
        ]);
    };

    // ==========================================
    // 1. DASHBOARD & MONITORING
    // ==========================================
    Route::get('/dashboard', fn() => $renderAdminPage('Dashboard'))->name('admin.dashboard');
    Route::get('/monitor-endorsements', fn() => $renderAdminPage('MonitorEndorsements'))->name('admin.monitor-endorsements');
    Route::get('/view-endorsement', fn() => $renderAdminPage('ViewEndorsement'))->name('admin.view-endorsement');
    Route::get('/kpi-monitoring', fn() => $renderAdminPage('KpiMonitoring'))->name('admin.kpi-monitoring');
    Route::get('/monthly-performance', fn() => $renderAdminPage('MonthlyPerformance'))->name('admin.monthly-performance');
    Route::get('/target-goal', fn() => $renderAdminPage('TargetGoal'))->name('admin.target-goal');

    // ==========================================
    // 2. MANAGEMENT MODULES
    // ==========================================
    Route::get('/client-management', fn() => $renderAdminPage('ClientManagement'))->name('admin.client-management');
    Route::get('/user-management', fn() => $renderAdminPage('UserManagement'))->name('admin.user-management');
    Route::get('/team-management', fn() => $renderAdminPage('TeamManagement'))->name('admin.team-management');

    // ==========================================
    // 3. ACCOUNT & PROFILE
    // ==========================================
    Route::get('/my-profile', fn() => $renderAdminPage('MyProfile'))->name('admin.my-profile');
    Route::get('/edit-profile', fn() => $renderAdminPage('EditProfile'))->name('admin.edit-profile');
    Route::get('/my-settings', fn() => $renderAdminPage('MySettings'))->name('admin.my-settings');

    // ==========================================
    // 4. COMMUNICATION & NOTIFICATIONS
    // ==========================================
    Route::get('/my-messaging', fn() => $renderAdminPage('MyMessaging'))->name('admin.my-messaging');
    Route::get('/my-inquiries', fn() => $renderAdminPage('MyInquiries'))->name('admin.my-inquiries');
    Route::get('/my-notifications', fn() => $renderAdminPage('MyNotifications'))->name('admin.my-notifications');

    // ==========================================
    // 5. INFORMATION & LEGAL
    // ==========================================
    Route::get('/privacy', fn() => $renderAdminPage('Privacy'))->name('admin.privacy');
    Route::get('/terms', fn() => $renderAdminPage('Terms'))->name('admin.terms');
    Route::get('/help', fn() => $renderAdminPage('Help'))->name('admin.help');

    // ==========================================
    // 6. SEARCH & FALLBACKS
    // ==========================================
    Route::get('/search-results', fn() => $renderAdminPage('SearchResults'))->name('admin.search-results');

    // 404 Page Not Found Fallback para sa Admin Portal
    Route::fallback(fn() => Inertia::render('Admin/PageNotFound')->toResponse(request())->setStatusCode(404));

});

Route::prefix('tele-supervisor')
    ->middleware(['auth', 'verified']) // I-adjust depende sa middleware/guard mo
    ->group(function () {

    $renderTelesupervisorPage = function (string $componentName) {
        return Inertia::render("Telesupervisor/{$componentName}", [
            'showWelcomeLoader' => session()->has('show_welcome_loader'),
        ]);
    };

    // ==========================================
    // 1. DASHBOARD & ENDORSEMENTS
    // ==========================================
    Route::get('/dashboard', fn() => $renderTelesupervisorPage('Dashboard'))->name('telesupervisor.dashboard');
    Route::get('/assign-endorsements', fn() => $renderTelesupervisorPage('AssignEndorsements'))->name('telesupervisor.assign-endorsements');
    Route::get('/qa-review-endorsements', fn() => $renderTelesupervisorPage('QaReviewEndorsements'))->name('telesupervisor.qa-review-endorsements');
    Route::get('/monitor-endorsements', fn() => $renderTelesupervisorPage('MonitorEndorsements'))->name('telesupervisor.monitor-endorsements');
    Route::get('/view-endorsement', fn() => $renderTelesupervisorPage('ViewEndorsement'))->name('telesupervisor.view-endorsement');

    // ==========================================
    // 2. REPORTS & HISTORY
    // ==========================================
    Route::get('/review-report', fn() => $renderTelesupervisorPage('ReviewReport'))->name('telesupervisor.review-report');
    Route::get('/view-report-history', fn() => $renderTelesupervisorPage('ViewReportHistory'))->name('telesupervisor.view-report-history');

    // ==========================================
    // 3. ACCOUNT & PROFILE
    // ==========================================
    Route::get('/my-profile', fn() => $renderTelesupervisorPage('MyProfile'))->name('telesupervisor.my-profile');
    Route::get('/edit-profile', fn() => $renderTelesupervisorPage('EditProfile'))->name('telesupervisor.edit-profile');
    Route::get('/my-settings', fn() => $renderTelesupervisorPage('MySettings'))->name('telesupervisor.my-settings');

    // ==========================================
    // 4. COMMUNICATION & NOTIFICATIONS
    // ==========================================
    Route::get('/my-messaging', fn() => $renderTelesupervisorPage('MyMessaging'))->name('telesupervisor.my-messaging');
    Route::get('/my-inquiries', fn() => $renderTelesupervisorPage('MyInquiries'))->name('telesupervisor.my-inquiries');
    Route::get('/my-notifications', fn() => $renderTelesupervisorPage('MyNotifications'))->name('telesupervisor.my-notifications');

    // ==========================================
    // 5. INFORMATION & LEGAL
    // ==========================================
    Route::get('/privacy', fn() => $renderTelesupervisorPage('Privacy'))->name('telesupervisor.privacy');
    Route::get('/terms', fn() => $renderTelesupervisorPage('Terms'))->name('telesupervisor.terms');
    Route::get('/help', fn() => $renderTelesupervisorPage('Help'))->name('telesupervisor.help');

    // ==========================================
    // 6. SEARCH & FALLBACKS
    // ==========================================
    Route::get('/search-results', fn() => $renderTelesupervisorPage('SearchResults'))->name('telesupervisor.search-results');

    // 404 Page Not Found Fallback para sa Telesupervisor Portal
    Route::fallback(fn() => Inertia::render('Telesupervisor/PageNotFound')->toResponse(request())->setStatusCode(404));

});

Route::prefix('superadmin')
    ->middleware(['auth', 'verified']) // I-adjust depende sa middleware/guard mo
    ->group(function () {

    $renderSuperAdminPage = function (string $componentName) {
        return Inertia::render("SuperAdmin/{$componentName}", [
            'showWelcomeLoader' => session()->has('show_welcome_loader'),
        ]);
    };

    // ==========================================
    // 1. DASHBOARD & OVERSIGHT
    // ==========================================
    Route::get('/dashboard', fn() => $renderSuperAdminPage('Dashboard'))->name('superadmin.dashboard');
    Route::get('/admin-activity', fn() => $renderSuperAdminPage('AdminActivity'))->name('superadmin.admin-activity');
    Route::get('/endorsement-oversight', fn() => $renderSuperAdminPage('EndorsementOversight'))->name('superadmin.endorsement-oversight');
    Route::get('/audit-tracker', fn() => $renderSuperAdminPage('AuditTracker'))->name('superadmin.audit-tracker');
    Route::get('/verification-log', fn() => $renderSuperAdminPage('VerificationLog'))->name('superadmin.verification-log');

    // ==========================================
    // 2. CONFIGURATION & CONTROL
    // ==========================================
    Route::get('/system-settings', fn() => $renderSuperAdminPage('SystemSettings'))->name('superadmin.system-settings');
    Route::get('/integration-settings', fn() => $renderSuperAdminPage('IntegrationSettings'))->name('superadmin.integration-settings');
    Route::get('/access-control', fn() => $renderSuperAdminPage('AccessControl'))->name('superadmin.access-control');
    Route::get('/maintenance-tools', fn() => $renderSuperAdminPage('MaintenanceTools'))->name('superadmin.maintenance-tools');

    // ==========================================
    // 3. DATA INTEGRITY, LOGS & AUDIT
    // ==========================================
    Route::get('/data-logs', fn() => $renderSuperAdminPage('DataLogs'))->name('superadmin.data-logs');
    Route::get('/user-logs', fn() => $renderSuperAdminPage('UserLogs'))->name('superadmin.user-logs');
    Route::get('/system-history', fn() => $renderSuperAdminPage('SystemHistory'))->name('superadmin.system-history');
    Route::get('/audit-export', fn() => $renderSuperAdminPage('AuditExport'))->name('superadmin.audit-export');

    // ==========================================
    // 4. PERFORMANCE ANALYTICS
    // ==========================================
    Route::get('/metrics', fn() => $renderSuperAdminPage('Metrics'))->name('superadmin.metrics');
    Route::get('/efficiency', fn() => $renderSuperAdminPage('Efficiency'))->name('superadmin.efficiency');
    Route::get('/goal-analytics', fn() => $renderSuperAdminPage('GoalAnalytics'))->name('superadmin.goal-analytics');
    Route::get('/performance-trends', fn() => $renderSuperAdminPage('PerformanceTrends'))->name('superadmin.performance-trends');

    // ==========================================
    // 5. REPORTS & DATA MANAGEMENT
    // ==========================================
    Route::get('/consolidated-reports', fn() => $renderSuperAdminPage('ConsolidatedReports'))->name('superadmin.consolidated-reports');
    Route::get('/custom-report-builder', fn() => $renderSuperAdminPage('CustomReportBuilder'))->name('superadmin.custom-report-builder');
    Route::get('/report-archive', fn() => $renderSuperAdminPage('ReportArchive'))->name('superadmin.report-archive');
    Route::get('/export-center', fn() => $renderSuperAdminPage('ExportCenter'))->name('superadmin.export-center');

    // ==========================================
    // 6. ACCOUNT & USER MANAGEMENT
    // ==========================================
    Route::get('/users', fn() => $renderSuperAdminPage('Users'))->name('superadmin.users');
    Route::get('/roles', fn() => $renderSuperAdminPage('Roles'))->name('superadmin.roles');
    Route::get('/admins', fn() => $renderSuperAdminPage('Admins'))->name('superadmin.admins');
    Route::get('/activity-logs', fn() => $renderSuperAdminPage('ActivityLogs'))->name('superadmin.activity-logs');

    // ==========================================
    // 7. COMPLIANCE & DOCUMENTATION
    // ==========================================
    Route::get('/audit-summary', fn() => $renderSuperAdminPage('AuditSummary'))->name('superadmin.audit-summary');
    Route::get('/documentation', fn() => $renderSuperAdminPage('Documentation'))->name('superadmin.documentation');
    Route::get('/retention-logs', fn() => $renderSuperAdminPage('RetentionLogs'))->name('superadmin.retention-logs');
    Route::get('/compliance-tracker', fn() => $renderSuperAdminPage('ComplianceTracker'))->name('superadmin.compliance-tracker');

    // ==========================================
    // 8. COMMUNICATION & ANNOUNCEMENTS
    // ==========================================
    Route::get('/announcements', fn() => $renderSuperAdminPage('Announcements'))->name('superadmin.announcements');
    Route::get('/admin-notices', fn() => $renderSuperAdminPage('AdminNotices'))->name('superadmin.admin-notices');
    Route::get('/notification-settings', fn() => $renderSuperAdminPage('NotificationSettings'))->name('superadmin.notification-settings');
    Route::get('/inquiry-management', fn() => $renderSuperAdminPage('InquiryManagement'))->name('superadmin.inquiry-management');
    Route::get('/my-messaging', fn() => $renderSuperAdminPage('MyMessaging'))->name('superadmin.my-messaging');
    Route::get('/my-notifications', fn() => $renderSuperAdminPage('MyNotifications'))->name('superadmin.my-notifications');

    // ==========================================
    // 9. PROFILE & SYSTEM SETTINGS
    // ==========================================
    Route::get('/my-profile', fn() => $renderSuperAdminPage('MyProfile'))->name('superadmin.my-profile');
    Route::get('/edit-profile', fn() => $renderSuperAdminPage('EditProfile'))->name('superadmin.edit-profile');
    Route::get('/my-settings', fn() => $renderSuperAdminPage('MySettings'))->name('superadmin.my-settings');
    Route::get('/my-inquiries', fn() => $renderSuperAdminPage('MyInquiries'))->name('superadmin.my-inquiries');

    // ==========================================
    // 10. INFORMATION & FALLBACKS
    // ==========================================
    Route::get('/privacy', fn() => $renderSuperAdminPage('Privacy'))->name('superadmin.privacy');
    Route::get('/terms', fn() => $renderSuperAdminPage('Terms'))->name('superadmin.terms');
    Route::get('/help', fn() => $renderSuperAdminPage('Help'))->name('superadmin.help');
    Route::get('/search-results', fn() => $renderSuperAdminPage('SearchResults'))->name('superadmin.search-results');

    // 404 Page Not Found Fallback para sa SuperAdmin Portal
    Route::fallback(fn() => Inertia::render('SuperAdmin/PageNotFound')->toResponse(request())->setStatusCode(404));

});

Route::prefix('field')
    ->middleware(['auth', 'verified']) // I-adjust depende sa middleware/guard mo
    ->group(function () {

    $renderFieldPage = function (string $componentName) {
        return Inertia::render("Field/{$componentName}", [
            'showWelcomeLoader' => session()->has('show_welcome_loader'),
        ]);
    };

    // ==========================================
    // 1. DASHBOARD & FIELD OPERATIONS
    // ==========================================
    Route::get('/dashboard', fn() => $renderFieldPage('Dashboard'))->name('field.dashboard');

    // ==========================================
    // 2. EXPORT & SYSTEM FALLBACKS (Mula sa CSS list mo)
    // ==========================================
    Route::get('/export-csv', fn() => $renderFieldPage('ExportCsv'))->name('field.export-csv');
    Route::get('/export-excel', fn() => $renderFieldPage('ExportExcel'))->name('field.export-excel');
    Route::get('/export-pdf', fn() => $renderFieldPage('ExportPdf'))->name('field.export-pdf');

    // 404 Page Not Found Fallback para sa Field Portal
    Route::fallback(fn() => Inertia::render('Field/PageNotFound')->toResponse(request())->setStatusCode(404));

});
