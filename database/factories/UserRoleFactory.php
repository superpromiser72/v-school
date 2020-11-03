<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserRole;
use Faker\Generator as Faker;

$factory->define(UserRole::class, function (Faker $faker) {
    return [
        
        'roleName'=>'admin',
        'permission'=>'[{"schoolName":"Admin","menuList":[{"resourceName":"使用者","read":true,"name":"adminuser"},{"resourceName":"Role","read":true,"name":"role"},{"resourceName":"Assign Role","read":true,"name":"assignRole"},{"resourceName":"School","read":true,"name":"School"},{"resourceName":"Grade","read":true,"name":"Grade"},{"resourceName":"Lesson","read":true,"name":"Lesson"}]},{"schoolName":"从化第四中学","menuList":[{"resourceName":"学校空间","read":true,"imgUrl":"/img/class/2.jpg","name":"/"},{"resourceName":"全体老师","read":true,"imgUrl":"/img/class/3.png","name":"allTeachers"},{"resourceName":"宿舍管理员","read":true,"imgUrl":"/img/class/4.jpg","name":"dormitory"},{"resourceName":"初二1班","read":true,"imgUrl":"/img/class/5.jpg","name":"middle21"},{"resourceName":"初二2班","read":true,"imgUrl":"/img/class/6.jpg","name":"middle22"},{"resourceName":"初二3班","read":true,"imgUrl":"/img/class/8.jpg","name":"middle23"},{"resourceName":"初二4班","read":true,"imgUrl":"/img/class/9.jpg","name":"middle24"},{"resourceName":"初二5班","read":true,"imgUrl":"/img/class/10.jpg","name":"middle25"},{"resourceName":"初二6班","read":true,"imgUrl":"/img/class/11.jpg","name":"middle26"},{"resourceName":"初三1班","read":true,"imgUrl":"/img/class/12.jpg","name":"middle31"},{"resourceName":"初三2班","read":true,"imgUrl":"/img/class/13.jpg","name":"middle32"},{"resourceName":"初三3班","read":true,"imgUrl":"/img/class/14.jpg","name":"middle33"},{"resourceName":"初三4班","read":true,"imgUrl":"/img/class/15.jpg","name":"middle34"},{"resourceName":"初三5班","read":true,"imgUrl":"/img/class/16.jpg","name":"middle35"},{"resourceName":"初三6班","read":true,"imgUrl":"/img/class/18.jpg","name":"middle36"},{"resourceName":"高二1班","read":true,"imgUrl":"/img/class/19.jpg","name":"high21"},{"resourceName":"高二2班","read":true,"imgUrl":"/img/class/20.jpg","name":"high22"},{"resourceName":"高二3班","read":true,"imgUrl":"/img/class/22.jpg","name":"high23"},{"resourceName":"高二4班","read":true,"imgUrl":"/img/class/23.jpg","name":"high24"},{"resourceName":"高二5班","read":true,"imgUrl":"/img/class/24.jpg","name":"high25"},{"resourceName":"高二6班","read":true,"imgUrl":"/img/class/25.jpg","name":"high26"},{"resourceName":"初三1班","read":true,"imgUrl":"/img/class/26.jpg","name":"high31"},{"resourceName":"初三2班","read":true,"imgUrl":"/img/class/27.jpg","name":"high32"},{"resourceName":"初三3班","read":true,"imgUrl":"/img/class/28.jpg","name":"high33"},{"resourceName":"初三4班","read":true,"imgUrl":"/img/class/29.jpg","name":"high34"},{"resourceName":"初三5班","read":true,"imgUrl":"/img/class/30.png","name":"high35"},{"resourceName":"初三6班","read":true,"imgUrl":"/img/class/31.png","name":"high36"}]}]'
    ];
});
