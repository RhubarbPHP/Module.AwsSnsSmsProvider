<?php

namespace Rhubarb\AwsSnsSmsProvider\SMSProviders;

use Aws\Sns\SnsClient;
use Rhubarb\AwsSnsSmsProvider\SMSProviders\Settings\AwsSnsSettings;
use Rhubarb\Crown\Sendables\Sendable;
use Rhubarb\Sms\Sendables\Sms\SmsProvider;

class AwsSnsSmsProvider extends SMSProvider
{
    /** @var SnsClient */
    private $client;

    public function getClient()
    {
        if (!isset($this->client)) {
            $awsSettings = AwsSnsSettings::singleton();
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
        $client = $this->getClient();

        foreach ($sendable->getRecipients() as $recipient) {
            $client->publish(['Message' => $sendable->getText(), 'PhoneNumber' => $recipient]);
        }
    }
}