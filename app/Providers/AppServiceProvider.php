<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;
use Carbon\Carbon;
use App\Helpers\MemberHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Validator::extend('vp_email', function($attribute, $value, $parameters) {
            if (filter_var($value, FILTER_VALIDATE_EMAIL) || $value == VP_EMAIL_DEFAULT)
            {
                return true;
            }
        
            return true;
        });

        //
        Validator::extend('vp_telephone', function($attribute, $value, $parameters)
        {
            if ("regex:/0(?:\d\-\d{4}|\d{2}\-\d{3}|\d{3}\-\d{2}|\d{4}\-\d{1})\-\d{4}$/") {
                return true;
            }
            
            return false;
        });

        //
        Validator::extend('vp_date', function($attribute, $value, $parameters)
        {
            // Parse current value to time
            $curDate = Carbon::createFromFormat(VP_TIME_FORMAT, $value);
            
            // Get min date
            $minDate = Carbon::createFromFormat(VP_TIME_FORMAT, VP_DATE_MIN);
            
            // Get max date
            $maxDate = Carbon::createFromFormat(VP_TIME_FORMAT, MemberHelper::getMaxDate());

            if ($minDate < $curDate && $curDate < $maxDate)
            {
                return true;
            }
            
            return false;
        });

        // //
        // Validator::extend('boss_with_employee', function($attribute, $value, $parameters) 
        // {
        //     return ( Validator->getValue($parameters[0]) != 'employee') ? false : true;
        // });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
