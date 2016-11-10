<?php

namespace App\Jobs;

use App\Exceptions\ConstantContactAddRecipientJobException;
use Ctct\Components\Contacts\Contact;
use Ctct\ConstantContact;
use Ctct\Exceptions\CtctException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConstantContactAddRecipientJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $apiKey;
    protected $apiToken;
    protected $apiSecret;
    protected $listName;
    protected $email;
    protected $name;

    /**
     * Create a new job instance.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->init($config);
    }

    /**
     * @param $config
     */
    protected function init($config)
    {
        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Execute the job.
     *
     * @throws ConstantContactAddRecipientJobException
     */
    public function handle()
    {
        $connection = new ConstantContact($this->getApiKey());
        $this->validateAddress();
        $list = $this->validateList($connection);

        $contact = new Contact();
        $contact->addEmail($this->email);
        $contact->addList($list->id);
        $this->contactName($contact);

        try {
            return $connection->contactService->addContact($this->getApiToken(), $contact);
        } catch (CtctException $ex) {
            $errors = '';
            if (!empty($ex->getErrors())) {
                foreach ($ex->getErrors() as $e) {
                    $errors .= $e->error_message . ', ';
                }
                $errors = rtrim($errors, ', ');
            }
            $exceptionMessage = $this->email . ' return exception(s) "' . $errors . '"  with error code ' . $ex->getCode() . ' from the Constant Contact Api when trying to add it to the "' . $list->name . '" list';
            if ($this->checkAlreadyExistsMessage($exceptionMessage) === true) {
                return true;
            }
            /** @codeCoverageIgnoreStart */
            throw new ConstantContactAddRecipientJobException($exceptionMessage);
            /** @codeCoverageIgnoreEnd */
        }
    }

    private function validateAddress()
    {
        $validator = \Validator::make(['email' => $this->email], ['email' => 'required|email'], ['email.email' => 'The :attribute must be a valid email address to add recipient.']);
        if ($validator->fails()) {
            // email not set or failed validation
            $message = is_array($validator->getMessageBag()) ? implode(' ', (array)$validator->getMessageBag()) : $validator->getMessageBag();
            throw new ConstantContactAddRecipientJobException($message);
        }
        return $this;
    }

    /**
     * Check for list in lists on constant contact account
     * @param $connection
     * @return mixed
     * @throws ConstantContactAddRecipientJobException
     */
    private function validateList($connection)
    {

        $useList = false;
        try {
            $lists = $connection->listService->getLists($this->apiToken, []);
        } catch (CtctException $ex) {
            throw new ConstantContactAddRecipientJobException($ex->getMessage());
        }

        foreach ($lists as $list) {
            if (strtoupper($list->name) === strtoupper($this->getListName())) {
                $useList = $list;
                break;
            }
        }
        if (!$useList) {
            throw new ConstantContactAddRecipientJobException('List "' . $this->getListName() . '" does not exist.');
        }

        return $useList;
    }

    /**
     * Slice apart the name into first and last
     * @param $contact
     */
    private function contactName(&$contact)
    {
        if (strpos($this->name, ' ') !== false) {
            $name = explode(' ', $this->name);
            $contact->last_name = array_pop($name);
            $contact->first_name = implode(' ', $name);
        } else {
            $contact->first_name = $this->name;
            $contact->last_name = '';
        }
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        if ($this->apiKey == 'CONSTANT_CONTACT_API_KEY' && config('constant_contact.api_key')) {
            return config('constant_contact.api_key');
        }
        return $this->apiKey;
    }

    /**
     * @return mixed
     */
    public function getApiToken()
    {
        if ($this->apiToken == 'CONSTANT_CONTACT_API_TOKEN' && config('constant_contact.api_token')) {
            return config('constant_contact.api_token');
        }
        return $this->apiToken;
    }

    /**
     * @return mixed
     */
    public function getApiSecret()
    {
        if ($this->apiSecret == 'CONSTANT_CONTACT_API_SECRET' && config('constant_contact.api_secret')) {
            return config('constant_contact.api_secret');
        }
        return $this->apiSecret;
    }

    /**
     * @return mixed
     */
    public function getListName()
    {
        if ($this->listName == 'CONSTANT_CONTACT_LIST_NAME' && config('constant_contact.list_name')) {
            return config('constant_contact.list_name');
        }
        return $this->listName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Flush job from queue if Constant Contact returns a message "Email address [email address] already exists."
     *
     * URL that generated this code:
     * http://txt2re.com/index-php.php3?s=Email%20address%20willfalldor@gmail.com%20already%20exists.&-12&-6&-21&-22&1&23&-7&-24&-10
     * @param $message
     * @return bool
     */
    protected function checkAlreadyExistsMessage($message)
    {
        $re1 = '(Email)';    # Word 1
        $re2 = '( )';    # White Space 1
        $re3 = '(address)';    # Word 2
        $re4 = '( )';    # White Space 2
        $re5 = '([\\w-+]+(?:\\.[\\w-+]+)*@(?:[\\w-]+\\.)+[a-zA-Z]{2,7})';    # Email Address 1
        $re6 = '(\\s+)';    # White Space 3
        $re7 = '(already)';    # Word 3
        $re8 = '( )';    # White Space 4
        $re9 = '(exists)';    # Word 4

        if ($capture = preg_match_all("/" . $re1 . $re2 . $re3 . $re4 . $re5 . $re6 . $re7 . $re8 . $re9 . "/is", $message, $matches)) {
            return $matches[5][0] === $this->getEmail();
        }
        /** @codeCoverageIgnoreStart */
        return false;
        /** @codeCoverageIgnoreEnd */
    }
}
