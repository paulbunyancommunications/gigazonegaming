<?php

namespace App\Http\Middleware;

use App\Http\Requests\UpdateRecipientRequest;
use Closure;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\ConstantContact;
use Ctct\Exceptions\CtctException;

/**
 * Class AddContactToConstantContactGigazoneGamingUpdatesMiddleware
 * @package App\Http\Middleware
 */
class AddContactToConstantContactGigazoneGamingUpdatesMiddleware
{
    /**
     * @var string
     */
    protected $listName = 'Gigazone Gaming Championship Updates';

    /**
     * @var string
     */
    protected $apiKey = '';

    protected $apiSecret = '';

    protected $apiToken = '';

    /**
     * @return string
     */
    public function getListName()
    {
        return $this->listName;
    }

    /**
     * @param string $listName
     * @return AddContactToConstantContactGigazoneGamingUpdatesMiddleware
     */
    public function setListName($listName)
    {
        $this->listName = $listName;
        return $this;
    }

    /**
     * Get api secret code
     * @return string
     */
    public function getApiSecret()
    {
        if (!$this->apiSecret) {
            $this->setApiSecret(env('CONSTANT_CONTACT_API_SECRET', 'CONSTANT_CONTACT_API_SECRET'));
        }
        return $this->apiSecret;
    }

    /**
     * @param string $apiSecret
     * @return AddContactToConstantContactGigazoneGamingUpdatesMiddleware
     */
    public function setApiSecret($apiSecret)
    {
        $this->apiSecret = $apiSecret;
        return $this;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(
        $request,
        Closure $next
    ) {
        $updateRequest = new UpdateRecipientRequest();
        $validator = \Validator::make($request->all(), $updateRequest->rules(), []);
        if ($validator->fails()) {
            // email not set or failed validation, just return to next middleware
            \Log::warning(json_encode($validator->getMessageBag()));
            return $next($request);
        }
        try {
            $constantContact = new ConstantContact($this->getApiKey());
            $updatesList = $this->getList($constantContact, $request, $next);
            $response = $constantContact->contactService->getContacts(
                $this->getApiToken(),
                array("email" => $this->email($request))
            );
            if (empty($response->results)) {
                // add the user to list
                $contact = new Contact();
                $contact->addEmail($this->email($request));
                $contact->addList($updatesList->id);
                $constantContact->contactService->addContact($this->getApiToken(), $contact);
            } else {
                // update user to this list
                $contact = $response->results[0];
                if ($contact instanceof Contact) {
                    $contact->addList($updatesList->id);
                    $constantContact->contactService->updateContact($this->getApiToken(), $contact);
                } else {
                    // @codeCoverageIgnoreStart
                    \Log::warning('contact is not an instance of Contact');
                    // @codeCoverageIgnoreEnd
                }
            }
        } catch (CtctException $ex) {
            throw new \Exception($ex->getMessage());
        }

        return $next($request);
    }

    /**
     * Get Api key
     * @return mixed
     */
    public function getApiKey()
    {
        if (!$this->apiKey) {
            $this->setApiKey(env('CONSTANT_CONTACT_API_KEY', 'CONSTANT_CONTACT_API_KEY'));
        }

        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @return AddContactToConstantContactGigazoneGamingUpdatesMiddleware
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @param ConstantContact $constantContact
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return ContactList|null
     */
    private function getList($constantContact, $request, $next)
    {
        $updatesList = null;
        try {
            $lists = $constantContact->listService->getLists($this->getApiToken());
            foreach ($lists as $list) {
                if ($list->name === $this->listName) {
                    return $list;
                }
            }
        } catch (CtctException $ex) {
            // if error log it and return to next middleware
            // @codeCoverageIgnoreStart
            \Log::warning($ex->getMessage());
            return $next($request);
            // @codeCoverageIgnoreEnd
        }

        if (!$updatesList) {
            // list was not found, log and make the list now
            \Log::info('List "' . $this->listName . '" was not found, it will be created.');
            $updatesList = $this->createList($constantContact);
            if (!$updatesList) {
                // if error log it and return to next middleware
                // @codeCoverageIgnoreStart
                \Log::warning('List "' . $this->listName . '" could not be created.');
                return $next($request);
                // @codeCoverageIgnoreEnd
            }
        }

        return $updatesList;
    }

    /**
     * Get api token
     * @return string
     */
    public function getApiToken()
    {
        if (!$this->apiToken) {
            $this->setApiToken(env('CONSTANT_CONTACT_API_TOKEN', 'CONSTANT_CONTACT_API_TOKEN'));
        }
        return $this->apiToken;
    }

    /**
     * @param string $apiToken
     * @return AddContactToConstantContactGigazoneGamingUpdatesMiddleware
     */
    public function setApiToken($apiToken)
    {
        $this->apiToken = $apiToken;
        return $this;
    }

    /**
     * Create a contacts list
     *
     * @param ConstantContact $cc
     * @return ContactList|null
     */
    private function createList(ConstantContact $cc)
    {
        $list = new ContactList();
        $listObject = $list->create(['name' => $this->listName, 'status' => 'ACTIVE']);
        try {
            $makeList = $cc->listService->addList($this->getApiToken(), $listObject);
        } catch (CtctException $e) {
            $makeList = null;
        }
        return $makeList;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function email($request)
    {
        return $request->input('email');
    }
}
