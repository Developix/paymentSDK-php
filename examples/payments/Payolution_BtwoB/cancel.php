<?php
// # Cancelling a Payolution B2B transaction

// To cancel a transaction, a cancel request with the parent transaction = a non-captured authorization is sent.

// ## Required objects

// To include the necessary files, we use the composer for PSR-4 autoloading.
require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../inc/common.php';
require __DIR__ . '/../../configuration/globalconfig.php';

require __DIR__ . '/../../inc/header.php';

use Wirecard\PaymentSdk\Entity\Amount;
use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\SuccessResponse;
use Wirecard\PaymentSdk\Transaction\PayolutionBtwobTransaction;
use Wirecard\PaymentSdk\TransactionService;

if (!isset($_POST['parentTransactionId'])) {
    ?>
    <form action="cancel.php" method="post">
        <div class="form-group">
            <label for="parentTransactionId">Transaction ID to cancel:</label><br>
            <input id="parentTransactionId" name="parentTransactionId" class="form-control"/><br>
        </div>
        <button type="submit" class="btn btn-primary">Cancel</button>
    </form>
    <?php
} else {
// ## Gathering data

// ### Transaction related objects

// Use the amount object as amount which has to be paid by the consumer.
// For void-authorization (cancelling a non-captured authorization) only full amount of parent transaction is supported.
    $amount = new Amount(700, 'EUR');

// ### Transaction
    $transaction = new PayolutionBtwobTransaction();
    $transaction->setParentTransactionId($_POST['parentTransactionId']);
    $transaction->setAmount($amount);

// ## Perform the call using _Transaction Service_

    $transactionService = new TransactionService($config);
    $response = $transactionService->cancel($transaction);

// ## Response handling

// The response from the service can be used for disambiguation.
// In case of a successful transaction, a `SuccessResponse` object is returned.

    if ($response instanceof SuccessResponse) {
        echo 'Payment successfully cancelled.<br>';
        echo getTransactionLink($baseUrl, $response);

// In case of a failed transaction, a `FailureResponse` object is returned.
    } elseif ($response instanceof FailureResponse) {
// In our example we iterate over all errors and echo them out.
// You should display them as error, warning or information based on the given severity.
        foreach ($response->getStatusCollection() as $status) {
            /**
             * @var $status \Wirecard\PaymentSdk\Entity\Status
             */
            $severity = ucfirst($status->getSeverity());
            $code = $status->getCode();
            $description = $status->getDescription();
            echo sprintf('%s with code %s and message "%s" occurred.<br>', $severity, $code, $description);
        }
    }
}

require __DIR__ . '/../../inc/footer.php';
