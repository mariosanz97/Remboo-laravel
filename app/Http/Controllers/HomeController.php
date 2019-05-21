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
      return view('recomendadorResult');
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

          $pelisNoVistas =  DB::select(DB::raw('select idmovies as idm from movies where idmovies not in (SELECT movie_id FROM ratings WHERE user_id = '.$user_id.')'));

          $NoVistas = array();
          foreach ($pelisNoVistas as  $value) {
            array_push($NoVistas, $value->idm);
          }

          $id = $results[0]->userid2;
          $rating1 = array();
          $rating2 = array();

          $ids = array();
          $predicciones = array();

          foreach ($results as $key => $data) {
                if ($id == $data->userid2) {
                  array_push($rating1, $data->ratings1);
                  array_push($rating2, $data->ratings2);
                  $x = $rating1;
                  $y = $rating2;
                }else{
                  //pearson
                    $resultss = 0;
                    if(count($x)!==count($y)){return -1;}
                      $x=array_values($x);
                      $y=array_values($y);

                      if (count($x) != 0 || count($y) != 0) {
                        $media1=array_sum($x)/count($x);
                        $media2=array_sum($y)/count($y);
                        $sumatorioNum=0;$sumatorio1=0;$sumatorio2=0;
                        for($i=0;$i<count($x);$i++){
                            $xr=$x[$i]-$media1;
                            $yr=$y[$i]-$media2;
                            $sumatorioNum+=$xr*$yr;
                            $sumatorio1+=pow($xr,2);
                            $sumatorio2+=pow($yr,2);
                        } 
                        $dem = sqrt($sumatorio1*$sumatorio2);
                        if($dem==0){
                          $resultPearson = 0;
                        }else{
                          $resultPearson = $sumatorioNum/$dem;
                        }
                      }
                  //cierrre pearson


                  if (is_null($vecin)) {
                    if ($umbral <= $resultPearson) {
                      array_push($ids, $id );
                      array_push($predicciones, $resultPearson);
                      }
                    }

                  unset($rating1);
                  unset($rating2);
                  $rating1 = array();
                  $rating2 = array();

                  $id = $data->userid2;

                  array_push($rating1, $data->ratings1);
                  array_push($rating2, $data->ratings2);

              }
        }


          $sim = array_combine($ids, $predicciones);

          print_r($sim);



          for ($i=0; $i < $NoVistas; $i++) { 

            for ($i=0; $i < sizeof(array_keys($sim)); $i++) { 
              echo array_keys($sim)[$i];
              $userAux = array_keys($sim)[$i];
              $pelisVistasUserAux =  DB::select(DB::raw('SELECT movie_id FROM ratings WHERE user_id = '.$userAux.''));

              $clave = array_search($NoVistas[$i], $pelisVistasUserAux);
              echo $clave;
              echo "::::::::::::::::::";

              echo "\n";
            }

           }

 /*
          for ($i=0; $i < $NoVistas; $i++) {
             $peliVistaUserAux =  DB::select(DB::raw('select idmovies as idm from movies where idmovies not in (SELECT movie_id FROM ratings WHERE user_id = '.$user_id.')'));
            if ($sim[$i]) {
              # code...
            }
              
          }
*/


/*
          if (is_null($vecin)) {
              //prediccion
              for ($i=0; $i < $item; $i++) {
                //similitud * (punuacionPeli-mediaSusPuntuaciones)
                $num = $SimFilto[$i] * ($rating1[$i] - $media1[$i]);

                $resultao = $num / $dem;

                echo "resultao:  ";
                echo $resultao;
                echo "\n";
              }

          }elseif (is_null($umbral)) {

          }
          else {
          }
*/



      return view('recomendadorResult', ['results' => $results]);
    }

/*
    public function pearson($x, $y){

      $resultss = 0;
      if(count($x)!==count($y)){return -1;}
        $x=array_values($x);
        $y=array_values($y);

        if (count($x) != 0 || count($y) != 0) {
          $media1=array_sum($x)/count($x);
          $media2=array_sum($y)/count($y);
          $sumatorioNum=0;$sumatorio1=0;$sumatorio2=0;
          for($i=0;$i<count($x);$i++){
              $xr=$x[$i]-$media1;
              $yr=$y[$i]-$media2;
              $sumatorioNum+=$xr*$yr;
              $sumatorio1+=pow($xr,2);
              $sumatorio2+=pow($yr,2);
          } 
          $dem = sqrt($sumatorio1*$sumatorio2);
          if($dem==0){
            $resultss = 0;
          }else{
            $resultss = $sumatorioNum/$dem;
          }
        }
        return $resultss;
      }
*/

/*

          //numero items
          sort($sim);
          print_r($sim);
          $SimFilto = array();

          for ($i=0; $i < $item; $i++) {
            array_push($SimFilto, $sim[$i]);
            print_r($sim[$i]);
            echo "\n";

*/

}