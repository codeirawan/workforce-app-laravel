<?php

Auth::routes(['verify' => true]);
Route::middleware(['auth', 'verified'])->group(
    function () {
        Route::redirect('/dashboard', url('/'));
        Route::redirect('/password/confirm', url('/'));
        Route::get('/', 'DashboardController@index')->name('dashboard');

        Route::namespace ('Forecast')->group(function () {
            Route::middleware('permission:view-forecast')->group(function () {
                Route::resource('raw-data', 'RawDataController');
                Route::post('/raw-data/data', 'RawDataController@data')->name('raw-data.data');
                Route::post('/raw-data/bulk', 'RawDataController@bulk')->name('raw-data.bulk');

                Route::resource('forecast/daily', 'DailyForecastController');
                Route::post('/forecast/daily/data', 'DailyForecastController@data')->name('daily.data');

                Route::resource('forecast/weekly', 'WeeklyForecastController');
                Route::post('/forecast/weekly/data', 'WeeklyForecastController@data')->name('weekly.data');

                Route::resource('forecast/monthly', 'MonthlyForecastController');
                Route::post('/forecast/monthly/data', 'MonthlyForecastController@data')->name('monthly.data');

            });
        });

        Route::namespace ('User')->group(
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
                Route::middleware('permission:view-position')->group(
                    function () {
                        Route::resource('position', 'PositionController')->except(['show']);
                        Route::post('/position/data', 'PositionController@data')->name('position.data');
                    }
                );

                Route::middleware('permission:view-project')->group(
                    function () {
                        Route::resource('project', 'ProjectController')->except(['show']);
                        Route::post('/project/data', 'ProjectController@data')->name('project.data');
                    }
                );

                Route::middleware('permission:view-file-type')->group(function () {
                    Route::resource('file-type', 'FileTypeController')->except(['show']);
                    Route::post('/file-type/data', 'FileTypeController@data')->name('file-type.data');
                });

            }
        );
    }
);
