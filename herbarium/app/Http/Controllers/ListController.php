<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ListController extends Controller
{
  public function show(){
    $characters = [
      'Daenerys Targaryen' => 'Emilia Clarke',
      'Jon Snow'           => 'Kit Harington',
      'Arya Stark'         => 'Maisie Williams',
      'Meilisandre'        => 'Carice van Houten'
    ];

    return view('welcome')->withCharacters($characters);
  }
}
