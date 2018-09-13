<?php
namespace Nitseditor\System\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use League\OAuth2\Server\ResourceServer;
use Illuminate\Auth\CreatesUserProviders;
use Illuminate\Auth\AuthenticationException;
use Nitseditor\System\Providers\ProviderRepository;
use Nitseditor\System\Guards\GuardChecker;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;

class ConfigAccessTokenCustomProvider
{
    use CreatesUserProviders;

    private $app;

    private $server;

    private $providers;

    public function __construct(ResourceServer $server, ProviderRepository $providers, App $app)
    {
        $this->server = $server;
        $this->providers = $providers;
        $this->app = App::getFacadeRoot();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the auth guard if has to check the default guard
        $guards = GuardChecker::getAuthGuards($request);
        // If don't has any guard follow the flow
        if (count($guards) == 0) {
            return $next($request);
        }
        $psr = (new DiactorosFactory)->createRequest($request);
        try {
            $psr = $this->server->validateAuthenticatedRequest($psr);
            $tokenId = $psr->getAttribute('oauth_access_token_id');
            if (! $tokenId) {
                return $next($request);
            }
            $accessToken = $this->providers->findForToken($tokenId);
            if (! $accessToken) {
                return $next($request);
            }
            // Check if has on provider users with same id of access_token owner.
            $entities = $this->entitiesWithSameIdOnProviders(
                $psr->getAttribute('oauth_user_id')
            );
            // If just one entity a register with this id follow the flow
            if (! ($entities->count() > 1)) {
                return $next($request);
            }
            if (count($guards) > 1) {
                // If has more than one guards set the api provider used to
                // choose provider on \Laravel\Passport\Bridge\UserRepository::getUserEntityByUserCredentials.
                config(['auth.guards.api.provider' => $accessToken->provider]);
                return $next($request);
            }
            // If has users with same id and the accessToken provider is different
            // of default provider return unauthenticated.
            if ($accessToken->provider != GuardChecker::defaultGuardProvider($guards[0])) {
                throw new AuthenticationException('Unauthenticated', $guards);
            }
        } catch (OAuthServerException $e) {
            //
        }
        return $next($request);
    }

    public function entitiesWithSameIdOnProviders($id)
    {
        $providers = array_keys(config('auth.providers'));
        $entities = collect([]);
        foreach ($providers as $provider) {
            $entity = $this->findEntityOnProvider($provider, $id);
            if (! is_null($entity)) {
                $entities->push($entity);
            }
        }
        return $entities;
    }

    public function findEntityOnProvider($provider, $userId)
    {
        $userProvider = $this->createUserProvider($provider);
        return $userProvider->retrieveById($userId);
    }
}