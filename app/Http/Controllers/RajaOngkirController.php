<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RajaOngkirController extends Controller
{
    public function get_province(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('RAJA_ONGKIR_API')."/province",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: ".env('RAJA_ONGKIR_KEY')
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return response()->json([
                "message"=>"something wrong",
                "error"=>$err
            ],400);
        } else {
            $response=json_decode($response,true);
            $data_pengirim = $response['rajaongkir']['results'];
            return response()->json([
                'message'=>"success",
                'data'=>$data_pengirim
            ],200);
        }
    }
    public function get_city($id){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('RAJA_ONGKIR_API')."/city?&province=$id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: ".env('RAJA_ONGKIR_KEY')
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return response()->json([
                "message"=>"something wrong",
                "error"=>$err
            ],400);
        } else {
            $response=json_decode($response,true);
            $data_kota = $response['rajaongkir']['results'];
            return response()->json([
                "message"=>"success",
                "data"=>$data_kota
            ],200);
        }
    }
    public function get_courier(){
        return response()->json([
            'message'=>"success",
            'data'=>['jne','tiki','pos']
        ],200);
    }
    public function get_cost(Request $request){
    	$curl = curl_init();

		curl_setopt_array($curl, array(
            CURLOPT_URL => env('RAJA_ONGKIR_API')."/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=". $request->origin ."&destination=". $request->destination ."&weight=" . $request->weight ."&courier=" . $request->courier,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: ".env('RAJA_ONGKIR_KEY')
            ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
            return response()->json([
                "message"=>"something wrong",
                "error"=>$err
            ],400);
		} else {
		    $response=json_decode($response,true);
            $data_harga = $response['rajaongkir']['results'];
            return response()->json([
                "message"=>"success",
                "data"=>$data_harga
            ],200);
		}
    }
}
