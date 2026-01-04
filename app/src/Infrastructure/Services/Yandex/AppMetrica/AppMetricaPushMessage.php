<?php

namespace Infrastructure\Services\Yandex\AppMetrica;

use Illuminate\Support\Arr;

class AppMetricaPushMessage
{
    protected array $messages = [];

    protected array $devices = [];

    protected int $groupId = 933719;

    private string $tag = 'some_tag';

    public function __construct(array $data = [])
    {
        $default = $this->getDefaultData();
        $data = array_merge_recursive($default, $data);

        $this->messages = Arr::get($data, 'push_batch_request.batch.0.messages');
        $this->devices = Arr::get($data, 'push_batch_request.batch.0.devices');
    }

    /**
     * @param int $groupId
     * @return AppMetricaPushMessage
     */
    public function setGroupId(int $groupId): static
    {
        $this->groupId = $groupId;
        return $this;
    }

    /**
     * @param string $tag
     * @return AppMetricaPushMessage
     */
    public function setTag(string $tag): static
    {
        $this->tag = $tag;

        return $this;
    }


    public function title(string $title): static
    {
        Arr::set($this->messages, 'android.content.title', $title);
        Arr::set($this->messages, 'iOS.content.title', $title);

        return $this;
    }

    public function text(string $text): static
    {
        Arr::set($this->messages, 'android.content.text', $text);
        Arr::set($this->messages, 'iOS.content.text', $text);

        return $this;
    }

    public function deeplink(string $deeplink): static
    {
        Arr::set($this->messages, 'android.open_action.deeplink', $deeplink);
        Arr::set($this->messages, 'iOS.open_action.deeplink', $deeplink);

        return $this;
    }

    public function url(string $url): static
    {
        Arr::set($this->messages, 'android.open_action.url', $url);
        Arr::set($this->messages, 'iOS.open_action.url', $url);

        return $this;
    }

    public function contentData(string $data): static
    {
        Arr::set($this->messages, 'android.content.data', $data);
        Arr::set($this->messages, 'iOS.content.data', $data);

        return $this;
    }

    public function withMessage(array $data): static
    {
        $data = Arr::dot($data);

        foreach ($data as $key => $value) {
            Arr::set($this->messages, $key, $value);
        }

        return $this;
    }

    public function withDevices(array $devices): static
    {
        $this->devices = array_merge_recursive($this->devices, $devices);

        return $this;
    }

    public function setNotifiable($notifiable): static
    {
        $notifiable->loadMissing('mobileTokens');

        $this->devices = collect($notifiable->mobileTokens->toArray())
            ->map(function ($item) {
                return [
                    'token' => $item['token'],
                    'device' => $item['device'],
                ];
            })
            ->groupBy('device')
            ->map(function ($item, $key) {
                $values = collect($item)->pluck('token')->toArray();

                return [
                    'id_type' => $key,
                    'id_values' => $values,
                ];
            })
            ->values()
            ->toArray();

        return $this;
    }

    public function toArray(): array
    {
        return [
            'push_batch_request' => [
                'group_id'  => $this->groupId,
                'tag'       => $this->tag,
                'batch'     => [
                    [
                        'messages' => $this->messages,
                        'devices' => $this->devices,
                    ]
                ]
            ]
        ];
    }

    private function getDefaultData(): array
    {
        return [
            'push_batch_request' => [
                'group_id' => 933719,
                'tag' => 'some_tag',
                'batch' => [
                    [
                        'messages' => [
                            'android' => [
                                'silent' => false,
                                'content' => [
                                    'title' => '',
                                    'text' => '',
                                    'icon' => '46',
                                    'icon_background' => '#FFFFFFFF',
                                    'priority' => -2,
                                    'collapse_key' => 2001,
                                    'vibration' => [
                                        0,
                                        500,
                                    ],
                                    'led_color' => '#FFFFFF',
                                    'led_interval' => 50,
                                    'led_pause_interval' => 50,
                                    'time_to_live' => 180,
                                ],
                            ],
                            'iOS' => [
                                'silent' => false,
                                'content' => [
                                    'title' => '',
                                    'text' => '',
                                    'badge' => '0',
                                    'sound' => 'disable',
                                    'attachments' => [
                                    ],
                                ],
                            ],
                        ],
                        'devices' => [
                        ],
                    ],
                ],
            ],
        ];
    }
}
