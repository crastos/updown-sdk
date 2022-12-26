<?php

it('returns an endpoint', function () {
    expect($this->app->make('updown')->nodes)->toBeInstanceOf(\Crastos\Updown\Http\Endpoints\Nodes::class);
});

it('returns an endpoint with underscores', function () {
    expect($this->app->make('updown')->status_pages)->toBeInstanceOf(\Crastos\Updown\Http\Endpoints\StatusPages::class);
});

it('returns an api if endpoint does not exist', function () {
    expect($this->app->make('updown')->kjo)->toBeInstanceOf(\Crastos\Updown\Updown::class);
});

it('builds a path', function () {
    expect($this->app->make('updown')->ayy->lmao->kjo->iirc->path)->toBe('/ayy/lmao/kjo/iirc');
});

it('builds a path even if endpoint is returned', function () {
    expect($this->app->make('updown')->nodes->path)->toBe('/nodes');
    expect($this->app->make('updown')->nodes->kjo->path)->toBe('/nodes/kjo');
});
