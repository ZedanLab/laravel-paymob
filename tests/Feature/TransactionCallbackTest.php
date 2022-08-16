<?php

use function Pest\Laravel\get;
use function Pest\Laravel\post;

use ZedanLab\Paymob\Models\PaymobTransaction;

it('can handle the transaction processed callback', function () {
    $data = json_decode(
        stripslashes('{"transaction_processed_callback_responses":null,"obj":{"id":52195232,"pending":false,"amount_cents":3300000,"success":false,"is_auth":false,"is_capture":false,"is_standalone_payment":true,"is_voided":false,"is_refunded":false,"is_3d_secure":true,"integration_id":2425791,"profile_id":245593,"has_parent_transaction":false,"order":{"id":62620863,"created_at":"2022-08-15T14:10:04.121076","delivery_needed":false,"merchant":{"id":245593,"created_at":"2022-07-24T12:07:49.478575","phones":["01272496660"],"company_emails":["ceo@suiiz.com"],"company_name":"Suiiz investment","state":null,"country":"EGY","city":"cairo","postal_code":null,"street":null},"collector":null,"amount_cents":3300000,"shipping_data":{"id":33787029,"first_name":"Terrell","last_name":"Cassin","street":"N\/A","building":"N\/A","floor":"N\/A","apartment":"N\/A","city":"N\/A","state":"N\/A","country":"N\/A","email":"hmaggio@example.org","phone_number":"(325) 464-8800","postal_code":"N\/A","extra_description":null,"shipping_method":"UNK","order_id":62620863,"order":62620863},"currency":"EGP","is_payment_locked":false,"is_return":false,"is_cancel":false,"is_returned":false,"is_canceled":false,"merchant_order_id":null,"wallet_notification":null,"paid_amount_cents":0,"notify_user_with_email":false,"items":[],"order_url":"https:\/\/accept.paymobsolutions.com\/standalone?ref=i_b2FVNTQxcUJvaSszNVB5bVpNUVVSZz09XytsQjdaVHdnM09CV2xkcFBtaEV5SWc9PQ==","commission_fees":0,"delivery_fees_cents":0,"delivery_vat_cents":0,"payment_method":"tbc","merchant_staff_tag":null,"api_source":"OTHER","data":{"payer":{"id":5,"type":"App\\Models\\User"},"payable":{"id":50,"type":"App\\Models\\Product"}}},"created_at":"2022-08-15T14:10:19.509275","transaction_processed_callback_responses":[],"currency":"EGP","source_data":{"pan":"2346","tenure":null,"type":"card","sub_type":"MasterCard"},"api_source":"IFRAME","terminal_id":null,"merchant_commission":0,"installment":null,"is_void":false,"is_refund":false,"data":{"card_type":"MASTERCARD","secure_hash":null,"amount":3300000,"order_info":"62620863","acq_response_code":null,"currency":"EGP","transaction_no":null,"merchant":"TEST770000","gateway_integration_pk":2425791,"migs_result":"FAILURE","captured_amount":0,"migs_order":{"totalCapturedAmount":0,"totalRefundedAmount":0,"creationTime":"2022-08-15T12:10:36.363Z","acceptPartialAmount":false,"amount":33000,"id":"62620863","status":"FAILED","totalAuthorizedAmount":0,"currency":"EGP"},"card_num":"512345xxxxxx2346","receipt_no":null,"authorised_amount":0,"batch_no":null,"created_at":"2022-08-15T12:10:36.721954","message":"BLOCKED","migs_transaction":{"type":"PAYMENT","acquirer":{"id":"CIB_S2I","merchantId":"770000"},"amount":33000,"source":"INTERNET","frequency":"SINGLE","id":"52195232","currency":"EGP"},"refunded_amount":0,"merchant_txn_ref":"52195232","txn_response_code":"BLOCKED","avs_result_code":null,"authorize_id":null,"avs_acq_response_code":null,"klass":"MigsPayment"},"is_hidden":false,"payment_key_claims":{"billing_data":{"floor":"N\/A","state":"N\/A","extra_description":"NA","first_name":"Terrell","email":"hmaggio@example.org","apartment":"N\/A","phone_number":"(325) 464-8800","postal_code":"N\/A","street":"N\/A","last_name":"Cassin","country":"N\/A","building":"N\/A","city":"N\/A"},"integration_id":2425791,"exp":1660569004,"user_id":438020,"lock_order_when_paid":false,"order_id":62620863,"amount_cents":3300000,"pmk_ip":"105.192.94.65","currency":"EGP"},"error_occured":false,"is_live":false,"other_endpoint_reference":null,"refunded_amount_cents":0,"source_id":-1,"is_captured":false,"captured_amount":0,"merchant_staff_tag":null,"updated_at":"2022-08-15T14:10:36.733596","owner":438020,"parent_transaction":null},"type":"TRANSACTION","hmac":"7432f4b9f82fbf8cce6421d94ba9b32177c716bc0111a5c867f0ace2e31b7ff20ab3fc1df8fc794cb73b7b9f139ae0637dfa789514b73bca12085b99b2120736"}'),
        true
    );

    post(route('paymob.transaction.processed'), $data);

    expect(PaymobTransaction::wherePaymobId(52195232)->exists())->toBeTrue();
});

it('can handle the transaction response callback', function () {
    $data = json_decode(
        stripslashes('{"source_data_type":"wallet","owner":"438020","is_voided":"false","refunded_amount_cents":"0","is_auth":"false","updated_at":"2022-08-16T11:15:07.025511","created_at":"2022-08-16T11:14:55.521348","is_refunded":"false","id":"52337134","merchant_commission":"0","captured_amount":"0","pending":"false","txn_response_code":"200","currency":"EGP","profile_id":"245593","success":"true","is_3d_secure":"false","error_occured":"false","is_refund":"false","order":"62780968","has_parent_transaction":"false","hmac":"84224546d338ebf0a5cf02f6df4580a2bc5829b61531b291273a730af9abe4581e5810cc9ec292d688531126449ce3fb19b94131d308cde2abcc7707167e68d2","is_capture":"false","data_message":"Transaction has been completed successfully.","source_data_sub_type":"wallet","is_standalone_payment":"true","integration_id":"2438611","source_data_pan":"01010101010","amount_cents":"44960","is_void":"false"}'),
        true
    );

    $response = get(route('paymob.transaction.response', $data));

    $response->assertRedirect(
        str($to = config('paymob.redirects.success'))->startsWith(['https://', 'http://'])
        ? $to
        : route($to)
    );
});
