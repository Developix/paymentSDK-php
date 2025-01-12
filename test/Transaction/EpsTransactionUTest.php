<?php
/**
 * Shop System SDK - Terms of Use
 *
 * The SDK offered are provided free of charge by Wirecard AG and are explicitly not part
 * of the Wirecard AG range of products and services.
 *
 * They have been tested and approved for full functionality in the standard configuration
 * (status on delivery) of the corresponding shop system. They are under General Public
 * License Version 3 (GPLv3) and can be used, developed and passed on to third parties under
 * the same terms.
 *
 * However, Wirecard AG does not provide any guarantee or accept any liability for any errors
 * occurring when used in an enhanced, customized shop system configuration.
 *
 * Operation in an enhanced, customized configuration is at your own risk and requires a
 * comprehensive test phase by the user of the plugin.
 *
 * Customers use the SDK at their own risk. Wirecard AG does not guarantee their full
 * functionality neither does Wirecard AG assume liability for any disadvantages related to
 * the use of the SDK. Additionally, Wirecard AG does not guarantee the full functionality
 * for customized shop systems or installed SDK of other vendors of plugins within the same
 * shop system.
 *
 * Customers are responsible for testing the SDK's functionality before starting productive
 * operation.
 *
 * By installing the SDK into the shop system the customer agrees to these terms of use.
 * Please do not use the SDK if you do not agree to these terms of use!
 */

namespace WirecardTest\PaymentSdk\Transaction;

use PHPUnit_Framework_TestCase;
use Wirecard\PaymentSdk\Entity\Amount;
use Wirecard\PaymentSdk\Entity\Redirect;
use Wirecard\PaymentSdk\Transaction\EpsTransaction;
use Wirecard\PaymentSdk\Transaction\Operation;

class EpsTransactionUTest extends PHPUnit_Framework_TestCase
{
    public function testMappedProperties()
    {
        $tx = new EpsTransaction();
        $tx->setLocale('de');
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '0.0.0.1';
        $amount = new Amount(18.4, 'EUR');
        $tx->setAmount($amount);

        $redirect = new Redirect(
            'http://www.test.at/return.php?status=success',
            null,
            'http://www.test.at/return.php?status=failure'
        );
        $tx->setRedirect($redirect);

        $tx->setOperation(Operation::PAY);

        $expectedResult = [
            'transaction-type' => 'debit',
            'requested-amount' => [
                'currency' => 'EUR',
                'value' => '18.4'
            ],
            'payment-methods' => [
                'payment-method' => [
                    0 => [
                        'name' => 'eps'
                    ]
                ]
            ],
            'success-redirect-url' => 'http://www.test.at/return.php?status=success',
            'fail-redirect-url' => 'http://www.test.at/return.php?status=failure',
            'locale' => 'de',
            'entry-mode' => 'ecommerce',
            'ip-address' => '0.0.0.1'
        ];

        $result = $tx->mappedProperties();

        $this->assertEquals($expectedResult, $result);
    }

    public function testDescriptor()
    {
        $descriptor = "0123-€\$§%!=#~;+/?:().,'&><\"*{}[]@\_°^ |ÄÖÜäöüß°`abcdefghijklmn" .
            "opqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $expectedDescriptor = "0123-€\$§%!=#~;+/?:().,'&><\"*{}[]@\_°^ |ÄÖÜäöüß°" .
            "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $transaction = new EpsTransaction();
        $transaction->setDescriptor($descriptor);
        $this->assertEquals($expectedDescriptor, $transaction->getDescriptor());
    }
}
