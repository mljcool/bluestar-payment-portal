<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Payments;

class PayTabsController extends Controller
{
    //


    public function playGround(Request $request)
    {
        // $data = $request->input('sample');
        $data = $request->json();
        return response()
        ->json(['data'=> $data->get('text')]);
    }

    

    public function simpleCurl(Request $request)
    {
        $url = 'https://jsonplaceholder.typicode.com/posts';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $err = curl_error($ch);  //if you need
        curl_close($ch);
        return response()
        ->json($response);
    }

    /**
       *  Create Merchant
       * @OA\Post(path="/admin/bluestar-payment-portal/create-merchant",
       *  tags={"create-merchant"},
       *     operationId="createMerchant",
       *     summary="Add new payment merchant",
       *     description="",
        * @OA\RequestBody(
            *       required=true,
            *       @OA\MediaType(
            *           mediaType="application/json",
            *           @OA\Schema(
            *               type="object",
            *               @OA\Property(
            *                   property="price",
            *                   description="48.17",
            *                   type="string"
            *               ),
            *               @OA\Property(
            *                   property="firstName",
            *                   description="John",
            *                   type="string"
            *               ),
            *               @OA\Property(
            *                   property="lastName",
            *                   description="Doe",
            *                   type="string"
            *               ),
            *               @OA\Property(
            *                   property="email",
            *                   description="email",
            *                   type="string"
            *               ),
            *               @OA\Property(
            *                   property="address",
            *                   description="404, 11th st, void",
            *                   type="string"
            *               ),
            *               @OA\Property(
            *                   property="city",
            *                   description="Dubai",
            *                   type="string"
            *               ),
            *           )
            *       )
            *   ),
       *   @OA\Response(
       *      response=200,
       *      description="Set - payment_url",
       *      @OA\JsonContent(
       *        @OA\Property(
       *            property="title",
       *            type="string",
       *        ),
       *        @OA\Property(
       *            property="id",
       *            type="string",
       *        ),
       *        @OA\Property(
       *            property="price",
       *            type="string",
       *        ),
       *        @OA\Property(
       *            property="firstName",
       *            type="string",
       *        ),
       *        @OA\Property(
       *            property="lastName",
       *            type="string",
       *        ),
       *        @OA\Property(
       *            property="address",
       *            type="string",
       *        ),
       *        @OA\Property(
       *            property="city",
       *            type="string",
       *        ),
       *      )
       *   )
       * )
       */

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public function createMerchant(Request $request)
    {
        $prod_mode_id = 75195;
        $test_mode_id = 68317;

        $payment_id = $this->generateRandomString();
        $title = $payment_id."_merchant_service_id";
        $values = $request->json();
        try {
            $data = array(
              "profile_id"=>         $test_mode_id,
              "tran_type"=>          "sale",
              "tran_class"=>         "ecom",
              "cart_currency"=>      "SAR",
              "cart_description"=>   $title,
              "cart_id"=>            $payment_id."",
              "cart_amount"=>        $values->get('price'),
              "return"=>             url('payments/new_payment?payment_id='.$payment_id),
              "customer_details"=> array(
                  "name"=> $values->get('firstName')." ".$values->get('lastName'),
                  "email"=> $values->get('email'),
                  "street1"=> $values->get('address'),
                  "city"=> $values->get('city'),
                  "country"=> "AE",
                  "ip"=> "94.204.129.89"
              ),
               "shipping_details"=> array(
                  "name"=> $values->get('firstName')." ".$values->get('lastName'),
                  "email"=> $values->get('email'),
                  "street1"=> $values->get('address'),
                  "city"=> $values->get('city'),
                  "country"=> "AE",
                  "ip"=> "94.204.129.89"
               )
               );
            
            $test_api_keys = 'S6JNRNDJWG-JB9GZDRNHZ-DLRMHH9KTJ';
            $live_api_keys = 'SBJNRNDJHM-J2DZGMHZD6-TZDNHBGJTW';

            $fields_string = json_encode($data);
            $headers = array(
              'Content-Type:application/json',
              'Authorization:'.$test_api_keys,
            );
    
            $curl = curl_init('https://secure.paytabs.sa/payment/request');
          
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_URL, 'https://secure.paytabs.sa/payment/request');
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // Make it so the data coming back is put into a string
            curl_setopt($curl, CURLOPT_POSTFIELDS, $fields_string);  // Insert the data
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            // Send the request
            $result = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            // Free up the resources $curl is using
            curl_close($curl);
            $data = json_decode($result, true);
            $data["payment_url"] = $data["redirect_url"];

            $payment = Payments::create([
                'tran_ref' => $data["tran_ref"],
                'payment_id' => $payment_id,
                'cart_description' => $title,
                'user_id' =>  $values->get('email'), // since its a consultants service
                'return_url' =>  url('payments/new_payment?payment_id='.$payment_id), // since its a consultants service
                'redirect_url' => $data["payment_url"], // since its a consultants service
                'status' => 1,
            ]);
    
            return response()
            ->json(['data'=> $data, 'payment_url'=>$data["payment_url"] , 'httpcode'=> $httpcode]);
        } catch (Exception $e) {
            return response()
            ->json(["message"=>"دخول غير مرخص", "status"=> 401]);
        }
    }
}
