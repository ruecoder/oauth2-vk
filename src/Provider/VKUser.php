<?php

namespace Ruecoder\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class VKUser implements ResourceOwnerInterface
{
    private $response = array();

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getId()
    {
        return $this->response['id'];
    }

    public function getEmail(): ?string
    {
        return $this->response['email'] || null;
    }

    public function getFirstName(): ?string
    {
        return $this->response['first_name'] || null;
    }

    public function getLastName(): ?string
    {
        return $this->response['last_name'] || null;
    }

    public function toArray(): array
    {
        return $this->response;
    }
}