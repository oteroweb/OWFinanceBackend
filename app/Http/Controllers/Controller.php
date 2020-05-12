<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected function get_string_between( $string, $start, $end ) {
        $string = ' ' . $string;
        $ini = strpos( $string, $start );
        if ( $ini == 0 ) return '';
        $ini += strlen( $start );
        $len = strpos( $string, $end, $ini ) - $ini;
        return substr( $string, $ini, $len );
    }
}
