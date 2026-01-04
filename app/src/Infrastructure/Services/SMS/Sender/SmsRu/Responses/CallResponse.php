<?php

namespace Infrastructure\Services\SMS\Sender\SmsRu\Responses;

/**
 * @property-read string $status
 * @property-read string $code
 * @property-read string $callId
 * @property-read float  $cost
 * @property-read float  $balance
 */
class CallResponse
{
    private string $status;

    private string $code;

    private string $callId;

    private float $cost;

    private float $balance;

    public function __construct(
        string $status,
        string $code,
        string $callId,
        float $cost,
        float $balance
    )
    {
        $this->status  = $status;
        $this->code    = $code;
        $this->callId  = $callId;
        $this->cost    = $cost;
        $this->balance = $balance;
    }

    public function __get(string $property)
    {
        return $this->$property;
    }
}
