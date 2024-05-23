<?php
namespace app\Traits;

use DateTimeInterface;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens as SanctumHasApiTokens;
use Laravel\Sanctum\NewAccessToken;

trait HasApiTokensCustom
{
    use SanctumHasApiTokens;

    /**
     * Create a new personal access token for the user.
     *
     * @param string $name
     * @param array $abilities
     * @return \Laravel\Sanctum\NewAccessToken
     */
    public function createToken(string $name, array $abilities = ['*'], DateTimeInterface $expiresAt = null): NewAccessToken
    {
        $token = $this->tokens()->create([
            #'id' => '66666',
            'id' => Str::uuid(),
            'name' => $name,
            'token' => hash('sha256', $plainTextToken = Str::random(40)),
            'abilities' => $abilities,
            'expires_at' => $expiresAt,
        ]);

        return new NewAccessToken($token, $token->getKey().'|'.$plainTextToken);
    }
}
