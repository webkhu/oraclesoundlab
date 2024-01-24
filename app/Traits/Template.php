<?php

namespace App\Traits;

trait Template
{
    function Template()
    {
        $ch = curl_init(env('API_LINK') . '/api/template');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ' . env('API_URL_ID') . ' ' . env('API_URL_TOKEN')));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }

        curl_close($ch);

        if ($response) {
            $data = json_decode($response);

            $setup = $data->setting;
            foreach ($setup as $set) {
                $template = $set->template;
                $home_page = $set->home_page;
                $main_page = $set->main_page;
            }

            $currentURL = url()->current();
            $currentURL = explode("/", $currentURL);

            if(@$currentURL[3]){
                $title = collect($data->pages)->firstWhere('name', $currentURL[3])->title;
            } else {
                $title = '';
            }
            
            $set_data = [
                'url' => env('APP_URL'),
                'title' => $title,
                'pages' => $data->pages,
                'subpages' => $data->subpages,
                'categories' => $data->categories,
                'template' => $template,
                'home_page' => $home_page,
                'main_page' => $main_page,
                'active' => isset($currentURL[3]) ? $currentURL[3] : 'index',
            ];
            return $set_data;
        } else {
            echo 'No API response from server.';
        }
    }
}
