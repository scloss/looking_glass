<?php

namespace App\Http\Controllers;
use File;
use DB;
use Input;
use DateTime;
use Zipper;
use Request;
use App\InfoTable;
session_start();

class CreateController extends Controller
{
    public function data_input_view(){
    	return view('Main.data_input');
    }
    public function data_input(){
    	$name = Request::get('name');
    	$info_table = new InfoTable();

        $info_table->name = $name;
        $info_table->row_created_time = date("Y-m-d H:i:s");

        $info_table->save();
    }
}
