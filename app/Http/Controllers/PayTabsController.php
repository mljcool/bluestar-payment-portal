<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

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


    public function createMerchantPagePostStaticData(Request $request)
    {
        // $headers = $this->input->request_headers();
        // $this->load->model('payments_m');
        // $values = $this->post();
        $values = $request->json();
        try {
            $data = array(
              "profile_id"=>         52842,
              "tran_type"=>          "sale",
              "tran_class"=>         "ecom",
              "cart_description"=>   $values->get('title'),
              "cart_id"=>            $values->get('id')."",
              "cart_currency"=>      "SAR",
              "cart_amount"=>        $values->get('price'),
              "return"=>             base_url('payments/new_payment?payment_id='.$values->get('price')),
              "customer_details"=> array(
                  "name"=> $values->get('firstName')." ".$values->get('lastName'),
                  "email"=> "receipts@aph.med.sa",
                  "street1"=> $values->get('address'),
                  "city"=> $values->get('city'),
                  "country"=> "AE",
                  "ip"=> "94.204.129.89"
              ),
               "shipping_details"=> array(
                  "name"=> $values->get('firstName')." ".$values->get('lastName'),
                  "email"=> "receipts@aph.med.sa",
                  "street1"=> $values->get('address'),
                  "city"=> $values->get('city'),
                  "country"=> "AE",
                  "ip"=> "94.204.129.89"
               )
               );
    
            $fields_string = json_encode($data);
            $headers = array(
              'Content-Type:application/json',
              'Authorization:SZJNRNDJZK-J2BZT6ZKTH-BJZGMKHRJW',
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
            // $data["payment_url"] = $data["redirect_url"];
    
            // $this->payments_m->save(
            //     array(
            //     "payment_id"=>$data["tran_ref"]
            //   ),
            //     $payment["id"]
            // );
    
            // $this->response($data, $httpcode);
            return response()
            ->json(['data'=> $data, 'httpcode'=> $httpcode]);
        } catch (Exception $e) {
            // $this->response(array("message"=>"دخول غير مرخص"), 401);
            return response()
            ->json(["message"=>"دخول غير مرخص"]);
        }
    }
}
