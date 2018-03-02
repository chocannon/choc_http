<?php

namespace App\Repositories\Test;

use Test\SampleModel;

class PushPlan {
    public static function nums()
    {
        return SampleModel::count();
    }
}