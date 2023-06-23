<?php

namespace App\Http\Livewire\Client\Environment;

use Livewire\Component;
use RistekUSDI\Kisara\ClientSecret as KisaraClientSecret;

class Index extends Component
{
    public $client_id;
    public $clientId;
    public $client_secret;
    public $allow_refresh_client_secret = false;
    public $ready_to_load = false;

    protected $listeners = ['copyEnv', 'toggle-environment' => '$refresh', 'environment-update-client-id' => 'updateClientId'];

    public function mount($id, $clientId)
    {
        $this->client_id = $id;
        $this->client_secret = (new KisaraClientSecret(config('sso')))->get($this->client_id);
        $this->clientId = $clientId;
        if ($this->clientId !== config('sso.client_id')) {
            $this->allow_refresh_client_secret = true;
        }
    }

    // For defer loading case!
    // Please see: https://laravel-livewire.com/docs/2.x/defer-loading#introduction
    public function loadEnvironment()
    {
        $this->ready_to_load = true;
    }

    public function updateClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    public function getEnvKeysProperty()
    {
        $admin_url = config('sso.admin_url');
        $base_url = config('sso.base_url');
        $realm = config('sso.realm');
        $public_key = config('sso.realm_public_key');
        $keycloak_client_secret = !empty($this->client_secret) ? "\nKEYCLOAK_CLIENT_SECRET={$this->client_secret}" : '';
        $connector_host_url = request()->getSchemeAndHttpHost();
        $rbac_connector_env = "";
        if (!empty($this->client_secret)) {
            $rbac_connector_env = "\n# For RBAC connector \n# RBAC_CONNECTOR_HOST_URL=$connector_host_url";
        }
        
        return <<<EOF
        KEYCLOAK_ADMIN_URL=$admin_url
        KEYCLOAK_BASE_URL=$base_url
        KEYCLOAK_REALM=$realm
        KEYCLOAK_REALM_PUBLIC_KEY=$public_key
        KEYCLOAK_CLIENT_ID=$this->clientId
        EOF
        .$keycloak_client_secret."\n# For non Laravel Framework \n# KEYCLOAK_CALLBACK=http://yourapp.test/sso/callback \n# KEYCLOAK_REDIRECT_URL=/home"
        ."\n"
        .$rbac_connector_env
        ."\n"
        ."\n# For ristekusdi/keycloak-token-laravel \n# KEYCLOAK_ALLOWED_RESOURCES=realm-management \n# KEYCLOAK_LEEWAY=60";
    }

    public function refreshClientSecret()
    {
        $this->client_secret = (new KisaraClientSecret(config('sso')))->update($this->client_id);
    }

    public function copyEnv()
    {
        $this->dispatchBrowserEvent('copy:env', [
            'text' => $this->env_keys
        ]);
    }

    public function render()
    {
        // For defer loading case!
        // Please see: https://laravel-livewire.com/docs/2.x/defer-loading#introduction
        if ($this->ready_to_load) {
            $this->client_secret = (new KisaraClientSecret(config('sso')))->get($this->client_id);
        }

        return view('livewire.client.environment.index', [
            'env_keys' => $this->env_keys,
        ]);
    }
}
