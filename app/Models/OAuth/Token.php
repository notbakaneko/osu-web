<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

namespace App\Models\OAuth;

use App\Events\UserSessionEvent;
use App\Exceptions\InvalidScopeException;
use App\Models\User;
use Ds\Set;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token as PassportToken;

class Token extends PassportToken
{
    // PassportToken doesn't have factory
    use HasFactory;

    public $timestamps = true;

    /**
     * Whether the resource owner is delegated to the client's owner.
     *
     * @return bool
     */
    public function delegatesOwner(): bool
    {
        return in_array('delegate', $this->scopes, true);
    }

    /**
     * Resource owner for the token.
     *
     * For client_credentials grants, this is the client that requested the token;
     * otherwise, it is the user that authorized the token.
     */
    public function getResourceOwner(): ?User
    {
        if ($this->isClientCredentials() && $this->delegatesOwner()) {
            return $this->client->user;
        }

        return $this->user;
    }

    public function isClientCredentials()
    {
        // explicitly no user_id.
        return $this->user_id === null;
    }

    public function isOwnToken(): bool
    {
        return $this->client->user_id !== null && $this->client->user_id === $this->user_id;
    }

    public function refreshToken()
    {
        return $this->hasOne(RefreshToken::class, 'access_token_id');
    }

    public function revokeRecursive()
    {
        $result = $this->revoke();
        optional($this->refreshToken)->revoke();

        return $result;
    }

    public function revoke()
    {
        $saved = parent::revoke();

        if ($saved && $this->user_id !== null) {
            UserSessionEvent::newLogout($this->user_id, ["oauth:{$this->getKey()}"])->broadcast();
        }

        return $saved;
    }

    public function scopeValidAt($query, $time)
    {
        return $query->where('revoked', false)->where('expires_at', '>', $time);
    }

    public function setScopesAttribute(?array $value)
    {
        if ($value !== null) {
            sort($value);
        }

        $this->attributes['scopes'] = $this->castAttributeAsJson('scopes', $value);
    }

    public function validate()
    {

        if (empty($this->scopes)) {
            throw new InvalidScopeException('Tokens without scopes are not valid.');
        }

        if ($this->client === null) {
            throw new InvalidScopeException('The client is not authorized.', 'unauthorized_client');
        }

        $scopes = new Set($this->scopes);

        $this->assertValidAnyScope($scopes);
        $this->assertValidChatWrite($scopes);
        $this->assertValidDelegation($scopes);

        return true;
    }

    public function save(array $options = [])
    {
        // Forces error if passport tries to issue an invalid client_credentials token.
        $this->validate();

        return parent::save($options);
    }

    public function user()
    {
        $provider = config('auth.guards.api.provider');

        return $this->belongsTo(config('auth.providers.'.$provider.'.model'), 'user_id');
    }

    private function assertValidAnyScope(Set $scopes): void
    {
        if (!$scopes->contains('*')) {
            return;
        }

        if ($scopes->count() > 1) {
            throw new InvalidScopeException('* is not valid with other scopes');
        }

        if ($this->isClientCredentials()) {
            throw new InvalidScopeException('* is not allowed with Client Credentials');
        }
    }

    private function assertValidChatWrite(Set $scopes)
    {
        // the user's own client can send messages as themselves for authorization code flows.
        if (!$scopes->contains('chat.write') || $this->isOwnToken()) {
            return;
        }

        // only clients owned by bots are allowed to act on behalf of another user.
        if (!$this->client->user->isBot()) {
            throw new InvalidScopeException('This scope is only available for chat bots or your own clients.');
        }

        if ($this->isClientCredentials() && !$scopes->contains('delegate')) {
            throw new InvalidScopeException('delegate scope is required.');
        }
    }

    // delegation of user-less token is only valid for chat bots
    private function assertValidDelegation(Set $scopes)
    {
        static $scopesRequireDelegation;
        $scopesRequireDelegation ??= new Set(['chat.write', 'delegate']);

        if (!$scopes->contains('delegate')) {
            return;
        }

        if (!$this->isClientCredentials()) {
            throw new InvalidScopeException('delegate scope is only valid for Client Credential tokens.');
        }

        if (!$this->client->user->isBot()) {
            throw new InvalidScopeException('delegate with Client Credentials is only available to chat bots.');
        }

        // delegation is only allowed if scopes given allow delegation.
        if (!$scopes->diff($scopesRequireDelegation)->isEmpty()) {
            throw new InvalidScopeException('delegation is not supported for this combination of scopes.');
        }
    }
}
