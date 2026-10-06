<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AdminMemberController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminLandingPageController;
use App\Http\Controllers\AdminStaffController;
use App\Http\Controllers\AdminAssignmentController;
use App\Http\Controllers\StaffAssignmentController;
use App\Http\Controllers\StaffAttendanceController;
use App\Http\Controllers\AdminAttendanceController;
use App\Http\Controllers\AdminSalesController;
use App\Services\WhatsappService;

// Route::get('/cek-koneksi', function() {
//     $token = 'e9kduQCaJ8iUZqtvzDm3'; // Token Anda

//     $curl = curl_init();
//     curl_setopt_array($curl, array(
//         CURLOPT_URL => 'https://api.fonnte.com/device',
//         CURLOPT_RETURNTRANSFER => true,
//         CURLOPT_CUSTOMREQUEST => 'POST', // Fonnte minta POST
//         CURLOPT_HTTPHEADER => array(
//             "Authorization: $token"
//         ),
//         CURLOPT_SSL_VERIFYPEER => false, // Penting jika di localhost
//     ));

//     $response = curl_exec($curl);
//     curl_close($curl);

//     return response($response)->header('Content-Type', 'application/json');
// });

Route::post('/resend-otp', [MemberController::class, 'resendOtp'])->name('member.resend.otp');

Route::get('/', function () {
    $headerLogo = \App\Models\LandingPageSetting::getValue('header', 'logo', 'images/logo4.webp');
    $mainLogo = \App\Models\LandingPageSetting::getValue('main', 'logo', 'images/logo1.webp');
    $mainSubtitle = \App\Models\LandingPageSetting::getValue('main', 'subtitle', 'Your Destination for Authentic Guitars');

    // Get slider images
    $sliderImagesJson = \App\Models\LandingPageSetting::getValue('main', 'slider_images', null);
    $sliderImages = $sliderImagesJson ? json_decode($sliderImagesJson, true) : [
        asset('images/slider1.webp'),
        asset('images/slider2.webp'),
        asset('images/slider3.webp'),
        asset('images/slider4.webp'),
        asset('images/slider5.webp'),
    ];

    // Get about section data
    $aboutLogo = \App\Models\LandingPageSetting::getValue('about', 'logo', 'images/logo-about.png');
    $aboutDescription = \App\Models\LandingPageSetting::getValue('about', 'description', 'Musicmen Store is a destination for guitar and bass enthusiasts who prioritize quality, authenticity, and a premium shopping experience. Based in Yogyakarta, we focus on original instrument selections—from premium second-hand units, rare items, to curated product lines chosen with high standards.

Every instrument at Musicmen goes through a meticulous selection process, detailed inspection, and professional setup to ensure that every guitar and bass not only looks perfect but is also ready to play with its best performance.

More than just a transaction place, Musicmen Store is a space that brings trust and comfort. We are committed to building long-term relationships with players, collectors, and the music community through friendly, transparent, and consistent service.

With the philosophy that every instrument has its own character and story, we help musicians find the right musical instrument—one that aligns with their playing style, needs, and musical journey.');

    // Get brands section data
    $brandsTitle = \App\Models\LandingPageSetting::getValue('brands', 'title', 'GUITAR BRANDS');
    $brandsSubtitle = \App\Models\LandingPageSetting::getValue('brands', 'subtitle', 'Trusted Guitar Brands We Offer');
    $brandsLogo = \App\Models\LandingPageSetting::getValue('brands', 'logo', null);
    $guitarBrands = \App\Models\Brand::getGuitarBrands();
    $accessoriesBrands = \App\Models\Brand::getAccessoriesBrands();
    $accTitle = \App\Models\LandingPageSetting::getValue('accessories', 'title', 'ACCESSORIES BRANDS');
    $accSubtitle = \App\Models\LandingPageSetting::getValue('accessories', 'subtitle', 'Trusted Accessories Brands');
    $accImage = \App\Models\LandingPageSetting::getValue('accessories', 'image', 'images/default-acc.jpg');
    $accessories = \App\Models\LandingPageProduct::where('category', 'accessories')
        ->orderBy('order')
        ->get();
    $galleries = \App\Models\Gallery::orderBy('order')->get();
    $title = \App\Models\LandingPageSetting::getValue('service', 'title', 'OUR SERVICES');
    $subtitle = \App\Models\LandingPageSetting::getValue('service', 'subtitle', 'Complete Services for Your Music Needs');
    $services = \App\Models\Service::orderBy('order')->get();
    $contactTitle = \App\Models\LandingPageSetting::getValue('contact', 'title', 'CONTACTS');
    $addressText = \App\Models\LandingPageSetting::getValue('contact', 'address_text', 'Jl. Wates No.148 Km. 3,5 No, Onggobayan, Ngestiharjo, Kec. Kasihan, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55184');
    $phoneText = \App\Models\LandingPageSetting::getValue('contact', 'phone_text', '08816707166');
    $socialLink = \App\Models\LandingPageSetting::getValue('contact', 'social_link', '#');
    $mapsLink = \App\Models\LandingPageSetting::getValue('contact', 'maps_link', '');
    $contactLogoPath = \App\Models\LandingPageSetting::getValue('contact', 'logo', 'images/logo3.png');

    // TAMBAHKAN INI: Ambil path icon hasil upload
    $addressIconPath = \App\Models\LandingPageSetting::getValue('contact', 'address_icon', null);
    $phoneIconPath = \App\Models\LandingPageSetting::getValue('contact', 'phone_icon', null);
    $socialIconPath = \App\Models\LandingPageSetting::getValue('contact', 'social_icon', null);
    $acc_title = \App\Models\LandingPageSetting::getValue('brands', 'acc_title', 'ACCESSORIES BRANDS');
    $acc_subtitle = \App\Models\LandingPageSetting::getValue('brands', 'acc_subtitle', 'Trusted Accessories Brands We Offer');

    // Get products from database (exclude accessories)
    try {
        // TAMBAHKAN filter != 'merchandise' dan != 'accessories'
        $products = \App\Models\LandingPageProduct::whereNotIn('category', ['accessories', 'merchandise']) //
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();
    } catch (\Exception $e) {
        $products = collect([]);
    }

    // Get products title
    $productsTitle = \App\Models\LandingPageSetting::getValue('products', 'title', 'PRODUCTS');

    return view('welcome', compact('headerLogo', 'mainLogo', 'mainSubtitle', 'sliderImages', 'aboutLogo', 'aboutDescription', 'brandsTitle', 'brandsSubtitle', 'brandsLogo', 'guitarBrands', 'accessoriesBrands', 'accTitle', 'accSubtitle', 'accImage', 'accessories', 'galleries', 'title', 'subtitle', 'services', 'contactTitle', 'addressText', 'phoneText', 'socialIconPath', 'socialLink', 'mapsLink', 'contactLogoPath', 'addressIconPath', 'phoneIconPath', 'products', 'productsTitle', 'acc_title', 'acc_subtitle'));
});

Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.detail');

// Category Routes
Route::get('/category/{category}', [ProductController::class, 'category'])->name('category.show');

// Accessories and Merchandise Routes
Route::get('/accessories', [ProductController::class, 'accessories'])->name('accessories');
Route::get('/merchandise', [ProductController::class, 'merchandise'])->name('merchandise');

// Used Gear Route
Route::get('/used-gear', [ProductController::class, 'usedGear'])->name('used-gear');

// Search Route
Route::get('/search', [ProductController::class, 'search'])->name('search');

// Admin Auth Routes

Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/verify-otp', [MemberController::class, 'showOtpForm'])->name('member.otp.view');
Route::post('/verify-otp', [MemberController::class, 'verifyOtp'])->name('member.verify.otp');

// Admin Dashboard (Protected)
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard');

    // Get Location (for setting office location)
    Route::get('/admin/get-location', function () {
        return view('admin.get-location');
    })->name('admin.get-location');

    // Office Settings
    Route::post('/admin/office/update-location', [App\Http\Controllers\AdminOfficeSettingController::class, 'updateLocation'])->name('admin.office.updateLocation');
    Route::get('/admin/office/get-location', [App\Http\Controllers\AdminOfficeSettingController::class, 'getLocation'])->name('admin.office.getLocation');

    // Admin Members Management
    Route::get('/admin/members', [AdminMemberController::class, 'index'])->name('admin.members.index');
    Route::get('/admin/members/export', [AdminMemberController::class, 'export'])->name('admin.members.export');
    Route::get('/admin/members/create', [AdminMemberController::class, 'create'])->name('admin.members.create');
    Route::post('/admin/members', [AdminMemberController::class, 'store'])->name('admin.members.store');
    Route::get('/admin/members/{id}/edit', [AdminMemberController::class, 'edit'])->name('admin.members.edit');
    Route::put('/admin/members/{id}', [AdminMemberController::class, 'update'])->name('admin.members.update');
    Route::delete('/admin/members/{id}', [AdminMemberController::class, 'destroy'])->name('admin.members.destroy');
    Route::post('/admin/members/{id}/visit', [AdminMemberController::class, 'recordVisit'])->name('admin.members.visit');
    Route::get('/admin/members/search/{memberId}', [AdminMemberController::class, 'getByMemberId'])->name('admin.members.search');
    Route::get('/admin/members/new', [MemberController::class, 'getNewMembers'])->name('admin.members.new');
    Route::post('/admin/members/{id}/send-wa', [AdminMemberController::class, 'sendWelcomeWa'])->name('admin.members.send_wa');

    // Admin Rewards Management
    Route::get('/admin/rewards', [App\Http\Controllers\AdminRewardController::class, 'index'])->name('admin.rewards.index');
    Route::get('/admin/rewards/create', [App\Http\Controllers\AdminRewardController::class, 'create'])->name('admin.rewards.create');
    Route::post('/admin/rewards', [App\Http\Controllers\AdminRewardController::class, 'store'])->name('admin.rewards.store');
    Route::get('/admin/rewards/{id}/edit', [App\Http\Controllers\AdminRewardController::class, 'edit'])->name('admin.rewards.edit');
    Route::put('/admin/rewards/{id}', [App\Http\Controllers\AdminRewardController::class, 'update'])->name('admin.rewards.update');
    Route::delete('/admin/rewards/{id}', [App\Http\Controllers\AdminRewardController::class, 'destroy'])->name('admin.rewards.destroy');
    Route::post('/admin/rewards/claims/{id}/fulfill', [App\Http\Controllers\RewardClaimController::class, 'fulfill'])->name('admin.rewards.claims.fulfill');

    Route::get('/admin/service-harian/export', [App\Http\Controllers\AdminServiceHarianController::class, 'export'])
        ->name('admin.service-harian.export');

    Route::get('/admin/service-harian/api/new', [App\Http\Controllers\AdminServiceHarianController::class, 'getNewServices'])
        ->name('admin.service-harian.api.new');

    Route::post('/admin/service-harian/{id}/update-fee', [App\Http\Controllers\AdminServiceHarianController::class, 'updateFee'])
        ->name('admin.service-harian.update-fee');

    Route::post('/admin/service-harian/get-whatsapp-message', [App\Http\Controllers\AdminServiceHarianController::class, 'getWhatsAppMessage'])
        ->name('admin.service-harian.get-whatsapp-message');

    // Admin Service Harian
    // --- Tambahkan ini di web.php (Bagian Admin) ---
    Route::prefix('admin/service-harian')->name('admin.service-harian.')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminServiceHarianController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\AdminServiceHarianController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\AdminServiceHarianController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [App\Http\Controllers\AdminServiceHarianController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\AdminServiceHarianController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\AdminServiceHarianController::class, 'destroy'])->name('destroy');
    });

    Route::post('/staff/update-my-profile', [App\Http\Controllers\AuthController::class, 'updateMyProfile'])->name('staff.update-my-profile');
    // --- Bagian Rute STAFF ---
    // --- Bagian Rute STAFF ---
    Route::prefix('staff/service-harian')->name('staff.service-harian.')->group(function () {
        Route::get('/input', [App\Http\Controllers\StaffServiceHarianController::class, 'inputIndex'])->name('input-index');
        Route::get('/kelola', [App\Http\Controllers\StaffServiceHarianController::class, 'kelolaIndex'])->name('kelola-index');
        Route::get('/create', [App\Http\Controllers\StaffServiceHarianController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\StaffServiceHarianController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [App\Http\Controllers\StaffServiceHarianController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\StaffServiceHarianController::class, 'update'])->name('update');

        // Route untuk WhatsApp Otomatis
        Route::post('/get-whatsapp-message', [App\Http\Controllers\StaffServiceHarianController::class, 'getWhatsAppMessage'])->name('get-whatsapp-message');

        // PERBAIKAN DI SINI:
        // Cukup gunakan '/send-manual-wa' karena sudah ada prefix 'staff/service-harian'
        Route::post('/send-manual-wa', [App\Http\Controllers\StaffServiceHarianController::class, 'sendManualWa'])->name('send-manual-wa');

        // Alias agar jika ada script lama yang memanggil 'index' tetap jalan
        Route::get('/', [App\Http\Controllers\StaffServiceHarianController::class, 'inputIndex'])->name('index');
    });

    Route::prefix('staff/inventory')->name('staff.inventory.')->group(function () {
        Route::get('/', [App\Http\Controllers\StaffProductController::class, 'inventoryIndex'])->name('index');
        Route::patch('/{id}/checkup', [App\Http\Controllers\StaffProductController::class, 'checkup'])->name('checkup'); // ← PATCH + method baru
    });

    Route::prefix('admin/sop')->name('admin.sop.')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminSopController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\AdminSopController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\AdminSopController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\AdminSopController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\AdminSopController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\AdminSopController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\AdminSopController::class, 'destroy'])->name('destroy');
    });

    // Staff SOP Routes (View Only)
    Route::prefix('staff/sop')->name('staff.sop.')->group(function () {
        Route::get('/', [App\Http\Controllers\StaffSopController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\StaffSopController::class, 'show'])->name('show');
    });


    // Staff Sales Instruments
    Route::get('/staff/sales', [App\Http\Controllers\StaffSalesController::class, 'index'])->name('staff.sales.index');
    Route::get('/staff/sales/create', [App\Http\Controllers\StaffSalesController::class, 'create'])->name('staff.sales.create');
    Route::post('/staff/sales', [App\Http\Controllers\StaffSalesController::class, 'store'])->name('staff.sales.store');
    Route::get('/staff/sales/{id}', [App\Http\Controllers\StaffSalesController::class, 'show'])->name('staff.sales.show');
    Route::get('/staff/sales/{id}/edit', [App\Http\Controllers\StaffSalesController::class, 'edit'])->name('staff.sales.edit');
    Route::put('/staff/sales/{id}', [App\Http\Controllers\StaffSalesController::class, 'update'])->name('staff.sales.update');

    // Staff Products (Input Instrumen Masuk)
    Route::get('/staff/products', [App\Http\Controllers\StaffProductController::class, 'index'])->name('staff.products.index');
    Route::get('/staff/products/create', [App\Http\Controllers\StaffProductController::class, 'create'])->name('staff.products.create');
    Route::post('/staff/products', [App\Http\Controllers\StaffProductController::class, 'store'])->name('staff.products.store');
    Route::get('/staff/products/{id}', [App\Http\Controllers\StaffProductController::class, 'show'])->name('staff.products.show');
    Route::get('/staff/products/{id}/edit', [App\Http\Controllers\StaffProductController::class, 'edit'])->name('staff.products.edit');
    Route::put('/staff/products/{id}', [App\Http\Controllers\StaffProductController::class, 'update'])->name('staff.products.update');

    // Admin Users Management
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/export', [AdminUserController::class, 'export'])->name('admin.users.export');
    Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{id}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/check-pending-products', [App\Http\Controllers\AdminProductController::class, 'checkPendingProducts'])->name('check.pending');

    // Admin Staffs Management
    Route::get('/admin/staffs', [AdminStaffController::class, 'index'])->name('admin.staffs.index');
    Route::get('/admin/staffs/create', [AdminStaffController::class, 'create'])->name('admin.staffs.create');
    Route::post('/admin/staffs', [AdminStaffController::class, 'store'])->name('admin.staffs.store');
    Route::get('/admin/staffs/{id}/edit', [AdminStaffController::class, 'edit'])->name('admin.staffs.edit');
    Route::put('/admin/staffs/{id}', [AdminStaffController::class, 'update'])->name('admin.staffs.update');
    Route::delete('/admin/staffs/{id}', [AdminStaffController::class, 'destroy'])->name('admin.staffs.destroy');
    Route::post('/admin/staffs/{id}/reset-points', [AdminStaffController::class, 'resetPoints'])->name('admin.staffs.resetPoints');

    // Admin Assignments Management
    Route::get('/admin/assignments', [AdminAssignmentController::class, 'index'])->name('admin.assignments.index');
    Route::get('/admin/assignments/create', [AdminAssignmentController::class, 'create'])->name('admin.assignments.create');
    Route::post('/admin/assignments', [AdminAssignmentController::class, 'store'])->name('admin.assignments.store');
    Route::get('/admin/assignments/{id}', [AdminAssignmentController::class, 'show'])->name('admin.assignments.show');
    Route::get('/admin/assignments/{id}/edit', [AdminAssignmentController::class, 'edit'])->name('admin.assignments.edit');
    Route::put('/admin/assignments/{id}', [AdminAssignmentController::class, 'update'])->name('admin.assignments.update');
    Route::delete('/admin/assignments/{id}', [AdminAssignmentController::class, 'destroy'])->name('admin.assignments.destroy');
    Route::get('/admin/assignments/api/new', [AdminAssignmentController::class, 'getNewAssignments'])->name('admin.assignments.api.new');

    // Staff Assignments
    Route::get('/staff/assignments', [StaffAssignmentController::class, 'index'])->name('staff.assignments.index');
    Route::get('/staff/assignments/{id}', [StaffAssignmentController::class, 'show'])->name('staff.assignments.show');
    Route::put('/staff/assignments/{id}/status', [StaffAssignmentController::class, 'updateStatus'])->name('staff.assignments.updateStatus');
    Route::get('/staff/assignments/unread/count', [StaffAssignmentController::class, 'getUnreadCount'])->name('staff.assignments.unreadCount');
    Route::get('/staff/assignments/api/new', [StaffAssignmentController::class, 'getNewAssignments'])->name('staff.assignments.api.new');

    // Staff Attendance (Only for staff - validation in controller)
    Route::get('/staff/attendance', [StaffAttendanceController::class, 'index'])->name('staff.attendance.index');
    Route::post('/staff/attendance/check-in', [StaffAttendanceController::class, 'checkIn'])->name('staff.attendance.checkIn');
    Route::post('/staff/attendance/check-out', [StaffAttendanceController::class, 'checkOut'])->name('staff.attendance.checkOut');
    Route::get('/staff/attendance/history', [StaffAttendanceController::class, 'history'])->name('staff.attendance.history');
    Route::get('/staff/attendance/token', [StaffAttendanceController::class, 'getCurrentToken'])->name('staff.attendance.getToken');


    // Staff Shift Schedule
    Route::get('/staff/shifts', [App\Http\Controllers\StaffShiftController::class, 'index'])->name('staff.shifts.index');
    Route::get('/staff/shifts/api/new', [App\Http\Controllers\StaffShiftController::class, 'getNewSchedules'])->name('staff.shifts.api.new');

    // Staff Leave Requests
    Route::get('/staff/leaves', [App\Http\Controllers\StaffLeaveController::class, 'index'])->name('staff.leaves.index');
    Route::get('/staff/leaves/create', [App\Http\Controllers\StaffLeaveController::class, 'create'])->name('staff.leaves.create');
    Route::post('/staff/leaves', [App\Http\Controllers\StaffLeaveController::class, 'store'])->name('staff.leaves.store');
    Route::get('/staff/leaves/{id}', [App\Http\Controllers\StaffLeaveController::class, 'show'])->name('staff.leaves.show');
    Route::get('/staff/leaves/api/updates', [App\Http\Controllers\StaffLeaveController::class, 'getUpdatedLeaveRequests'])->name('staff.leaves.api.updates');

    // Admin Attendance Report (Only for admin)
    Route::get('/admin/attendance', [AdminAttendanceController::class, 'index'])->name('admin.attendance.index');
    Route::get('/admin/attendance/export', [AdminAttendanceController::class, 'export'])->name('admin.attendance.export');
    Route::get('/admin/attendance/staff/{staffId}', [AdminAttendanceController::class, 'showStaff'])->name('admin.attendance.staff');
    Route::delete('/admin/attendance/{id}', [AdminAttendanceController::class, 'destroy'])->name('admin.attendance.destroy');
    Route::get('/admin/attendance/api/new', [AdminAttendanceController::class, 'getNewAttendances'])->name('admin.attendance.api.new');
    Route::post('/admin/attendance/reset', [AdminAttendanceController::class, 'monthlyReset'])->name('admin.attendance.reset');

    // Admin Shift Schedule
    Route::get('/admin/shifts', [App\Http\Controllers\AdminShiftController::class, 'index'])->name('admin.shifts.index');
    Route::get('/admin/shifts/create', [App\Http\Controllers\AdminShiftController::class, 'create'])->name('admin.shifts.create');
    Route::post('/admin/shifts', [App\Http\Controllers\AdminShiftController::class, 'store'])->name('admin.shifts.store');
    Route::get('/admin/shifts/{weekStart}/edit', [App\Http\Controllers\AdminShiftController::class, 'edit'])->name('admin.shifts.edit');
    Route::put('/admin/shifts/{weekStart}', [App\Http\Controllers\AdminShiftController::class, 'update'])->name('admin.shifts.update');
    Route::delete('/admin/shifts/{weekStart}', [App\Http\Controllers\AdminShiftController::class, 'destroy'])->name('admin.shifts.destroy');
    Route::get('/admin/shifts/times', [App\Http\Controllers\AdminShiftTimeController::class, 'index'])->name('admin.shifts.times');
    Route::put('/admin/shifts/times', [App\Http\Controllers\AdminShiftTimeController::class, 'update'])->name('admin.shifts.times.update');

    // Admin Leave Requests Management
    Route::get('/admin/leaves', [App\Http\Controllers\AdminLeaveController::class, 'index'])->name('admin.leaves.index');
    Route::get('/admin/leaves/{id}', [App\Http\Controllers\AdminLeaveController::class, 'show'])->name('admin.leaves.show');
    Route::post('/admin/leaves/{id}/approve', [App\Http\Controllers\AdminLeaveController::class, 'approve'])->name('admin.leaves.approve');
    Route::post('/admin/leaves/{id}/reject', [App\Http\Controllers\AdminLeaveController::class, 'reject'])->name('admin.leaves.reject');
    Route::get('/admin/leaves/api/new', [App\Http\Controllers\AdminLeaveController::class, 'getNewLeaveRequests'])->name('admin.leaves.api.new');

    // Admin Payroll Management
    // Admin Payroll Management
    Route::get('/admin/payroll', [App\Http\Controllers\AdminPayrollController::class, 'index'])->name('admin.payroll.index');
    Route::get('/admin/payroll/create', [App\Http\Controllers\AdminPayrollController::class, 'create'])->name('admin.payroll.create');
    Route::post('/admin/payroll', [App\Http\Controllers\AdminPayrollController::class, 'store'])->name('admin.payroll.store');
    Route::get('/admin/payroll/{id}/edit', [App\Http\Controllers\AdminPayrollController::class, 'edit'])->name('admin.payroll.edit');
    Route::put('/admin/payroll/{id}', [App\Http\Controllers\AdminPayrollController::class, 'update'])->name('admin.payroll.update');
    Route::get('/admin/payroll/{id}', [App\Http\Controllers\AdminPayrollController::class, 'show'])->name('admin.payroll.show');
    Route::get('/admin/payroll/{id}/download', [App\Http\Controllers\AdminPayrollController::class, 'downloadPdf'])->name('admin.payroll.download');
    Route::delete('/admin/payroll/{id}', [App\Http\Controllers\AdminPayrollController::class, 'destroy'])->name('admin.payroll.destroy');
    Route::post('/admin/payroll/{id}/send-auto-wa', [App\Http\Controllers\AdminPayrollController::class, 'sendAutoWa'])->name('admin.payroll.sendAutoWa');

    // ✅ TAMBAHKAN ROUTE INI (sebelum routes lainnya)
    Route::get('/admin/payroll/preview-sales-fee', [App\Http\Controllers\AdminPayrollController::class, 'previewSalesFee'])->name('admin.payroll.preview');

    // Staff Payroll Management
    Route::get('/staff/payroll', [App\Http\Controllers\StaffPayrollController::class, 'index'])->name('staff.payroll.index');
    Route::get('/staff/payroll/{id}', [App\Http\Controllers\StaffPayrollController::class, 'show'])->name('staff.payroll.show');
    Route::get('/staff/payroll/{id}/download', [App\Http\Controllers\StaffPayrollController::class, 'downloadPdf'])->name('staff.payroll.download');

    // Staff Members Management
    Route::get('/staff/members', [App\Http\Controllers\StaffMembersController::class, 'index'])->name('staff.members.index');
    Route::get('/staff/members/create', [App\Http\Controllers\StaffMembersController::class, 'create'])->name('staff.members.create');
    Route::post('/staff/members', [App\Http\Controllers\StaffMembersController::class, 'store'])->name('staff.members.store');
    Route::get('/staff/members/{id}', [App\Http\Controllers\StaffMembersController::class, 'show'])->name('staff.members.show');
    Route::post('/staff/members/{id}/visit', [App\Http\Controllers\StaffMembersController::class, 'recordVisit'])->name('staff.members.visit');
    Route::get('/staff/members/search/{memberId}', [App\Http\Controllers\StaffMembersController::class, 'getByMemberId'])->name('staff.members.search');
    Route::post('/staff/members/{id}/send-wa', [App\Http\Controllers\StaffMembersController::class, 'sendWelcomeWa'])->name('staff.members.send_wa');
    Route::post('/staff/members/{id}/send-wa', [App\Http\Controllers\StaffMembersController::class, 'sendWelcomeWa'])->name('staff.members.send_wa');

    // Staff Rewards Management
    Route::get('/staff/rewards', [App\Http\Controllers\StaffRewardsController::class, 'index'])->name('staff.rewards.index');
    Route::get('/staff/rewards/create', [App\Http\Controllers\StaffRewardsController::class, 'create'])->name('staff.rewards.create');
    Route::post('/staff/rewards', [App\Http\Controllers\StaffRewardsController::class, 'store'])->name('staff.rewards.store');
    Route::get('/staff/rewards/{id}/edit', [App\Http\Controllers\StaffRewardsController::class, 'edit'])->name('staff.rewards.edit');
    Route::put('/staff/rewards/{id}', [App\Http\Controllers\StaffRewardsController::class, 'update'])->name('staff.rewards.update');
    Route::post('/staff/rewards/claims/{id}/fulfill', [App\Http\Controllers\StaffRewardsController::class, 'fulfillClaim'])->name('staff.rewards.claims.fulfill');

    // Staff Daily Sales
    Route::get('/staff/daily-sales', [App\Http\Controllers\StaffDailySalesController::class, 'index'])->name('staff.daily-sales.index');
    Route::get('/staff/daily-sales/create', [App\Http\Controllers\StaffDailySalesController::class, 'create'])->name('staff.daily-sales.create');
    Route::post('/staff/daily-sales', [App\Http\Controllers\StaffDailySalesController::class, 'store'])->name('staff.daily-sales.store');
    Route::get('/staff/daily-sales/{id}', [App\Http\Controllers\StaffDailySalesController::class, 'show'])->name('staff.daily-sales.show');

    // Admin Daily Sales (View all staff daily sales)
    Route::get('/admin/daily-sales', [App\Http\Controllers\AdminDailySalesController::class, 'index'])->name('admin.daily-sales.index');
    Route::get('/admin/daily-sales/export', [App\Http\Controllers\AdminDailySalesController::class, 'export'])->name('admin.daily-sales.export');
    Route::get('/admin/daily-sales/{id}', [App\Http\Controllers\AdminDailySalesController::class, 'show'])->name('admin.daily-sales.show');
    Route::get('/admin/daily-sales/{id}/edit', [App\Http\Controllers\AdminDailySalesController::class, 'edit'])->name('admin.daily-sales.edit');
    Route::put('/admin/daily-sales/{id}', [App\Http\Controllers\AdminDailySalesController::class, 'update'])->name('admin.daily-sales.update');
    Route::delete('/admin/daily-sales/{id}', [App\Http\Controllers\AdminDailySalesController::class, 'destroy'])->name('admin.daily-sales.destroy');
    Route::get('/admin/daily-sales/api/new', [App\Http\Controllers\AdminDailySalesController::class, 'getNewDailySales'])->name('admin.daily-sales.api.new');

    // Admin Products Management
    Route::get('/admin/products', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::post('/admin/products/mark-viewed', [AdminProductController::class, 'markViewed'])->name('admin.products.markViewed');
    Route::get('/admin/products/export', [AdminProductController::class, 'export'])->name('admin.products.export');
    Route::get('/admin/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('/admin/products', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('/admin/products/{id}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/admin/products/{id}', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/admin/products/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::get('/admin/products/api/new', [AdminProductController::class, 'getNewProducts'])->name('admin.products.api.new');

    // Notifications
    Route::get('/notifications/all', [App\Http\Controllers\NotificationController::class, 'getAllNotifications'])->name('notifications.all');
    Route::post('/notifications/mark-viewed', [App\Http\Controllers\NotificationController::class, 'markViewed'])->name('notifications.mark-viewed');

    // Admin Sales Instruments Management
    Route::resource('admin/sales', AdminSalesController::class)->names([
        'index' => 'admin.sales.index',
        'create' => 'admin.sales.create',
        'store' => 'admin.sales.store',
        'show' => 'admin.sales.show',
        'edit' => 'admin.sales.edit',
        'update' => 'admin.sales.update',
        'destroy' => 'admin.sales.destroy',
    ]);
    Route::get('/admin/sales/export', [App\Http\Controllers\AdminSalesController::class, 'export'])->name('admin.sales.export');

    // Admin Landing Page Management
    Route::prefix('admin/landing')->name('admin.landing.')->group(function () {
        Route::get('/header', [AdminLandingPageController::class, 'header'])->name('header');
        Route::post('/header', [AdminLandingPageController::class, 'updateHeader'])->name('header.update');
        Route::get('/main', [AdminLandingPageController::class, 'main'])->name('main');
        Route::post('/main', [AdminLandingPageController::class, 'updateMain'])->name('main.update');
        Route::get('/about', [AdminLandingPageController::class, 'about'])->name('about');
        Route::post('/about', [AdminLandingPageController::class, 'updateAbout'])->name('about.update');
        Route::get('/brands', [AdminLandingPageController::class, 'brands'])->name('brands');
        Route::post('/brands/header', [AdminLandingPageController::class, 'updateBrandsHeader'])->name('brands.header.update');
        Route::post('/brands', [AdminLandingPageController::class, 'storeBrand'])->name('brands.store');
        Route::put('/brands/{id}', [AdminLandingPageController::class, 'updateBrand'])->name('brands.update');
        Route::delete('/brands/{id}', [AdminLandingPageController::class, 'destroyBrand'])->name('brands.destroy');
        Route::get('/products', [AdminLandingPageController::class, 'products'])->name('products');
        Route::post('/products/header', [AdminLandingPageController::class, 'updateProductsHeader'])->name('products.header.update');
        Route::post('/products/import', [AdminLandingPageController::class, 'importProducts'])->name('products.import');
        Route::post('/products', [AdminLandingPageController::class, 'storeProduct'])->name('products.store');
        Route::put('/products/{id}', [AdminLandingPageController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{id}', [AdminLandingPageController::class, 'destroyProduct'])->name('products.destroy');
        Route::post('/products/payments', [AdminLandingPageController::class, 'storePaymentIcon'])->name('payments.store');
        Route::delete('/products/payments/destroy', [AdminLandingPageController::class, 'destroyPaymentIcon'])->name('payments.destroy');
        // Ubah nama rute pada baris 179 menjadi 'accessories.header.update'
        Route::get('/accessories', [AdminLandingPageController::class, 'accessories'])->name('accessories');
        Route::post('/accessories/update', [AdminLandingPageController::class, 'updateAccessories'])->name('accessories.header.update'); // <--- DIUBAH
        Route::post('/accessories/store', [AdminLandingPageController::class, 'storeAccessory'])->name('accessories.store');
        Route::put('/accessories/{id}', [AdminLandingPageController::class, 'updateAccessory'])->name('accessories.update');
        Route::get('/gallery', [AdminLandingPageController::class, 'gallery'])->name('gallery');
        Route::post('/gallery/store', [AdminLandingPageController::class, 'storeGallery'])->name('gallery.store');
        Route::put('/gallery/{id}', [AdminLandingPageController::class, 'updateGallery'])->name('gallery.update');
        Route::delete('/gallery/{id}', [AdminLandingPageController::class, 'destroyGallery'])->name('gallery.destroy');
        Route::get('/service', [AdminLandingPageController::class, 'service'])->name('service');

        Route::get('/merchandise', [AdminLandingPageController::class, 'merchandise'])->name('merchandise');
        Route::post('/merchandise/header', [AdminLandingPageController::class, 'updateMerchandiseHeader'])->name('merchandise.header');
        Route::post('/merchandise/store', [AdminLandingPageController::class, 'storeMerchandise'])->name('merchandise.store');
        Route::delete('/merchandise/{id}', [AdminLandingPageController::class, 'destroyMerchandise'])->name('merchandise.destroy');
        Route::put('/merchandise/{id}', [AdminLandingPageController::class, 'updateMerchandise'])->name('merchandise.update');

        // Route untuk memproses update title dan subtitle
        Route::post('/service/header', [AdminLandingPageController::class, 'updateServiceHeader'])->name('service.header');

        // Route untuk menambah item service baru
        Route::post('/service/store', [AdminLandingPageController::class, 'storeService'])->name('service.store');

        // Route untuk menghapus service
        Route::delete('/service/{id}', [AdminLandingPageController::class, 'destroyService'])->name('service.destroy');
        Route::get('/contact', [AdminLandingPageController::class, 'contact'])->name('contact');
        Route::post('/contact/update', [AdminLandingPageController::class, 'updateContact'])->name('contact.update');
    });
});

// Member Routes
Route::get('/member/register', [MemberController::class, 'showRegisterForm'])->name('member.register');
Route::post('/member/register', [MemberController::class, 'register'])->name('member.register.submit');
Route::get('/member/login', [MemberController::class, 'showLoginForm'])->name('member.login');
Route::post('/member/login', [MemberController::class, 'login']);
Route::post('/member/logout', [MemberController::class, 'logout'])->name('member.logout');
Route::get('/member/visits/count', [MemberController::class, 'visitCount'])->name('member.visits.count');

// Member Profile (Protected by session check in controller)
Route::get('/member/profile', [MemberController::class, 'profile'])->name('member.profile');
Route::get('/member/profile/edit', [MemberController::class, 'showEditForm'])->name('member.profile.edit');
Route::put('/member/profile', [MemberController::class, 'updateProfile'])->name('member.profile.update');
Route::post('/member/reward/claim', [MemberController::class, 'claimReward'])->name('member.reward.claim');
