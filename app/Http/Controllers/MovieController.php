<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB; 
use View;
use DOMDocument;

class MovieController extends Controller
{
    public function webscrapmovie($id){
        $Qlinkid = DB::select(DB::raw('select tmdbId as linkid from links where movieId = '.$id.''));
        foreach ($Qlinkid as  $value) {
            $linkidd= $value->linkid;
        }

        $url = 'https://www.themoviedb.org/movie/'.$linkidd;
        $html = file_get_contents($url);
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        foreach ($doc->getElementsByTagName('a') as $tag) {
            if($tag->getAttribute('class')=='no_click progressive replace')
            {
                $photolink = $tag->getAttribute('href');
            }
        }
        foreach ($doc->getElementsByTagName('div') as $tag) {
            if($tag->getAttribute('class')=='overview')
            {
                $description = $tag->nodeValue;
            }
        }
        return array($photolink,$description);
    }

}