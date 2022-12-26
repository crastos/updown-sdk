<?php

namespace Crastos\Updown\Http\Resources;

use Crastos\Updown\Enums\HttpVerb;
use Crastos\Updown\Enums\Until;
use Crastos\Updown\Http\Endpoints\Endpoint;
use Crastos\Updown\Http\Resources\Check\Ssl;
use DateTimeInterface;

class Check extends Resource
{
    public string $url;

    public int $period = 60;

    public float $apdex_t = 0.5;

    public bool $enabled = true;

    public bool $published = true;

    public string $alias;

    public string $string_match;

    public DateTimeInterface|Until|null $mute_until;

    public HttpVerb $http_verb;

    public string $http_body;

    public iterable $disabled_locations;

    /** @var ResourceRepository<Recipient[]> */
    public ResourceRepository $recipients;

    public iterable $custom_headers;

    public readonly string $token;

    public readonly float $uptime;

    public readonly bool $down;

    public readonly mixed $error;

    public readonly string $favicon_url;

    public readonly int $last_status;

    public readonly ?DateTimeInterface $down_since;

    public readonly ?DateTimeInterface $next_check_at;

    public readonly ?DateTimeInterface $last_check_at;

    public readonly Ssl $ssl;

    public function __construct(iterable|string|int $payload = [], protected ?Endpoint $endpoint = null)
    {
        parent::__construct($payload, $endpoint);

        foreach ($this->payload as $key => $value) {
            $this->{$key} = $this->transform($key, $value);
        }
    }

    protected function transformMuteUntil($value, $key, $type)
    {
        return Until::from($value) ?? $this->transformDateTime($value, $key, $type);
    }

    protected function transformHttpVerb($value, $key, $type)
    {
        return HttpVerb::from($value);
    }
}
