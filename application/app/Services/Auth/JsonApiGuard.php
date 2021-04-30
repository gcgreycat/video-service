<?php


namespace App\Services\Auth;


use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class JsonApiGuard implements Guard
{
    use GuardHelpers;

    const TOKEN_FIELD = 'token';
    const TOKEN_EXPIRE_FIELD = 'token_expire_at';

    /**
     * @var Request
     */
    protected $request;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->setProvider($provider);
        $this->setRequest($request);
    }

    public function user(): ?Authenticatable
    {
        if (! is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        $token = $this->request->bearerToken();

        if (! empty($token)) {
            $user = $this->getUserByToken($token);
        }

        return $this->user = $user;
    }

    public function validate(array $credentials = []): bool
    {
        if (empty($credentials[static::TOKEN_FIELD])) {
            return false;
        }

        $user = $this->getUserByToken($credentials[static::TOKEN_FIELD]);
        if (empty($user)) {
            return false;
        }

        return true;
    }

    private function getUserByToken(string $token): ?Authenticatable
    {
        $credentials = [
            static::TOKEN_FIELD => $token,
        ];
        $user = $this->provider->retrieveByCredentials($credentials);
        if (empty($user) || $this->invalidTokenLife($user)) {
            return null;
        }
        return $user;
    }

    private function invalidTokenLife(Authenticatable $user): bool
    {
        $dateTime = $user->{static::TOKEN_EXPIRE_FIELD};
        if ($dateTime instanceof \DateTime) {
            return $dateTime <= new \DateTime();
        }
        return false;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function attempt(array $credentials = []): bool
    {
        $user = $this->provider->retrieveByCredentials($credentials);
        if (empty($user)) {
            return false;
        }

        if ($this->provider->validateCredentials($user, $credentials)) {
            $this->setUser($user);
            return true;
        } else {
            return false;
        }
    }
}
