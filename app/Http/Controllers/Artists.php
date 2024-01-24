<?php

namespace App\Http\Controllers;

use App\Traits\Template;

class Artists extends Controller
{
    //
    use Template;
    public function index()
    {
        $ch = curl_init(env('API_LINK') . '/api/artists');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ' . env('API_URL_ID') . ' ' . env('API_URL_TOKEN')));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }

        curl_close($ch);

        return view('artists', $this->Template(), [
            'artists' => json_decode($response)->artists,
            'crumb1' => 'Artists',
        ]);
    }

    public function artist(string $slug)
    {
        $ch = curl_init(env('API_LINK') . '/api/artist/' . $slug);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ' . env('API_URL_ID') . ' ' . env('API_URL_TOKEN')));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }

        curl_close($ch);

        return view('artist', $this->Template(), [
            'datas' => json_decode($response)->artist,
            'items' => json_decode($response)->galleries,
            'crumb1' => 'Artists',
            'crumb2' => json_decode($response)->artist->name,
        ]);
    }
}
