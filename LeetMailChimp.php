<?php

//
class LeetMailChimp extends CApplicationComponent {

    // Public properties
    public $apiKey;
    public $listId;

    // Private properties
    private $dataCenter;
    private $apiEndpoint;

    // Initialize
    public function init() {
        // Validate `apiKey`
        if (empty($this->apiKey)) {
            throw new CException('Variable `apiKey` is required.');
        }
        if (!is_string($this->apiKey)) {
            throw new CException('The type for variable `apiKey` is wrong.');
        }

        // Validate `listId`
        if (empty($this->listId)) {
            throw new CException('Variable `listId` is required.');
        }
        if (!is_string($this->listId)) {
            throw new CException('The type for variable `listId` is wrong.');
        }

        //
        $this->dataCenter = substr($this->apiKey, strpos($this->apiKey, '-') + 1);

        //
        $this->apiEndpoint = 'https://' . $this->dataCenter . '.api.mailchimp.com/3.0';

        parent::init();
    }

    //
    public function getListMembers() {
        $url = $this->apiEndpoint . '/lists/' . $this->listId . '/members';
        $requestType = 'GET';

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestType);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        return json_decode(curl_exec($ch));
    }

    //
    public function addListMember($email) {
        $url = $this->apiEndpoint . '/lists/' . $this->listId . '/members';
        $requestType = 'POST';
        $json = json_encode([
            'email_address' => $email,
            'status' => 'subscribed',
        ]);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestType);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        return json_decode(curl_exec($ch));
    }

}