<?php

namespace App\Jobs;

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
     * @throws \Exception
     */
    public function handle()
    {
        $connection = new ConstantContact($this->apiKey);
        $this->validateAddress();
        $list = $this->validateList($connection);

        $contact = new Contact();
        $contact->addEmail($this->email);
        $contact->addList($list->id);
        $this->contactName($contact);

        try {
            $connection->contactService->addContact($this->apiToken, $contact);
        } catch (CtctException $ex) {
            $exceptionMessage = $this->email . ' return exception(s) "' . implode('", ', $ex->getErrors()) . ' "  with error code ' . $ex->getCode() . ' from the Constant Contact Api when trying to add it to the "' . $list->name . '" list';
            throw new \Exception($exceptionMessage);
        }

    }

    private function validateAddress()
    {
        $validator = \Validator::make(['email' => $this->email], ['email' => 'required|email'], []);
        if ($validator->fails()) {
            // email not set or failed validation, just return to next middleware
            throw new \Exception(implode(' ', $validator->getMessageBag()));
        }
        return $this;
    }

    /**
     * Check for list in lists on constant contact account
     * @param $connection
     * @return mixed
     * @throws \Exception
     */
    private function validateList($connection)
    {

        $useList = false;
        try {
            $lists = $connection->listService->getLists($this->apiToken, []);
        } catch (CtctException $ex) {
            throw new \Exception(implode(' ', $ex->getErrors()));
        }

        foreach ($lists as $list) {
            if (strtoupper($list->name) === strtoupper($this->listName)) {
                $useList = $list;
                break;
            }
        }
        if (!$useList) {
            throw new \Exception('List ' . $this->listName . ' does not exist.');
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
}
