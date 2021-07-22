<?php

use Illuminate\Database\Seeder;
use App\Models\Permissions;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         $permission = [
        	[
        		'name' => 'Super Administrator',
        		'display_name' => 'Super Administrator',
        		'description' => 'Super Administrator'
        	],
        	[
        		'name' => 'Administrator',
        		'display_name' => 'Administrator',
        		'description' => 'Administrator'
        	],
        	[
        		'name' => 'Receptionist',
        		'display_name' => 'Receptionist',
        		'description' => 'Receptionist'
        	],
        	[
        		'name' => 'Medical Technician',
        		'display_name' => 'Medical Technician',
        		'description' => 'Medical Technician'
        	]
        ];

        foreach ($permission as $key => $value) {
        	Permissions::create($value);
        }
    }
}
