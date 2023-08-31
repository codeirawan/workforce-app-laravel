<?php

Auth::routes(['verify' => true, 'register' => false]);

Route::middleware(['auth', 'verified'])->group(
    function () {
        Route::redirect('/dashboard', url('/'));
        Route::redirect('/password/confirm', url('/'));
        Route::get('/', 'DashboardController@index')->name('dashboard');

        Route::namespace('Forecast')->group(function () {
            Route::middleware('permission:view-forecast')->group(function () {
                Route::resource('raw-data', 'RawDataController');
                Route::post('/raw-data/data', 'RawDataController@data')->name('raw-data.data');
                Route::post('/raw-data/bulk', 'RawDataController@bulk')->name('raw-data.bulk');

                Route::get('/history/daily', 'HistoryDataController@daily')->name('history.daily');
                Route::post('/history/daily/data', 'HistoryDataController@dailyData')->name('history.daily.data');

                Route::get('/history/weekly', 'HistoryDataController@weekly')->name('history.weekly');
                Route::post('/history/weekly/data', 'HistoryDataController@weeklyData')->name('history.weekly.data');

                Route::resource('forecast', 'ForecastController');
                Route::post('/forecast/params', 'ForecastController@params')->name('forecast.params');
                Route::post('/forecast/history/show/{id}', 'ForecastController@showHistory')->name('forecast.history.show');
                Route::post('/forecast/history/data/{id}', 'ForecastController@dataHistory')->name('forecast.history.data');
                Route::post('/forecast/history/store', 'ForecastController@storeHistory')->name('forecast.history.store');
                Route::post('/forecast/history/average/{id}', 'ForecastController@averageHistory')->name('forecast.history.average');
                Route::delete('/forecast/history/destroy/{id}', 'ForecastController@destroyHistory')->name('forecast.history.destroy');
                Route::post('/forecast/adjust/data/{id}', 'ForecastController@dataAdjust')->name('forecast.adjust.data');
                Route::post('/forecast/adjust/update/{id}', 'ForecastController@updateAdjust')->name('forecast.adjust.update');
                Route::post('/forecast/{day}/{id}', 'ForecastController@fteReqDay')->name('forecast.mon');
                Route::post('/forecast/{day}/{id}', 'ForecastController@fteReqDay')->name('forecast.tue');
                Route::post('/forecast/{day}/{id}', 'ForecastController@fteReqDay')->name('forecast.wed');
                Route::post('/forecast/{day}/{id}', 'ForecastController@fteReqDay')->name('forecast.thu');
                Route::post('/forecast/{day}/{id}', 'ForecastController@fteReqDay')->name('forecast.fri');
                Route::post('/forecast/{day}/{id}', 'ForecastController@fteReqDay')->name('forecast.sat');
                Route::post('/forecast/{day}/{id}', 'ForecastController@fteReqDay')->name('forecast.sun');
            });
        });

        Route::namespace('Schedule')->group(function () {
            Route::middleware('permission:view-schedule')->group(function () {
                Route::resource('schedule', 'ScheduleController');
                Route::post('/schedule/data', 'ScheduleController@data')->name('schedule.data');
                Route::post('/schedule/generate/{id}', 'ScheduleController@generate')->name('schedule.generate');
                Route::post('/schedule/generate/{id}', 'ScheduleController@generate')->name('schedule.generate');
                Route::post('/schedule/publish/{id}', 'ScheduleController@publish')->name('schedule.publish');
                Route::post('/schedule/unpublish/{id}', 'ScheduleController@unpublish')->name('schedule.unpublish');
                Route::post('/schedule/swap/{id}', 'ScheduleController@swap')->name('schedule.swap');

            });
        });

        Route::namespace('Leave')->group(function () {
            Route::middleware('permission:view-leave')->group(function () {
                Route::resource('paid-leave', 'PaidLeaveController');
                Route::post('/paid-leave/data', 'PaidLeaveController@data')->name('paid-leave.data');
                Route::post('/paid-leave/{id}/submit/{type}', 'PaidLeaveController@submit')->name('paid-leave.submit');
                Route::post('/paid-leave/{id}/process/{type}', 'PaidLeaveController@process')->name('paid-leave.process');
                Route::post('/paid-leave/{id}/approve/{type}', 'PaidLeaveController@approve')->name('paid-leave.approve');
                Route::post('/paid-leave/{id}/cancel', 'PaidLeaveController@cancel')->name('paid-leave.cancel');

                Route::resource('unpaid-leave', 'UnpaidLeaveController');
                Route::post('/unpaid-leave/data', 'UnpaidLeaveController@data')->name('unpaid-leave.data');
                Route::post('/unpaid-leave/{id}/submit/{type}', 'UnpaidLeaveController@submit')->name('unpaid-leave.submit');
                Route::post('/unpaid-leave/{id}/process/{type}', 'UnpaidLeaveController@process')->name('unpaid-leave.process');
                Route::post('/unpaid-leave/{id}/approve/{type}', 'UnpaidLeaveController@approve')->name('unpaid-leave.approve');
                Route::post('/unpaid-leave/{id}/cancel', 'UnpaidLeaveController@cancel')->name('unpaid-leave.cancel');

            });
        });

        Route::namespace('User')->group(
            function () {
                Route::prefix('/password')->as('password.')->group(
                    function () {
                        Route::get('/edit', 'ChangePasswordController@edit')->name('edit');
                        Route::post('/', 'ChangePasswordController@update')->name('store');
                    }
                );
                Route::middleware('permission:view-role')->group(
                    function () {
                        Route::resource('role', 'RoleController')->except(['destroy']);
                        Route::post('/role/data', 'RoleController@data')->name('role.data');
                    }
                );
                Route::middleware('permission:view-user')->group(
                    function () {
                        Route::resource('user', 'UserController');
                        Route::post('/user/data', 'UserController@data')->name('user.data');
                        Route::post('/user/bulk', 'UserController@bulk')->name('user.bulk');
                    }
                );
            }
        );

        Route::prefix('/master')->as('master.')->namespace('Master')->group(
            function () {
                Route::middleware('permission:view-master')->group(
                    function () {
                        Route::resource('project', 'ProjectController')->except(['show']);
                        Route::post('/project/data', 'ProjectController@data')->name('project.data');

                        Route::resource('skill', 'SkillController')->except(['show']);
                        Route::post('/skill/data', 'SkillController@data')->name('skill.data');

                        Route::resource('leave-type', 'LeaveTypeController')->except(['show']);
                        Route::post('/leave-type/data', 'LeaveTypeController@data')->name('leave-type.data');

                        Route::resource('shift', 'ShiftController')->except(['show']);
                        Route::post('/shift/data', 'ShiftController@data')->name('shift.data');

                        Route::resource('national-holiday', 'NationalHolidayController')->except(['show']);
                        Route::post('/national-holiday/data', 'NationalHolidayController@data')->name('national-holiday.data');

                        Route::resource('activity', 'ActivityController')->except(['show']);
                        Route::post('/activity/data', 'ActivityController@data')->name('activity.data');
                    }
                );

            }
        );
    }
);