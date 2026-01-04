<?php

namespace Infrastructure\Services\SMS\Sender\SmsRu;

use DomainException;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Infrastructure\Services\SMS\Sender\SmsApiInterface;
use Infrastructure\Services\SMS\Sender\SmsRu\Exceptions\CouldNotSendNotification;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Arr;

class SmsRuApi implements SmsApiInterface
{
    public const FORMAT_JSON = 1;

    /** @var HttpClient */
    protected HttpClient $client;

    /** @var string */
    protected string $endpoint;

    /** @var string */
    protected string $api_id;

    /** @var string */
    protected string $sender;

    /** @var bool */
    protected bool $debug;

    /** @var array */
    protected array $extra;

    public function __construct(array $config)
    {
        $this->api_id = Arr::get($config, 'api_id');
        $this->sender = Arr::get($config, 'sender');
        $this->endpoint = sprintf('%s/sms/send', Arr::get($config, 'host'));
        $this->debug = Arr::get($config, 'debug', false);
        $this->extra = Arr::get($config, 'extra', []);

        $this->client = new HttpClient([
            'timeout' => 5,
            'connect_timeout' => 5,
        ]);
    }

    /**
     * @throws CouldNotSendNotification
     * @throws GuzzleException
     */
    public function send(array $params): array
    {
        $base = [
            'api_id'   => $this->api_id,
            'json'     => self::FORMAT_JSON,
        ];

        $remoteAddress = Arr::get($_SERVER, "HTTP_X_REAL_IP", false);
        if($remoteAddress) {
            $base['ip'] = $remoteAddress;
        }

        if ($this->sender) {
            $base['from'] = $this->sender;
        }

        if ($this->debug) {
            $base['test'] = true;
        }

        $params = array_merge($base, array_filter($params), $this->extra);

        try {
            $response = $this->client->request('GET', $this->endpoint, ['query' => $params]);

            $response = json_decode((string) $response->getBody(), true);

            if (isset($response['status']) && $response['status'] === 'ERROR') {
                throw new DomainException($response['status_text'], $response['status_code']);
            }

            return $response;
        } catch (DomainException $exception) {
            throw CouldNotSendNotification::smsRespondedWithAnError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithSms($exception);
        }
    }
}
