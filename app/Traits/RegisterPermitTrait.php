<?php


namespace App\Traits;


use App\SystemVariable;
use Carbon\Carbon;

trait RegisterPermitTrait {
    public function permitCheck() {
        $isPermited = true;
        $var = json_decode(SystemVariable::where('name','registration')->first()->value)->permit;

        if (is_bool($var)){
            $isPermited = $var;
        }
        else {
            $isPermited = !Carbon::parse($var)->isPast();
        }
        return $isPermited;
    }
}