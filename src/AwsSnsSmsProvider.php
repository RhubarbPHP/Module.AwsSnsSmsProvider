<?php

namespace Rhubarb\AwsSnsSmsProvider\SMSProviders;

use Aws\Sns\SnsClient;
use Gcdtech\Aws\Settings\AwsSettings;
use Rhubarb\Crown\Sendables\Sendable;
use Rhubarb\Sms\Sendables\Sms\SmsProvider;

class AwsSnsSmsProvider extends SMSProvider
{
    /** @var SnsClient */
    private $client;

    public function getClient()
    {
        if (!isset($this->client)) {
            $awsSettings = AwsSettings::singleton();
            $settings = $awsSettings->getClientSettings();

            $this->client = new SnsClient($settings);
        }
        return $this->client;
    }

    /**
     * Sends the sendable.
     *
     * Implemented by the concrete provider type.
     *
     * @param Sendable $sendable
     * @return mixed
     */
    public function send(Sendable $sendable)
    {
        foreach($sendable->getRecipients() as $recipient) {
            $this->client->publish(['Message' => $sendable->getText(), 'PhoneNumber' => $recipient]);
        }
    }
}