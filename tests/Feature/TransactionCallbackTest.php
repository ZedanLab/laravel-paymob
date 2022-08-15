<?php

use function Pest\Laravel\get;
use function Pest\Laravel\post;

use ZedanLab\Paymob\Models\PaymobTransaction;

it('can handle the transaction processed callback', function () {
    $data = json_decode(
        stripslashes('{"transaction_processed_callback_responses":null,"obj":{"id":52195232,"pending":false,"amount_cents":3300000,"success":false,"is_auth":false,"is_capture":false,"is_standalone_payment":true,"is_voided":false,"is_refunded":false,"is_3d_secure":true,"integration_id":2425791,"profile_id":245593,"has_parent_transaction":false,"order":{"id":62620863,"created_at":"2022-08-15T14:10:04.121076","delivery_needed":false,"merchant":{"id":245593,"created_at":"2022-07-24T12:07:49.478575","phones":["01272496660"],"company_emails":["ceo@suiiz.com"],"company_name":"Suiiz investment","state":null,"country":"EGY","city":"cairo","postal_code":null,"street":null},"collector":null,"amount_cents":3300000,"shipping_data":{"id":33787029,"first_name":"Terrell","last_name":"Cassin","street":"N\/A","building":"N\/A","floor":"N\/A","apartment":"N\/A","city":"N\/A","state":"N\/A","country":"N\/A","email":"hmaggio@example.org","phone_number":"(325) 464-8800","postal_code":"N\/A","extra_description":null,"shipping_method":"UNK","order_id":62620863,"order":62620863},"currency":"EGP","is_payment_locked":false,"is_return":false,"is_cancel":false,"is_returned":false,"is_canceled":false,"merchant_order_id":null,"wallet_notification":null,"paid_amount_cents":0,"notify_user_with_email":false,"items":[],"order_url":"https:\/\/accept.paymobsolutions.com\/standalone?ref=i_b2FVNTQxcUJvaSszNVB5bVpNUVVSZz09XytsQjdaVHdnM09CV2xkcFBtaEV5SWc9PQ==","commission_fees":0,"delivery_fees_cents":0,"delivery_vat_cents":0,"payment_method":"tbc","merchant_staff_tag":null,"api_source":"OTHER","data":{"payer":{"id":5,"type":"App\\Models\\User"},"payable":{"id":50,"type":"App\\Models\\Product"}}},"created_at":"2022-08-15T14:10:19.509275","transaction_processed_callback_responses":[],"currency":"EGP","source_data":{"pan":"2346","tenure":null,"type":"card","sub_type":"MasterCard"},"api_source":"IFRAME","terminal_id":null,"merchant_commission":0,"installment":null,"is_void":false,"is_refund":false,"data":{"card_type":"MASTERCARD","secure_hash":null,"amount":3300000,"order_info":"62620863","acq_response_code":null,"currency":"EGP","transaction_no":null,"merchant":"TEST770000","gateway_integration_pk":2425791,"migs_result":"FAILURE","captured_amount":0,"migs_order":{"totalCapturedAmount":0,"totalRefundedAmount":0,"creationTime":"2022-08-15T12:10:36.363Z","acceptPartialAmount":false,"amount":33000,"id":"62620863","status":"FAILED","totalAuthorizedAmount":0,"currency":"EGP"},"card_num":"512345xxxxxx2346","receipt_no":null,"authorised_amount":0,"batch_no":null,"created_at":"2022-08-15T12:10:36.721954","message":"BLOCKED","migs_transaction":{"type":"PAYMENT","acquirer":{"id":"CIB_S2I","merchantId":"770000"},"amount":33000,"source":"INTERNET","frequency":"SINGLE","id":"52195232","currency":"EGP"},"refunded_amount":0,"merchant_txn_ref":"52195232","txn_response_code":"BLOCKED","avs_result_code":null,"authorize_id":null,"avs_acq_response_code":null,"klass":"MigsPayment"},"is_hidden":false,"payment_key_claims":{"billing_data":{"floor":"N\/A","state":"N\/A","extra_description":"NA","first_name":"Terrell","email":"hmaggio@example.org","apartment":"N\/A","phone_number":"(325) 464-8800","postal_code":"N\/A","street":"N\/A","last_name":"Cassin","country":"N\/A","building":"N\/A","city":"N\/A"},"integration_id":2425791,"exp":1660569004,"user_id":438020,"lock_order_when_paid":false,"order_id":62620863,"amount_cents":3300000,"pmk_ip":"105.192.94.65","currency":"EGP"},"error_occured":false,"is_live":false,"other_endpoint_reference":null,"refunded_amount_cents":0,"source_id":-1,"is_captured":false,"captured_amount":0,"merchant_staff_tag":null,"updated_at":"2022-08-15T14:10:36.733596","owner":438020,"parent_transaction":null},"type":"TRANSACTION","hmac":"a569c44e872915c62eb287b43787aa5857a683f319e893064ea7bc4e910dd3ee420cd3289a769692efb050841798b6215f619d620d47060558e4e38de0f72e5a"}'),
        true
    );

    post(route('paymob.callbacks.transactionProcessed'), $data);

    expect(PaymobTransaction::wherePaymobId(52195232)->exists())->toBeTrue();
});

it('can handle the transaction response callback', function () {
    $data = json_decode(
        stripslashes('{"updated_at":"2022-08-15T14:10:36.733596","source_data_type":"card","merchant_commission":"0","has_parent_transaction":"false","data_message":"BLOCKED","owner":"438020","order":"62620863","is_capture":"false","is_auth":"false","source_data_sub_type":"MasterCard","is_voided":"false","is_refund":"false","is_3d_secure":"true","is_standalone_payment":"true","id":"52195232","is_refunded":"false","currency":"EGP","error_occured":"false","is_void":"false","refunded_amount_cents":"0","created_at":"2022-08-15T14:10:19.509275","captured_amount":"0","amount_cents":"3300000","source_data_pan":"2346","integration_id":"2425791","success":"true","profile_id":"245593","txn_response_code":"BLOCKED","pending":"false","hmac":"cbab2a48623cc9577ca1cd5daeefc436ed576d91dfc9a9399f9a952184959275d772a8d2f960348eded409ca4af7d757f808444dbc17c74efb8cd71d17b5e353"}'),
        true
    );

    $response = get(route('paymob.callbacks.transactionResponse', $data));

    $response->assertRedirect(
        str($to = config('paymob.redirects.success'))->startsWith(['https://', 'http://'])
        ? $to
        : route($to)
    );
});
