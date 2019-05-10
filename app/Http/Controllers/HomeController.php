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
          $vecin = request()->get('vecin');

/*
          $results = DB::select(DB::raw('select * from ratings e1, ratings e2 
          WHERE e1.user_id = '.$user_id.' 
          and e1.movie_id = e2.movie_id 
          and e1.user_id != e2.user_id'));
*/

          $results = DB::select(DB::raw('select e1.user_id as userid1, e1.movie_id as movieid1, e1.ratings as ratings1,
          e2.user_id as userid2, e2.movie_id as movieid2, e2.ratings as ratings2
          from ratings e1, ratings e2 
          WHERE e1.user_id = '.$user_id.'
          and e1.movie_id = e2.movie_id 
          and e1.user_id != e2.user_id'));

          $id = $results[0]->userid2;
          $rating1 = array();
          $rating2 = array();
          $sim = array();

          $var = [$results[0]->userid1 => [1,2,3,4,5]];

          foreach ($results as $key => $data) {
            if ($id == $data->userid2) {
              array_push($rating1, $data->ratings1);
              array_push($rating2, $data->ratings2);

              /*print_r($rating1);*/

            }else{
              $x = $rating1;
              $y = $rating2;

              $res = $this->pearson($x, $y, $id, $sim);

          if (is_null($vecin)) {

            if ($umbral <= $res) {
              array_push($sim,$id .'->'. $res);
            }

          }elseif (is_null($umbral)) {

            /*sort($sim);*/
            array_push($sim, $id .'->'. $res);


          } else {
            echo "pelicano de marruecos";
            
          }


              unset($rating1);
              unset($rating2);
              $rating1 = array();
              $rating2 = array();

            $id = $data->userid2;
          }
        }


          

          print_r($sim);
      return view('recomendadorResult', ['results' => $results]);
    }

    public function pearson($x, $y, $id, $sim){
      $resultss = 0;
      if(count($x)!==count($y)){return -1;}
        $x=array_values($x);
        $y=array_values($y);

        if (count($x) != 0 || count($y) != 0) {
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
          if($b==0){
            $resultss = 0;
          }else{
            $resultss = $a/$b;
          }
        }
        return $resultss;
      }


}