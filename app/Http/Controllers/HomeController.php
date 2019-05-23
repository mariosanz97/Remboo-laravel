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

          $Fpredic = array();
          $FnombrePeli = array();
          //$Fiduser = array();
          $Fidpel = array();

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

          $pelisNoVistas =  DB::select(DB::raw('select idmovies as idm from movies where idmovies not in (SELECT movie_id FROM ratings WHERE user_id = '.$user_id.')
              LIMIT 5'));

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

          //print_r($sim);

          //pelis vistas por el user aux, y no por user original // y su rating
          $pelisVistasUserAux = array();
          $pelisVistasUserAuxRating = array();


          for ($i=0; $i < sizeof($NoVistas); $i++) {
            //buscar a los users que hayan pasado el umbral y la hayan visto
            //recoger todos ls users que hayan visto esa peli,
            for ($j=0; $j < sizeof(array_keys($sim)); $j++) {
                  $userAuxid = array_keys($sim)[$j];
                  $QpelisVistasUserAux =  DB::select(DB::raw('SELECT ratings FROM ratings WHERE user_id = '.$userAuxid.' 
                  and movie_id = '.$NoVistas[$i].''));
                  if ($QpelisVistasUserAux!=null) {
                    array_push($pelisVistasUserAux, $userAuxid);

                    foreach ($QpelisVistasUserAux as $variable) {
                        array_push($pelisVistasUserAuxRating, $variable->ratings);
                      # code...
                    }

                  }


              }

              $simm = array_combine($pelisVistasUserAux, $pelisVistasUserAuxRating);
             //predccion
              //$den = array_sum ($simm);
              $den = 0;
              $num= 0;

              for ($p=0; $p < sizeof($simm); $p++) { 
                $den += $sim[array_keys($simm)[$p]];
              }


              for ($k=0; $k < sizeof($simm); $k++) { 
                 $med = $this->media(array_keys($simm)[$k]);
                 //similitud * (punuacionPeli-mediaSusPuntuaciones)ç 
                $num += $sim[array_keys($simm)[$k]] * ($simm[array_keys($simm)[$k]] - $med);
                  //array_push($Fiduser, array_keys($simm)[$k]);
              }

/*
                echo "DENOM y NUM";
                echo "\n";
                echo $den;
                echo "\n";
                echo $num;
                echo "\n";
*/

                $medMyuser = $this->media($user_id);
                if ($den!=0) {
                  //echo "ESTA PELI  ";
                  //echo $NoVistas[$i];
                  $resultao =  $medMyuser + ($num / $den);
                  //echo "  resultao:  ";
                  //echo $resultao;
                  array_push($Fpredic, $resultao);
                  array_push($Fidpel, $NoVistas[$i]);
                  $name = $this->nombrePeli($NoVistas[$i]);
                  array_push($FnombrePeli, $name);
                  
                }

                $num = 0;
                $den = 0;


                  //print_r($simm);

                  unset($simm);
                  $simm = array();
                  unset($pelisVistasUserAux);
                  $pelisVistasUserAux = array();
                  unset($pelisVistasUserAuxRating);
                  $pelisVistasUserAuxRating = array();

           }


/*
           for ($i=0; $i < sizeof($pelisVistasUserAux); $i++) { 
                //similitud * (punuacionPeli-mediaSusPuntuaciones)
                $num = $sim[$i] * ($rating1[$i] - $media1[$i]);
                $resultao = $num / $dem;

                echo "resultao:  ";
                echo $resultao;
                echo "\n";
           }
*/ 

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
/*
          print_r($Fpredic);
          echo "\n"; 
          print_r($Fiduser);
          echo "\n"; 
          print_r($Fidpel);
          echo "\n";
*/
    return view('recomendadorResult', ['Fpredic' => $Fpredic, 'Fidpel' => $Fidpel, 'FnombrePeli' => $FnombrePeli, 'item' => $item]);
    }



    public function media($userid){
        $Qmedia =  DB::select(DB::raw('SELECT AVG(ratings) as med FROM ratings where user_id = '.$userid.''));
        foreach ($Qmedia as $med) {
          $media = $med->med;
        }
        return $media;
    }

    public function nombrePeli($peliid){
        $Qmedia =  DB::select(DB::raw('SELECT title as nom FROM movies WHERE idMovies = '.$peliid.''));
        foreach ($Qmedia as $nom) {
          $name = $nom->nom;
        }
        return $name;
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



//funcion

/*
            for ($i=0; $i < sizeof(array_keys($sim)); $i++) { 
              //echo array_keys($sim)[$i];
              $userAuxid = array_keys($sim)[$i];
                  echo "\n";
                  echo "::::::::::::::::::::";
                  echo $userAuxid;
                  echo "::::::::::::::::::::";
                  echo "\n";
              $pelisVistasUserAux =  DB::select(DB::raw('SELECT movie_id FROM ratings WHERE user_id = '.$userAuxid.' 
              LIMIT 10'));

              foreach ($pelisVistasUserAux as  $value) {
                  echo "\n";
                  echo "-----";
                  echo $value->movie_id;
                  echo "-----";
                  echo "\n";

              }

              echo "\n";
            }
            */