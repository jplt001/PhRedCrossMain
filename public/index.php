<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__.'/../bootstrap/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/
function pr($data = null){
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}
function pre($data = null){	
	pr($data);
	exit();
}

function acl_roles($user_type ,$module, $action = '', $sub = ''){
	
	$users_type = [
		0=>[
			'serology' => ['create', 'view', 'edit', 'delete'],
			'blood_referral'=> ['create', 'view', 'edit', 'delete'],
			'blood_register'=> ['create', 'view', 'edit', 'delete'],
			'forecasting'=> ['create', 'view', 'edit', 'delete'],
			'bloodbank'=> ['create', 'view', 'edit', 'delete'],
			'reports'=> '',
			'forecast'=> ['create', 'view', 'edit', 'delete'],
			
			'users'=>['create', 'view', 'edit', 'delete'],
			'branch'=>['create', 'view', 'edit', 'delete'],
			'hospitals'=>['create', 'view', 'edit', 'delete'],
			'patient'=>['create', 'view', 'edit', 'delete'],
			'organization'=>['create', 'view', 'edit', 'delete'],
			'report_bloodbank' => [],
			'report_bloodreferral'=>[],
			'report_reservation'=>[],
			'report_serology'=>[],
			
		],
		1=>[
			'serology' => ['create', 'view', 'edit', 'delete'],
			'blood_referral'=> ['create', 'view', 'edit', 'delete'],
			'blood_register'=> ['create', 'view', 'edit', 'delete'],
			'forecasting'=> ['create', 'view', 'edit', 'delete'],
			'bloodbank'=> ['create', 'view', 'edit', 'delete'],
			'reports'=> ['create', 'view', 'edit', 'delete'],
			'forecast'=> ['create', 'view', 'edit', 'delete'],
			'users'=>['create', 'view', 'edit', 'delete'],
			'branch'=>['create', 'view', 'edit', 'delete'],
			'hospitals'=>['create', 'view', 'edit', 'delete'],
			'patient'=>['create', 'view', 'edit', 'delete'],
			'organization'=>['create', 'view', 'edit', 'delete'],
			'report_bloodbank' => [],
			'report_bloodreferral'=>[],
			'report_reservation'=>[],
			'report_serology'=>[],
		],
		2=>[
			'serology' => ['create', 'view', 'edit', 'delete'],
			// 'blood_referral'=> ['create', 'view', 'edit', 'delete'],
			'blood_register'=> ['view'],
			// 'forecasting'=> ['create', 'view', 'edit', 'delete'],
			// 'blood_bank'=> ['create', 'view', 'edit', 'delete'],
			'report_bloodbank' => [],
			'report_bloodreferral'=>[],
			'report_reservation'=>[],
			'report_serology'=>[],
			// 'users'=>['create', 'view', 'edit', 'delete'],
			// 'branch'=>['create', 'view', 'edit', 'delete'],
			// 'hospitals'=>['create', 'view', 'edit', 'delete'],
			// 'patient'=>['create', 'view', 'edit', 'delete'],
			'organization'=>['create', 'view', 'edit', 'delete'],
			
		],
		3=>[
			'serology' => ['view'],
			// 'blood_referral'=> ['view', 'edit', 'delete'],
			'blood_register'=> ['view'],
			// 'forecasting'=> ['create', 'view', 'edit', 'delete'],
			'bloodbank'=> ['create', 'view', 'edit', 'delete'],
			'report_bloodbank' => [],
			'report_bloodreferral'=>[],
			'report_reservation'=>[],
			'report_serology'=>[],
			
			// 'users'=>['create', 'view', 'edit', 'delete'],
			// 'branch'=>['create', 'view', 'edit', 'delete'],
			'hospitals'=>['create', 'view', 'edit', 'delete'],
			'patient'=>['create', 'view', 'edit', 'delete'],
			// 'organization'=>['create', 'view', 'edit', 'delete'],
			
		]
	];

	$res = $users_type[$user_type];
	
	if(isset($res[$module])){
		
		if( !empty($action)){			
			if( in_array( $action, $res[$module] ) ){				
				return true;
			}else{
				return false;
			}
		}else{
			return true;
		}
	}else{
		return false;
	}
	
}

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
