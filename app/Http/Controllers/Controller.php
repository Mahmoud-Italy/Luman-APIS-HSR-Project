<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    //

    public function paginate($data)
    {
        $nextPageUrl = $data->nextPageUrl();
        $prevPageUrl = $data->previousPageUrl();
        $lastPage    = $data->lastPage();
        $currentPage = $data->currentPage();
        $perPage     = $data->perPage();
        $total       = $data->total();
        $pagination  = ['total'        => $total, 
                       'per_page'      => $perPage, 
                       'current_page'  => $currentPage, 
                       'last_page'     => $lastPage, 
                       'next_page_url' => $nextPageUrl, 
                       'prev_page_url' => $prevPageUrl];
        return $pagination;
    }
}
