<?php

namespace App\Http\Livewire\Client;

use Livewire\Component;
use RistekUSDI\Kisara\Client as KisaraClient;

class Edit extends Component
{
    public $client;
    public $clientId;
    public $client_id;
    public $redirectUri;
    public $redirectUris;
    public $publicClient;
    public $name;
    public $information;
    public $categories;
    public $rootUrl;
    public $selected_categories = [];
    public $allow_edit_clientId = false;
    public $nameIdFormat = 'username';
    public $assertionConsumerUrlPost;
    public $singleLogoutServiceUrlPost;
    public $singleLogoutServiceUrlRedirect;

    protected $rules = [
        'clientId' => 'required',
        'name' => 'required',
        'information' => 'required|max:127|not_regex:/#/i', // tidak boleh ada tanda pagar
        'categories' => 'required|array|min:1|max:3'
    ];

    protected $messages = [
        'clientId.required' => 'Client ID wajib diisi.',
        'name.required' => 'Nama aplikasi wajib diisi.',
        'information.required' => 'Informasi wajib diisi',
        'information.max' => 'Informasi maksimal 127 karakter',
        'information.not_regex' => 'Tidak boleh ada tanda # di field Tujuan',
        'categories.required' => 'Kategori wajib diisi',
        'categories.min' => 'Minimal 1 kategori dipilih',
        'categories.max' => 'Maksimal 3 kategori dipilih'
    ];

    public function mount($client_id)
    {
        $this->client = (new KisaraClient(config('sso')))->findById($client_id);
        if (empty($this->client)) {
            abort(404, 'Client tidak ditemukan!');
        }
        $this->clientId = $this->client['clientId'];
        if (in_array($this->clientId, explode(',', config('keycloak-default-clients.clients')))) {
            abort(403, 'Anda tidak diijinkan mengedit client ini!');
        }
        $this->client_id = $client_id;
        $this->name = isset($this->client['name']) ? $this->client['name'] : '';
        $this->description = isset($this->client['description']) ? $this->client['description'] : '';
        $this->rootUrl = isset($this->client['rootUrl']) ? $this->client['rootUrl'] : '';
        $this->redirectUris = $this->client['redirectUris'];
        $this->publicClient = $this->client['publicClient'];
        if ($this->clientId !== config('sso.client_id')) {
            $this->allow_edit_clientId = true;
        }
        if (!empty($this->description)) {
            $description = explode('#', $this->description);
            $this->information = $description[0];
            unset($description[0]);

            $selected_categories = [];
            foreach ($description as $value) {
                array_push($selected_categories, trim("#".$value));
            }

            $this->categories = array_unique($selected_categories);
        } else {
            $this->categories = [];
        }

        // SAML client
        if ($this->client['protocol'] === 'saml') {
            if (isset($this->client['attributes']['saml_name_id_format'])) {
                $this->nameIdFormat = $this->client['attributes']['saml_name_id_format'];
            }

            if (isset($this->client['attributes']['saml_assertion_consumer_url_post'])) {
                $this->assertionConsumerUrlPost = $this->client['attributes']['saml_assertion_consumer_url_post'];
            }

            if (isset($this->client['attributes']['saml_single_logout_service_url_post'])) {
                $this->singleLogoutServiceUrlPost = $this->client['attributes']['saml_single_logout_service_url_post'];
            }

            if (isset($this->client['attributes']['saml_single_logout_service_url_redirect'])) {
                $this->singleLogoutServiceUrlRedirect = $this->client['attributes']['saml_single_logout_service_url_redirect'];
            }
        }
    }

    public function addRedirectUri()
    {
        $this->redirectUris[] = $this->redirectUri;
        $this->redirectUri = '';
    }

    public function removeRedirectUri($index)
    {
        unset($this->redirectUris[$index]);
        $this->redirectUris = array_values($this->redirectUris);
    }

    public function submit()
    {
        $this->validate();

        $base_client = (new KisaraClient(config('sso')))->findById($this->client_id);
        // We must change value of authenticationFlowBindingOverrides to object if value is empty array
        // to prevent error update client
        if (empty($base_client['authenticationFlowBindingOverrides'])) {
            $base_client['authenticationFlowBindingOverrides'] = new \stdClass();
        }

        foreach ($this->categories as $category) {
            array_push($this->selected_categories, $category);
        }

        $description = trim($this->information)." ".implode(' ', $this->selected_categories);

        $this->selected_categories = [];

        $update_client = [
            'clientId' => str_replace(" ", "-", strtolower($this->clientId)),
            'redirectUris' => $this->redirectUris,
            'name' => $this->name,
            'description' => $description,
            'rootUrl' => $this->rootUrl,
            'publicClient' => $this->publicClient,
        ];

        $client = array_replace($base_client, $update_client);

        // Activate post logout redirect uris if redirect uris more than 1.
        if (count($this->redirectUris) >= 1) {
            $client['attributes']['post.logout.redirect.uris'] = '+##';
        }

        if ($this->client['protocol'] === 'saml') {
            $client['attributes']['saml_name_id_format'] = $this->nameIdFormat;
            $client['attributes']['saml_assertion_consumer_url_post'] = $this->assertionConsumerUrlPost;
            $client['attributes']['saml_single_logout_service_url_post'] = $this->singleLogoutServiceUrlPost;
            $client['attributes']['saml_single_logout_service_url_redirect'] = $this->singleLogoutServiceUrlRedirect;
        }

        $result = (new KisaraClient(config('sso')))->update($this->client_id, $client);

        if ($result['code'] === 204) {
            $this->dispatchBrowserEvent('toast:ok', [
                'message' => "Berhasil disimpan!"
            ]);
            /**
             * This method will refresh component without refresh the browser!
             */
            $this->emit('$refresh');
            $this->emit('toggle-environment');
            $this->emit('environment-update-client-id', $this->clientId);
        } else {
            $this->dispatchBrowserEvent('toast:error', [
                'message' => "Gagal disimpan!"
            ]);
        }
    }

    public function render()
    {
        return view('livewire.client.edit');
    }
}
