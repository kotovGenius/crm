<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    private function dateValidator($date){
        $date = explode(" ",$date)[0];
        return date("d.m.Y",strtotime($date));
    }


    public function getCreatedAtAttribute($value){
        return $this->dateValidator($value);
    }
    public function getUpdatedAtAttribute($value){
        return $this->dateValidator($value);
    }
    public function getEntryDateAttribute($value){
        return $this->dateValidator($value);
    }

    public function subordinates(){
        return $this->hasMany(EmployeesManagers::class,'manager_id');
    }


    public function tempImage(){
        return $this->hasOne(TempImage::class);
    }

    use HasFactory;
}
