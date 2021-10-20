<?php

namespace Ruecoder\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class VK extends AbstractProvider
{
    use BearerAuthorizationTrait;

    protected $version = '5.131';
    protected $lang = null;
    protected $fields = ['id', 'nickname', 'photo_200_orig'];

    public function getBaseAuthorizationUrl(): string
    {
        return 'https://oauth.vk.com/authorize';
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return 'https://oauth.vk.com/access_token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        $query = $this->buildQueryString([
            'fields' => $this->fields,
            'access_token' => $token->getToken(),
            'v' => $this->version,
            'lang' => $this->lang
        ]);

        return "https://api.vk.com/method/users.get?$query";
    }

    protected function getDefaultScopes(): array
    {
        return ['email'];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (empty($data['error'])) {
            return;
        }

        throw new IdentityProviderException($data['error'], 0, $data);
    }

    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
    {
        return new VKUser($response);
    }
}