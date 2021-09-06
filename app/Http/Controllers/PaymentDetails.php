<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payments;
use App\Models\PaymentCallBack;

class PaymentDetails extends Controller
{
    //

    /**
     * @OA\Get(
     *     path="/admin/bluestar-payment-portal/payments/details?payment_id={payment_id}",
     *     tags={"payment-details"},
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
    public function getPaymentDetails(Request $request)
    {
        $get_payment_id = $request->payment_id;
        $paymentDetails = Payments::where('payment_id', $get_payment_id)->first();
        $paymentCallback = PaymentCallBack::where('payment_id', $get_payment_id)->first();
        return response()
        ->json(['data'=> [
          'merchant_details' => $paymentDetails,
          'callback_details' => $paymentCallback]]);
    }
}
