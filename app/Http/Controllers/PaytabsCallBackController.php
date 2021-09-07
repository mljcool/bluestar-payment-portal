<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payments;
use App\Models\PaymentCallBack;
use App\Http\Controllers\KeySettings;

class PaytabsCallBackController extends Controller
{
    //

    /**
     * @OA\Post(
     *     path="/admin/bluestar-payment-portal/payments/new_payment?payment_id={payment_id}",
     *     tags={"new-payment"},
     *     operationId="createPayment",
     *     summary="Add new payment",
     *     description="",
     *     @OA\Parameter(
     *         name="payment_id",
     *         in="path",
     *         description="redirect URL after payment",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *           format="int64"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="JSON formatted",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */

    public function newPayment(Request $request)
    {
        $paytabs_config = (new KeySettings())->getPayTabsKeys();

        $get_payment_id = $request->payment_id;
       
        $payment = Payments::where('payment_id', $get_payment_id)->first();


        $appointmentResult = "";
        
        if (isset($get_payment_id)) {
            $data = array(
                'profile_id'=> $paytabs_config->profile_id,
                'tran_ref' => $payment->tran_ref,
                'tran_type' => 'sale',
              );

            $fields_string = json_encode($data);
              

            $headers = array(
                'Content-Type: application/json',
                'Authorization:'.$paytabs_config->api_key,
              );
        
            $curl = curl_init('https://secure.paytabs.sa/payment/query');
            
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_URL, 'https://secure.paytabs.sa/payment/query');
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
            curl_close($curl);
            $data = json_decode($result, true);
            

            if ($data["payment_result"]["response_status"] == "A") {
                $paymentData = array(
                  "result"=>$data["payment_result"]["response_message"],
                  "payment_reference"=>$data["trace"],
                  "response_code"=>0,
                  "amount"=> floatval($data["cart_amount"]),
                  "currency"=>$data["cart_currency"],
                  "transaction_id"=>$data["tran_ref"],
                  "card_brand"=>$data["payment_info"]["card_scheme"],
                  "card_first_six_digits"=>$data["payment_info"]["payment_description"],
                  "card_last_four_digits"=>$data["payment_info"]["payment_description"],
                );
            } else {
                $paymentData = array(
                  "result"=>$data["payment_result"]["response_message"],
                  "payment_reference"=>$data["trace"],
                  "response_code"=>$data["payment_result"]["response_code"],
                  "amount"=>$data["cart_amount"],
                  "currency"=>$data["cart_currency"],
                  "transaction_id"=>$data["tran_ref"]
                );
            }
        
            $paymentData["invoice_return_val"] = $appointmentResult;

            $final_format = array_merge($paymentData, [
              'payment_id' => $get_payment_id
            ]);
            $payment_callback = PaymentCallBack::create($final_format);
            
            if ($payment_callback) {
                return redirect('bluestar://payment?paymentId='.$get_payment_id);
            } else {
                return response()
              ->json(['data'=> [
                'error' => 500,
                'message' => 'something went wrong']]);
            }
            // return response()
            // ->json(['data'=> $data ]);
        }
    }
}
