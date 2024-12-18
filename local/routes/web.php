<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\PublicAccessController::class, 'index'])->name("public");
Route::get('/public_access', [App\Http\Controllers\PublicAccessController::class, 'public_access'])->name("publicNew");
Route::get('/previewCapstone/{id}', [App\Http\Controllers\PublicAccessController::class, 'preview']);
Route::get('/registerCoordinator', [App\Http\Controllers\CoordinatorRegisterController::class, 'index'])->name('coordinatorRegister');


// Invite Adviser
Route::post('/inviteAdviser', [App\Http\Controllers\InviteAdviserController::class, 'send']);
Route::get('/acceptInvitation/{token}', [App\Http\Controllers\InviteAdviserController::class, 'accept']);

Auth::routes();
// main Landing Page
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Coordinator 
Route::get('/coordinator-dashboard', [App\Http\Controllers\CoordinatorDashboardController::class, 'index'])->name('coordinator.dashboard');
Route::get('/students', [App\Http\Controllers\StudentsController::class, 'index'])->name('students');
Route::put('/students/enable/{id}', [App\Http\Controllers\StudentsController::class, 'updateEnable']);
Route::put('/students/disable/{id}', [App\Http\Controllers\StudentsController::class, 'updateDisable']);
Route::get('/advisers', [App\Http\Controllers\AdvisersController::class, 'index'])->name('advisers');
Route::get('/groupings', [App\Http\Controllers\GroupingsController::class, 'index']);
Route::post('/saveGroupings', [App\Http\Controllers\GroupingsController::class, 'save']);
Route::delete('/deleteGroupings/{reference}', [App\Http\Controllers\GroupingsController::class, 'delete']);
Route::get('/capstones', [App\Http\Controllers\CoordinatorCapstoneController::class, 'index'])->name('capstones');
Route::post('/coordinator_transaction/{id}', [App\Http\Controllers\CoordinatorCapstoneController::class, 'coordinator_transaction']);
Route::get('/publishedCapstone', [App\Http\Controllers\CoordinatorCapstoneController::class, 'publishedCapstone'])->name('publishedCapstone');
Route::put('/unpublished_capstone/{id}', [App\Http\Controllers\CoordinatorCapstoneController::class, 'unpublished_capstone']);


// Advisers / Panels
Route::get('/adviser-dashboard', [App\Http\Controllers\AdviserPageControllers\DashboardController::class, 'index'])->name('adviser.dashboard');
Route::get('/adviser-dashboard/adviser', [App\Http\Controllers\AdviserPageControllers\DashboardController::class, 'adviserTable']);
Route::get('/adviser-dashboard/panel', [App\Http\Controllers\AdviserPageControllers\DashboardController::class, 'panelTable']);
Route::get('/getStudentInfo/{reference}', [App\Http\Controllers\AdviserPageControllers\DashboardController::class, 'getStudentInfo']);
Route::put('/saveScoreAdviser/{reference}', [App\Http\Controllers\AdviserPageControllers\DashboardController::class, 'saveScoreAdviser']);
Route::put('/saveScorePanel/{reference}', [App\Http\Controllers\AdviserPageControllers\DashboardController::class, 'saveScorePanel']);
Route::get('/consultation-hours', [App\Http\Controllers\AdviserPageControllers\ConsultationHoursController::class, 'index'])->name('consultation-hours');
Route::post('/saveConsultationSchedule', [App\Http\Controllers\AdviserPageControllers\ConsultationHoursController::class, 'addSchedule']);
Route::get('/getMySchedule/{id}', [App\Http\Controllers\AdviserPageControllers\ConsultationHoursController::class, 'getMySchedule']);
Route::put('/updateMySchedule/{id}', [App\Http\Controllers\AdviserPageControllers\ConsultationHoursController::class, 'updateMySchedule']);
Route::delete('/deleteMySchedule/{id}', [App\Http\Controllers\AdviserPageControllers\ConsultationHoursController::class, 'deleteMySchedule']);
Route::get('/viewCapstone/{reference}', [App\Http\Controllers\AdviserPageControllers\DashboardController::class, 'viewCapstone']);
Route::post('/finalizedGrades', [App\Http\Controllers\AdviserPageControllers\DashboardController::class, 'finalizedGrades']);
Route::get('/checkGrades/{ref}', [App\Http\Controllers\AdviserPageControllers\DashboardController::class, 'checkGrades']);
Route::post('/publishcapstone/{reference}', [App\Http\Controllers\AdviserPageControllers\DashboardController::class, 'publishcapstone']);
Route::post('/saveCapstone/{reference}', [App\Http\Controllers\AdviserPageControllers\DashboardController::class, 'saveCapstone']);
Route::get('/getComments/{reference}', [App\Http\Controllers\AdviserPageControllers\DashboardController::class, 'getComments']);

// Student
Route::get('/student-dashboard', [App\Http\Controllers\StudentPageControllers\DashboardController::class, 'index'])->name('student.dashboard');
Route::get('/consultation', [App\Http\Controllers\StudentPageControllers\ConsultationController::class, 'index'])->name('consultation');
Route::get('/getMyAdviserConsultationHours', [App\Http\Controllers\StudentPageControllers\ConsultationController::class, 'getMyAdviserConsultationHours']);
Route::get('/getGroupings', [App\Http\Controllers\StudentPageControllers\ConsultationController::class, 'getGroupings']);
Route::get('/myCapstone', [App\Http\Controllers\StudentPageControllers\CapstoneController::class, 'index'])->name('myCapstone');
Route::post('/uploadCapstone', [App\Http\Controllers\StudentPageControllers\CapstoneController::class, 'uploadCapstone']);
Route::delete('/removeMyCapstone/{reference}', [App\Http\Controllers\StudentPageControllers\CapstoneController::class, 'removeMyCapstone']);
Route::post('/updateCapstoneDesc', [App\Http\Controllers\StudentPageControllers\CapstoneController::class, 'updateCapstoneDesc']);
Route::post('/updateCapstoneFile', [App\Http\Controllers\StudentPageControllers\CapstoneController::class, 'updateCapstoneFile']);
Route::get('/getCommentsStudents/{reference}', [App\Http\Controllers\StudentPageControllers\CapstoneController::class, 'getComments']);

// Messages Routes
Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages');
Route::get('/messages/{group_reference}', [App\Http\Controllers\MessageController::class, 'view']);
Route::post('/sendMessage', [App\Http\Controllers\MessageController::class, 'send']);
Route::get('/getMyMessages/{group_reference}', [App\Http\Controllers\MessageController::class, 'getMyMessages']);

// Global Route
Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings');
Route::put('/settings/updateMyInfo', [App\Http\Controllers\SettingsController::class, 'update']);

// Calendar
Route::get('/getTakenDate', [App\Http\Controllers\CalendarController::class, 'getTakenDate']);
Route::get('/getAllDate', [App\Http\Controllers\CalendarController::class, 'getAllDate']);
Route::get('/getAllDateCoordinator', [App\Http\Controllers\CalendarController::class, 'getAllDateCoordinator']);
Route::get('/checkDate/{date}', [App\Http\Controllers\CalendarController::class, 'checkDate']);
Route::get('/checkDateActions/{date}', [App\Http\Controllers\CalendarController::class, 'checkDateActions']);
Route::post('/requestDefense/{date}', [App\Http\Controllers\CalendarController::class, 'requestDefense']);
Route::post('/requestConsultation/{date}/{user_id}', [App\Http\Controllers\CalendarController::class, 'requestConsultation']);
Route::post('/requestDefense/{date}', [App\Http\Controllers\CalendarController::class, 'requestDefense']);

// Requests
Route::get('/requests', [App\Http\Controllers\RequestController::class, 'index'])->name('requests');
Route::post('/approveRequests', [App\Http\Controllers\RequestController::class, 'approveRequest']);
Route::post('/rejectRequests', [App\Http\Controllers\RequestController::class, 'rejectRequest']);

// Notifications BackEnd
Route::get('/getNotifications', [App\Http\Controllers\NotificationController::class, 'getNotifications']);

// Notification FrontEnd
Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'view']);

// Request Groupings
Route::get('/request-groupings', [App\Http\Controllers\RequestGroupingsController::class, 'index']);
Route::get('/request-edit-groupings/{option}', [App\Http\Controllers\RequestGroupingsController::class, 'edit']);
Route::post('/request_my_groupings', [App\Http\Controllers\RequestGroupingsController::class, 'request_my_groupings']);
Route::post('/request_my_groupings_add', [App\Http\Controllers\RequestGroupingsController::class, 'request_my_groupings_add']);
Route::post('/request_my_groupings_kick', [App\Http\Controllers\RequestGroupingsController::class, 'request_my_groupings_kick']);
Route::post('/request_my_groupings_disband', [App\Http\Controllers\RequestGroupingsController::class, 'request_my_groupings_disband']);
Route::get('/request_list', [App\Http\Controllers\RequestGroupingsController::class, 'request_list']);
Route::get('/request_edit_list', [App\Http\Controllers\RequestGroupingsController::class, 'request_edit_list']);
Route::get('/get_request_info/{encrypted_id}', [App\Http\Controllers\RequestGroupingsController::class, 'get_request_info']);
Route::get('/get_request_edit_info/{encrypted_id}', [App\Http\Controllers\RequestGroupingsController::class, 'get_request_edit_info']);
Route::get('/get_request_edit_info/{encrypted_id}', [App\Http\Controllers\RequestGroupingsController::class, 'get_request_edit_info']);
Route::put('/reject_request_info/{encrypted_id}', [App\Http\Controllers\RequestGroupingsController::class, 'reject_request_info']);
Route::put('/edit_reject_request_info/{encrypted_id}', [App\Http\Controllers\RequestGroupingsController::class, 'edit_reject_request_info']);
Route::put('/approve_request_info/{encrypted_id}', [App\Http\Controllers\RequestGroupingsController::class, 'approve_request_info']);
Route::put('/edit_approve_request_info/{encrypted_id}', [App\Http\Controllers\RequestGroupingsController::class, 'edit_approve_request_info']);
Route::get('/getAdvisersAndPanel/{exception}', [App\Http\Controllers\RequestGroupingsController::class, 'getAdvisersAndPanel']);

// Schedule Checker
Route::get('/getScheduler', [App\Http\Controllers\checkSchedulerController::class, 'index']);

// Testing Public Access
Route::get('/testing', function(){
    return view("pages.public_access.index2");
});

// Grading Form
Route::get('/grading_form/{group_reference}', [App\Http\Controllers\GradingFormController::class, 'index']);
Route::post('/saveEvaluations', [App\Http\Controllers\GradingFormController::class, 'save']);
Route::post('/saveResponse', [App\Http\Controllers\GradingFormController::class, 'saveResponse']);
Route::post('/saveResponseSummary', [App\Http\Controllers\GradingFormController::class, 'saveResponseSummary']);
Route::post('/finalizedScores', [App\Http\Controllers\GradingFormController::class, 'finalizedScores']);
Route::get('/view-grading-form/{reference}', [App\Http\Controllers\GradingFormController::class, 'view']);

// Comments
Route::put('/updateComments/{type}/{id}', [App\Http\Controllers\StudentPageControllers\CapstoneController::class, 'updateComments']);

