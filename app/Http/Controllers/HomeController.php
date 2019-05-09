<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB; 
use View;

class HomeController extends Controller
{
    public function recomendador_user_user()
    {
      $results = DB::table('ratings')->distinct()->get(['user_id']);
      return view('recomendadorUU', ['results' => $results]);
    }

    public function recomendador_user_user_result(Request $request)
    {
      $results = DB::table('ratings')->distinct()->get(['user_id']);
      return view('recomendadorUU', ['results' => $results]);
    }

     public function calcular_correlacion(Request $request)
    {     

          $user_id = request()->get('user_id');
          $umbral = request()->get('umbral');
          $item = request()->get('item');

          $results = DB::select(DB::raw('select * from ratings e1, ratings e2 
          WHERE e1.user_id = '.$user_id.' 
          and e1.movie_id = e2.movie_id 
          and e1.user_id != e2.user_id'));

/*
    select * from ratings e1, ratings e2 
    WHERE e1.user_id = 2 
    and e1.movie_id = e2.movie_id 
    and e1.user_id != e2.user_id 
    and 5 < (SELECT COUNT(e3.user_id) FROM ratings e3)

    $results = DB::table('ratings')->distinct()->get(['user_id']);
*/

    /*
    domingo 29

    calcular prediccion con todos y quedarte con el vecindario que eligas o con el umbral que eligas
     APlicar formula para calcular la prediccion

    */
          

          dd($results);


          $x = array(5,3,6,7,4,2,9,5);
          $y = array(4,3,4,8,3,2,10,5);

          if(count($x)!==count($y)){return -1;}
          $x=array_values($x);
          $y=array_values($y);
          $xs=array_sum($x)/count($x);
          $ys=array_sum($y)/count($y);
          $a=0;$bx=0;$by=0;
          for($i=0;$i<count($x);$i++){
              $xr=$x[$i]-$xs;
              $yr=$y[$i]-$ys;
              $a+=$xr*$yr;
              $bx+=pow($xr,2);
              $by+=pow($yr,2);
          }   
          $b = sqrt($bx*$by);
          if($b==0) return 0;
          $results = $a/$b;


          /*dd($results);*/
      return redirect('/recomendadorUresult', ['results' => $resultss]);
    }


}
