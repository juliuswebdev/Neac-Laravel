<?php

use Illuminate\Database\Seeder;


class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::create(['category_id'=>'11','name'=>'USA - NCLEX® Application']);
        Service::create(['category_id'=>'12','name'=>'USA - USRN® Application']);
        Service::create(['category_id'=>'13','name'=>'Canada - NCLEX® Application']);
        Service::create(['category_id'=>'14','name'=>'Ireland - NMBI® Application']);
        Service::create(['category_id'=>'15','name'=>'United Kingdom - NMC UK® Application']);
        Service::create(['category_id'=>'16','name'=>'UAE AbuDhabi - HAAD® Application']);
        Service::create(['category_id'=>'17','name'=>'UAE Dubai - DHA® Application']);
        Service::create(['category_id'=>'18','name'=>'Saudi - SCHS® Application']);
        Service::create(['category_id'=>'19','name'=>'Oman - OMH® Application']);
        Service::create(['category_id'=>'20','name'=>'Qatar - QCHP® Application']);
        Service::create(['category_id'=>'21','name'=>'UAE - MOH® Application']);
        Service::create(['category_id'=>'22','name'=>'UAE Dubai - DHCA® Application']);
        Service::create(['category_id'=>'23','name'=>'USA - Medcoder Application']);
        Service::create(['category_id'=>'24','name'=>'Online Review - Practice Tests']);
        Service::create(['category_id'=>'25','name'=>'Other Services']);

        // Service::create(['type'=>'ANY']);
        // Service::create(['type'=>'ANY']);
        // Service::create(['type'=>'ANY']);
        // Service::create(['type'=>'ANY']);
        // Service::create(['type'=>'ANY']);
        // Service::create(['type'=>'ANY']);
        // Service::create(['type'=>'ANY']);
        // Service::create(['type'=>'ANY']);
        // Service::create(['type'=>'ANY']);
        // Service::create(['type'=>'ANY']);
        // Service::create(['type'=>'ANY']);
        // Service::create(['type'=>'ANY']);
        // Service::create(['type'=>'ANY']);
        // Service::create(['type'=>'ANY']);
        // Service::create(['type'=>'ANY']);
        
       
    }
}
