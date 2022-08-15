<?php

use function Pest\Faker\faker;

use ZedanLab\Paymob\Services\PaymobOrder;
use ZedanLab\Paymob\Services\PaymobOrderItem;

it('can set or get delivery_needed attribute', function () {
    $order = new PaymobOrder();
    $order->deliveryNeeded(true);

    expect(boolval($order->get('delivery_needed')) === true)->toBeTrue();

    $order->deliveryNeeded(false);

    expect(boolval($order->get('delivery_needed')) === false)->toBeTrue();
});

it('can set or get merchant_order_id attribute', function () {
    $order = new PaymobOrder();
    $order->referenceId('121212');

    expect($order->get('merchant_order_id') === '121212')->toBeTrue();

    $order->referenceId('ABCDE');

    expect($order->get('merchant_order_id') === 'ABCDE')->toBeTrue();
});

it('can set or get amount_cents attribute', function () {
    $order = new PaymobOrder();
    $order->amount(1000);

    expect($order->get('amount_cents') === intval(1000 * 100))->toBeTrue();

    $order->amount(30000, true);

    expect($order->get('amount_cents') === intval(30000))->toBeTrue();
});

it('can set or get currency attribute', function () {
    $order = new PaymobOrder();
    $order->currency('AED');

    expect($order->get('currency') === 'AED')->toBeTrue();

    $order->currency('EGP');

    expect($order->get('currency') === 'EGP')->toBeTrue();
});

it('can set or get notify_user_with_email attribute', function () {
    $order = new PaymobOrder();
    $order->notifyUserWithEmail(true);

    expect(boolval($order->get('notify_user_with_email')) === true)->toBeTrue();

    $order->notifyUserWithEmail(false);

    expect(boolval($order->get('notify_user_with_email')) === false)->toBeTrue();
});

it('can set or get additional data attribute', function () {
    $order = new PaymobOrder();

    $order->additionalData($data = [
        'name' => faker()->name,
        'reference' => [
            'email' => faker()->email,
            'slug' => faker()->slug,
        ],
    ]);

    $data['payer'] = null;
    $data['payable'] = null;

    expect($order->get('data') === $data)->toBeTrue();

    $order = new PaymobOrder();

    $order->payable(50, 'App\\Models\\Product')
        ->payer(5, 'App\\Models\\User')
        ->additionalData($data = [
            'name' => faker()->name,
            'reference' => [
                'email' => faker()->email,
                'slug' => faker()->slug,
            ],
        ]);

    $data['payer'] = [
        'id' => 5,
        'type' => 'App\\Models\\User',
    ];

    $data['payable'] = [
        'id' => 50,
        'type' => 'App\\Models\\Product',
    ];

    expect($order->get('data') === $data)->toBeTrue();
});

it('can set or get items attribute', function () {
    $items = [
        PaymobOrderItem::make(
            name:"Item 1",
            amount_cents:"20000",
            description:"Item ID: 1",
            quantity:"2"
        ),
        PaymobOrderItem::make(
            name:"Item 2",
            amount_cents:"100000",
            description:"Item ID: 2",
            quantity:"1"
        ),
    ];

    $order = new PaymobOrder();
    $order->items($items);

    expect($order->get('items') === $items)->toBeTrue();

    $items = [
        PaymobOrderItem::make(
            name:"Item 1",
            amount_cents:"20000",
            description:"Item ID: 1",
            quantity:"2"
        ),
        PaymobOrderItem::make(
            name:"Item 2",
            amount_cents:"100000",
            description:"Item ID: 2",
            quantity:"1"
        ),
        PaymobOrderItem::make(
            name:"Item 3",
            amount_cents:"5000",
            description:"Item ID: 3",
            quantity:"10"
        ),
    ];

    $order->items(...$items);

    expect($order->get('items') === $items)->toBeTrue();
});
