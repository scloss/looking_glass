<?php

namespace App\Http\Controllers;

use File;
use DB;
use Input;
use DateTime;
use Zipper;
use Request;
session_start();

class AuthController extends Controller
{
    public function view_login(){
        //if(isset($_SESSION['pass'])){
            return view('Auth.login_view');
        //}
        //else{
            header('Location:../../login_plugin/login.php');
            exit();
            
        //}
		
	}

	public function authenticate(){

        $username = Request::get('username');
        $password = Request::get('password');        


		$auth_query = "SELECT * FROM login_plugin_db.login_table";
        $auth_lists = \DB::connection('mysql2')->select(\DB::raw($auth_query));
        if(count($auth_lists) > 0 )        
        {   
        	foreach ($auth_lists as $auth_list) {	
    			if($auth_list->user_id==$username && $auth_list->account_status!="blocked"){

    				if (password_verify($password, $auth_list->user_password)) 
        			{
        				$_SESSION['user_id'] = $auth_list->user_id;
        				$email = $auth_list->email;

        				$hr_query = "SELECT * FROM hr_tool_db.employee_table where email='$email' AND status='active'";
    					$hr_lists = \DB::connection('mysql3')->select(\DB::raw($hr_query));

                        //echo count($hr_lists);

    					foreach($hr_lists as $hr_list){

    						$_SESSION['user_name'] = $hr_list->name;
    						$_SESSION['designation'] = $hr_list->designation;
    						$_SESSION['department'] = $hr_list->department;
                            $_SESSION['email'] = $hr_list->email;
                            $_SESSION['phone'] = $hr_list->phone;

                            $hr_dept_id_query = "SELECT dept_row_id FROM hr_tool_db.department_table where dept_name='".$_SESSION['department']."'";
                            $hr_dept_id_lists = \DB::connection('mysql3')->select(\DB::raw($hr_dept_id_query));
                            $_SESSION['dept_id'] = $hr_dept_id_lists[0]->dept_row_id;
                            


                            $access_type_query = "SELECT type FROM phoenix_tt_db.access_table where user_id='".$_SESSION['user_id']."'";
                            $access_table_lists = \DB::select(\DB::raw($access_type_query));
                            if(count($access_table_lists) > 0){
                                $_SESSION['access_type'] = $access_table_lists[0]->type;
                            }
                            else{
                                $msg = 'Please contact OSS for phoenix access level';
                                return view('errors.error_phoenix',compact('msg'));
                                //return redirect('/');
                            }

                            
                            //$request->session()->put('session_user_id',$auth_list->user_id);

    						// print_r($_SESSION);

    						// exit();

    					}
                        if(!isset($_SESSION['user_name'])){
                            return redirect('DashboardTT');
                        }

        				return redirect('DashboardTT');
        			}
                    else{
                        return redirect('/');
                    }
    			}
        	}
        }
        

        

	}

	public function logout(){

		session_unset();
		return redirect('/');
	}

}
