<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\GenerateFolderController;
use App\Http\Controllers\UserFolderController;
use App\Http\Controllers\GuestFolderController;
use App\Http\Controllers\SocialController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// Public Routes (Accessible to Everyone)
Route::get('/', [LoginController::class, 'landing'])->name('landing.page');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login/submit', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::get('auth/{provider}', [SocialController::class, 'redirect']);
Route::get('auth/{provider}/callback', [SocialController::class, 'callback']);
Route::get('/forgot-password', [LoginController::class, 'forgotPassword'])->name('forgot.password');
Route::post('/forgot-password', [LoginController::class, 'sendResetLink'])->name('forgot.password.send');
Route::get('/reset-password', [LoginController::class, 'showResetForm'])->name('password.reset');
Route::get('/verify-code', [LoginController::class, 'showVerifyCodeForm'])->name('verify.code.form');
Route::post('/verify-code/submit', [LoginController::class, 'verifyCode'])->name('verify.code');
Route::post('/reset-password/submit', [LoginController::class, 'resetPassword'])->name('password.custom.reset');
Route::get('/basic/plan', [LoginController::class, 'basicPlan'])->name('basic.plan');
Route::post('/register-pay', [LoginController::class, 'registerAndPay'])->name('register.pay');
Route::get('/standard/plan', [LoginController::class, 'standardPlan'])->name('standard.plan');
Route::get('/premium/plan', [LoginController::class, 'premiumPlan'])->name('premium.plan');
Route::get('/success/{CHECKOUT_SESSION_ID}',[LoginController::class, 'sucessPayment'])->name('success.payment');

Route::middleware('auth')->group(function () {
    Route::get('send-otp', [LoginController::class, 'sendotp'])->name('send-otp');
    Route::get('show-otp', [LoginController::class, 'showotp'])->name('show-otp');
    Route::post('verify-otp', [LoginController::class, 'verifyotp'])->name('verify-otp');
    Route::middleware('verified')->group(function () {
        Route::post('/payment', [LoginController::class, 'proceedToPayment'])->name('payment.proceed');
        Route::get('/stripe-payment', [LoginController::class, 'showStripePaymentPage'])->name('stripe.payment');
        Route::get('/stripe-success', [LoginController::class, 'stripeSuccess'])->name('stripe.success');
        Route::get('/stripe-success2', [LoginController::class, 'stripeSuccess2'])->name('stripe.success2');
        Route::get('/stripe-receipt', [LoginController::class, 'stripeReceipt'])->name('stripe.receipt');
        Route::get('/stripe-receipt2', [LoginController::class, 'stripeReceipt2'])->name('stripe.receipt2');
        Route::get('/stripe-login', [LoginController::class, 'stripeLogin'])->name('stripe.login');
        Route::get('/stripe-login2', [LoginController::class, 'stripeLogin2'])->name('stripe.login2');
        Route::get('stripe-dashboard',[LoginController::class, 'stripeDashboard'])->name('stripe.dashboard');
    });

    // Logout
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout.show');
});

// Super Admin Routes (Requires Authentication & Role 1)
Route::middleware(['auth', 'role:1'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
    Route::get('/stripe', [SuperAdminController::class, 'finance'])->name('stripe');
    Route::get('/photo', [SuperAdminController::class, 'photo'])->name('photo');
    Route::get('/email', [SuperAdminController::class, 'email'])->name('email');
    Route::get('/homepage', [SuperAdminController::class, 'homepage'])->name('homepage');
    Route::post('/homepage', [SuperAdminController::class, 'homestore'])->name('homestore');
    Route::get('/user', [SuperAdminController::class, 'user'])->name('user');
    Route::post('/add-user', [SuperAdminController::class, 'addUser'])->name('add-user');
    Route::post('/edit-user', [SuperAdminController::class, 'editUser'])->name('edit-user');
    Route::post('/delete-user', [SuperAdminController::class, 'deleteUser'])->name('delete-user');
    Route::get('/search-user', [SuperAdminController::class, 'searchUser'])->name('search-user');
    Route::post('/stripe-configuration', [SuperAdminController::class, 'stripeupdate'])->name('stripe-update');
    Route::get('/invoice', [SuperAdminController::class, 'invoice'])->name('invoice');
    Route::get('/search/invoice', [SuperAdminController::class, 'searchInvoice'])->name('search.invoice');
    Route::get('/information', [SuperAdminController::class, 'information'])->name('information');
    Route::get('/login-details', [SuperAdminController::class, 'logindetails'])->name('login-details');
    Route::post('/login-update', [SuperAdminController::class, 'loginupdate'])->name('login-update');
    Route::post('/information-update', [SuperAdminController::class, 'informationupdate'])->name('information-update');
    Route::get('/file', [SuperAdminController::class, 'file'])->name('file');
    Route::get('/file/loan', [SuperAdminController::class, 'loan'])->name('file.loan');
    Route::post('/loan/next-step', [LoanController::class, 'nextStep'])->name('loan.nextStep');
    Route::get('/loan/previous-step', [LoanController::class, 'previousStep'])->name('loan.previousStep');
    Route::get('/folders', [FolderController::class, 'index'])->name('folders.index');
    Route::get('/subfolder/{folders}/{subfolder}', [FolderController::class, 'showSub'])->name('subfolder.show');
    Route::get('files/{id}', [FileController::class, 'show'])->name('files.show');
    Route::put('files/{id}', [FileController::class, 'update'])->name('files.update');
    Route::post('files/upload/{folder?}', [FileController::class, 'upload'])->name('files.upload');
    Route::get('files/download/{folder}/{file}', [FileController::class, 'download'])->name('files.download');
    Route::post('files/rename/{folder}/{file}', [FileController::class, 'rename'])->name('files.rename');
    Route::post('files/delete/{folder}/{file}', [FileController::class, 'delete'])->name('files.delete');
    //subfolder
    Route::post('files/upload/{folder}/{subfolder}', [FileController::class, 'sub_upload'])->name('sub.files.upload');
    Route::get('files/download/{folder}/{subfolder}/{file}', [FileController::class, 'sub_download'])->name('sub.files.download');
    Route::post('files/rename/{folder}/{subfolder}/{file}', [FileController::class, 'sub_rename'])->name('sub.files.rename');
    Route::post('files/delete/{folder}/{subfolder}/{file}', [FileController::class, 'sub_delete'])->name('sub.files.delete');
    //innerfolder
    Route::post('files/upload/{folder}/{subfolder}/{innerfolder}', [FileController::class, 'inner_upload'])->name('inner.files.upload');
    Route::get('files/download/{folder}/{subfolder}/{innerfolder}/{file}', [FileController::class, 'inner_download'])->name('inner.files.download');
    Route::post('files/rename/{folder}/{subfolder}/{innerfolder}/{file}', [FileController::class, 'inner_rename'])->name('inner.files.rename');
    Route::post('files/delete/{folder}/{subfolder}/{innerfolder}/{file}', [FileController::class, 'inner_delete'])->name('inner.files.delete');
    //rename folders
    Route::post('/folders/rename', [FolderController::class, 'rename_folder'])->name('folders.rename');
    Route::post('/folders/delete', [FolderController::class, 'delete_folder'])->name('folders.delete');
    //rename subfolders
    Route::post('/subfolder/rename', [FolderController::class, 'renameSubfolder'])->name('subfolder.rename');
    Route::post('/subfolder/delete', [FolderController::class, 'deleteSubfolder'])->name('subfolder.delete');
    //rename innerfolders
    Route::post('/innerfolder/rename', [FolderController::class, 'renameInnerFolder'])->name('innerfolder.rename');
    Route::post('/innerfolder/delete', [FolderController::class, 'deleteInnerFolder'])->name('innerfolder.delete');

    // Generate Folder
    Route::post('/add/mainfolder', [GenerateFolderController::class, 'addMainFolder'])->name('add.folders');
    Route::post('/edit/mainfolder', [GenerateFolderController::class, 'editMainFolder'])->name('update.folders');
    Route::post('/delete/mainfolder', [GenerateFolderController::class, 'deleteMainFolder'])->name('delete.folders');
    Route::post('/subfolder/show', [GenerateFolderController::class, 'folder_show'])->name('folders.show');
    Route::post('/subfolder/add', [GenerateFolderController::class, 'addSubFolder'])->name('add.subfolders');
    Route::post('/subfolder/edit', [GenerateFolderController::class, 'editSubFolder'])->name('update.subfolders');
    Route::post('/subfolder/delete', [GenerateFolderController::class, 'deleteSubFolder'])->name('delete.subfolders');
    Route::post('/innerfolder/add', [GenerateFolderController::class, 'addInnerFolder'])->name('add.innerfolders');
    Route::post('/innerfolder/show', [GenerateFolderController::class, 'inner_show'])->name('innerfolders.show');
    Route::post('/innerfolder/edit', [GenerateFolderController::class, 'editInnerFolder'])->name('update.innerfolders');
    Route::post('/innerfolder/delete', [GenerateFolderController::class, 'deleteInnerFolder'])->name('delete.innerfolders');
    Route::post('/childfolder/add', [GenerateFolderController::class, 'addChildFolder'])->name('add.childfolders');
    Route::post('/childfolder/show', [GenerateFolderController::class, 'child_show'])->name('childfolders.show');
    Route::post('/childfolder/edit', [GenerateFolderController::class, 'editChildFolder'])->name('update.childfolders');
    Route::post('/childfolder/delete', [GenerateFolderController::class, 'deleteChildFolder'])->name('delete.childfolders');
    Route::post('/innerchildfolder/add', [GenerateFolderController::class, 'addInnerChildFolder'])->name('add.innerchildfolders');
    Route::post('/innerchildfolder/show', [GenerateFolderController::class, 'innerchild_show'])->name('innerchildfolders.show');
    Route::post('/innerchildfolder/edit', [GenerateFolderController::class, 'editInnerChildFolder'])->name('update.innerchildfolders');
    Route::post('/innerchildfolder/delete', [GenerateFolderController::class, 'deleteInnerChildFolder'])->name('delete.innerchildfolders');
    Route::post('/lastfolder/show', [GenerateFolderController::class, 'lastfolder_show'])->name('lastfolders.show');
    Route::post('/folder-template/copy', [FolderController::class, 'copyStructure'])
    ->name('folder-template.copy');

});

// Admin Routes (Requires Authentication & Role 2)
Route::middleware(['auth', 'role:2'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::get('/manage', [AdminController::class, 'manage'])->name('manage');
    Route::post('/profile-update', [AdminController::class, 'profileupdate'])->name('profile-update');
    Route::get('/login-details', [AdminController::class, 'logindetails'])->name('login-details');
    Route::post('login-update', [AdminController::class, 'loginupdate'])->name('login-update');
    Route::get('/search-guest', [AdminController::class, 'searchGuest'])->name('search-guest');
    Route::get('/file', [AdminController::class, 'file'])->name('file');
    Route::get('/file/loan', [AdminController::class, 'loan'])->name('file.loan');
    Route::post('/loan/next-step', [LoanController::class, 'nextStep'])->name('loan.nextStep');
    Route::get('/loan/previous-step', [LoanController::class, 'previousStep'])->name('loan.previousStep');
    Route::get('/folders', [UserFolderController::class, 'index'])->name('folders.index');
    Route::get('/shared/files', [UserFolderController::class, 'shared_files'])->name('shared.files');
    Route::post('/subfolder/show', [UserFolderController::class, 'folder_show'])->name('folders.show');
    Route::post('/innerfolder/show', [UserFolderController::class, 'inner_show'])->name('innerfolders.show');
    Route::post('/childfolder/show', [UserFolderController::class, 'child_show'])->name('childfolders.show');
    Route::post('/innerchildfolder/show', [UserFolderController::class, 'innerchild_show'])->name('innerchildfolders.show');
    Route::post('/lastfolder/show', [UserFolderController::class, 'lastfolder_show'])->name('lastfolders.show');
    Route::post('files/upload', [UserFolderController::class, 'files_upload'])->name('files.upload');
    Route::post('files/update', [UserFolderController::class, 'files_update'])->name('files.update');
    Route::post('files/delete', [UserFolderController::class, 'files_delete'])->name('files.delete');
    Route::get('files/search', [UserFolderController::class, 'files_search'])->name('files.search');
    Route::post('files/last_folder', [UserFolderController::class, 'last_folder_table'])->name('files.last_show');
    Route::post('last/files/upload', [UserFolderController::class, 'last_file_upload'])->name('files.last_file_upload');
    Route::post('last/files/update', [UserFolderController::class, 'last_file_update'])->name('files.last_file_update');
    Route::post('last/files/delete', [UserFolderController::class, 'last_file_delete'])->name('files.last_file_delete');
    Route::get('last/files/search', [UserFolderController::class, 'last_file_search'])->name('files.last_file_search');
    Route::post('innerchild/files/upload', [UserFolderController::class, 'innerchild_folder_upload'])->name('files.innerchild_folder_upload');
    Route::post('innerchild/files/update', [UserFolderController::class, 'innerchild_folder_update'])->name('files.innerchild_folder_update');
    Route::post('innerchild/files/delete', [UserFolderController::class, 'innerchild_folder_delete'])->name('files.innerchild_folder_delete');
    Route::get('innerchild/files/search', [UserFolderController::class, 'innerchild_folder_search'])->name('files.innerchild_folder_search');
    Route::get('/invoice', [AdminController::class, 'invoice'])->name('invoice');
    Route::get('/search/invoice', [AdminController::class, 'searchInvoice'])->name('search.invoice');
    Route::get('/billing', [AdminController::class, 'billing'])->name('billing');
    Route::post('/invitations',[AdminController::class, 'store_invite'])->name('store.invite');

    Route::post('/add-user', [AdminController::class, 'addUser'])->name('add-user');
    Route::post('/edit-user', [AdminController::class, 'editUser'])->name('edit-user');
    Route::post('/delete-user', [AdminController::class, 'deleteUser'])->name('delete-user');
    Route::get('/search-user', [AdminController::class, 'searchUser'])->name('search-user');
    Route::get('/folders/{id}/files', [UserFolderController::class, 'viewFiles'])->name('folders.view.files');
    Route::put('/admin/folders/update', [UserFolderController::class, 'update'])->name('folders.update');
    Route::delete('/admin/folders/{id}', [UserFolderController::class, 'destroy'])->name('folders.destroy');
    // In your web.php
    Route::get('/admin/files/view/{id}', [UserFolderController::class, 'viewFile'])->name('files.view');
    Route::post('/admin/add/folder', [UserFolderController::class, 'addFolder'])->name('folder.add');
     Route::post('/admin/add/subfolderfolder', [UserFolderController::class, 'addSubFolder'])->name('subfolder.add');
    Route::post('/linked-account/create-user', [AdminController::class, 'createUser'])->name('linked-account.create-user');
    Route::get('/account-link', [AdminController::class, 'accountLink'])->name('account-link');
    Route::post('/switch-account/{id}', [AdminController::class, 'switch'])->name('switch.account');
    Route::post('/switch-back', [AdminController::class, 'switchBack'])->name('switch.back');
    Route::post('/switch-role/{roleId}', [AdminController::class, 'switchRole'])->name('role.switch');

    Route::post('/admin/folder/share', [UserFolderController::class, 'inviteFolder'])->name('folder.share');
     Route::post('/switch-role', [UserController::class, 'switchRole'])->name('switch-role');

});

// User Routes (Requires Authentication & Role 3)
Route::middleware(['auth', 'role:3'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile-update', [UserController::class, 'profileupdate'])->name('profile-update');
    Route::get('/login-details', [UserController::class, 'logindetails'])->name('login-details');
    Route::post('/login-update', [UserController::class, 'loginupdate'])->name('login-update');
    Route::get('/folders', [GuestFolderController::class, 'index'])->name('folders.index');
    Route::post('/subfolders', [GuestFolderController::class, 'show'])->name('folders.show');
    Route::post('/innerfolder', [GuestFolderController::class, 'inner_folder'])->name('innerfolders.show');
    Route::post('/childfolder',[GuestFolderController::class, 'child_folder'])->name('childfolders.show');
    Route::post('/innerchild',[GuestFolderController::class, 'innerchild_folder'])->name('innerchildfolders.show');
    Route::post('/lastfolder',[GuestFolderController::class, 'last_folder'])->name('lastfolders.show');
    Route::post('/last-table',[GuestFolderController::class, 'last_folder_table'])->name('last-table.show');
    Route::get('/secure-file-force/{folder}/{filename}', [GuestFolderController::class, 'viewPrivateFile'])
    ->name('files.secure.view.force');
    Route::post('/switch-back', [UserController::class, 'switchBack'])->name('switch.back');
    Route::post('/switch-account/{id}', [UserController::class, 'switch'])->name('switch.account');
    Route::post('/switch-role/{roleId}', [UserController::class, 'switchRole'])->name('role.switch');
   Route::get('/proxy-file/{folder}/{filename}/{user}', [GuestFolderController::class, 'proxyFile'])
    ->name('files.proxy.public')
    ->middleware('signed');
    Route::get('/account-link', [UserController::class, 'accountLink'])->name('account-link');
    Route::post('/switch-role', [UserController::class, 'switchRole'])->name('switch-role');

});
