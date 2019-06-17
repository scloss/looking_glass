<?php

namespace App\Http\Middleware;

use Closure;

class ClassA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        if(isset($_SESSION['pass'])){
            return $next($request);
        }
        else{
            header('Location:../../login_plugin/login.php');
            exit();
            
        }     
    }
}
