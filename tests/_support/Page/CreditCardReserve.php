<?php
/**
 * Shop System Plugins:
 * - License can be found under:
 * https://github.com/wirecard/paymentSDK-php/blob/master/LICENSE
 */

namespace Page;

class CreditCardReserve extends Base
{
    // include url of current page
    public $URL = '/CreditCard/reserve.php';

    public $page_specific = 'pay';

    public $elements = array(
        'Redirect to 3-D Secure page' => "//*[@class='btn btn-primary']",
        'Reservation successfully completed.' => "Reservation successfully completed.",
        'Transaction ID' => "Transaction ID",
        'Transaction Identification' => "//div[contains(@class, 'content')]/a",
        'Noted Transaction Identification' => ''
    );
}
