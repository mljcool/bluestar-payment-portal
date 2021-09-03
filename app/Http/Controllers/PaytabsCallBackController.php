<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payments;

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
        // $this->load->model('payments_m');
        $get_payment_id = $request->payment_id;
       
        $payment = Payments::where('cart_id', $get_payment_id)->first();
        $appointmentResult = "";
        
        if (isset($get_payment_id)) {
            $data = array(
                'profile_id'=> 75195,
                'tran_ref' => $payment->tran_ref,
              );
            $fields_string = json_encode($data);
        
            $headers = array(
                'Content-Type: application/json',
                'Authorization: SZJNRNDJZK-J2BZT6ZKTH-BJZGMKHRJW',
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
                  "payment_reference"=>$data["payment_result"]["response_code"],
                  "response_code"=>100,
                  "amount"=>$data["cart_amount"],
                  "currency"=>$data["cart_currency"],
                  "transaction_id"=>$data["tran_ref"],
                  "card_brand"=>$data["payment_info"]["card_scheme"],
                  "card_first_six_digits"=>$data["payment_info"]["payment_description"],
                  "card_last_four_digits"=>$data["payment_info"]["payment_description"],
                );
            } else {
                $paymentData = array(
                  "result"=>$data["payment_result"]["response_message"],
                  "payment_reference"=>$data["payment_result"]["response_code"],
                  "response_code"=>400,
                  // "pt_invoice_id"=>$data["pt_invoice_id"],
                  "amount"=>$data["cart_amount"],
                  "currency"=>$data["cart_currency"],
                  "transaction_id"=>$data["tran_ref"]
                );
            }
        
            $paymentData["invoice_return_val"] = $appointmentResult;
            // $this->payments_m->save($paymentData, $get_payment_id["payment_id"]);
        
            // Redirect to the deep link
            
            return response()
            ->json(['data'=> $paymentData, 'redirect_link'=>$payment->return_url]);
            ;
        }
    }
}
