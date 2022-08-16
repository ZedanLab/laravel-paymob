<?php

namespace ZedanLab\Paymob\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ZedanLab\Paymob\Enums\PaymobTransactionStatus;
use ZedanLab\Paymob\Services\PaymobProcessed;
use ZedanLab\Paymob\Services\PaymobResponse;

class PaymobCallbackController extends Controller
{
    /**
     * Handle transaction processed callback.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function transactionProcessed(Request $request)
    {
        $response = new PaymobProcessed($request->all());

        if (! $response->hasValidHmac()) {
            return $this->redirectTo(false);
        }

        $response->save();

        return $this->redirectTo(true, $response);
    }

    /**
     * Handle transaction response callback.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function transactionResponse(Request $request)
    {
        $response = new PaymobResponse($request->all());

        if (! $response->hasValidHmac() || $response->getStatus() === PaymobTransactionStatus::DECLINED) {
            return $this->redirectTo(false);
        }

        return $this->redirectTo(true, $response);
    }

    /**
     * Redirect to payment status.
     *
     * @param  \ZedanLab\Paymob\Services\PaymobResponse|\ZedanLab\Paymob\Services\PaymobProcessed $response
     * @param  bool                                                                               $status
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectTo(bool $status, PaymobResponse | PaymobProcessed $response = null)
    {
        $status = $status ? 'success' : 'failed';

        return str($to = config('paymob.redirects.' . $status))->startsWith(['https://', 'http://'])
        ? redirect()->to($to)
        : redirect()->route($to);
    }
}
