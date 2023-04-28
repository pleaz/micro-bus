<?php

namespace Amranidev\MicroBus\Sns;

use Illuminate\Support\Str;

class Publisher extends BaseSns
{
    /**
     * Publish the message to SNS.
     *
     * @param string $topic
     * @param mixed  $message
     *
     * @throws \Exception
     *
     * @return \Aws\Result
     */
    public function publish($topic, $message, $region = null)
    {
        $topic = $this->getTopicArn($topic);
        $messageAttributes = [
            'MICRO_BUS.JOB_UUID' => [
                'DataType'    => 'String',
                'StringValue' => (string) Str::uuid(),
            ]
        ];
        if ($region) {
            $messageAttributes = array_merge($messageAttributes, [
                'country' => [
                    'DataType'    => 'String',
                    'StringValue' => $region,
                ]
            ]);
        }

        return $this->sns->publish([
            'Message'           => $this->prepareMessage($message),
            'TopicArn'          => $topic,
            'MessageAttributes' => $messageAttributes
        ]);
    }
}
