<?php
namespace App\Exceptions;

use Exception;

/**
 * ConstantContactAddRecipientJobException
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package App\Exceptions
 */
class ConstantContactAddRecipientJobException extends Exception
{

    /**
     * ConstantContactAddRecipientJobException constructor.
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        return parent::__construct($message, $code, $previous);
    }
}
