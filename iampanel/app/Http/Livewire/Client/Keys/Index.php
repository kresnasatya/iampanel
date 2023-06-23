<?php

namespace App\Http\Livewire\Client\Keys;

use Livewire\Component;
use RistekUSDI\Kisara\Client as KisaraClient;

class Index extends Component
{
    public $client_id;
    public $samlSigningCertificate;
    public $samlEncryptionCertificate;

    public function mount($id)
    {
        $this->client_id = $id;
        $client = (new KisaraClient(config('sso')))->findById($this->client_id);
        if ($client['protocol'] === 'saml') {
            if (isset($client['attributes']['saml.signing.certificate'])) {
                $this->samlSigningCertificate = $client['attributes']['saml.signing.certificate'];
            }

            if (isset($client['attributes']['saml.encryption.certificate'])) {
                $this->samlEncryptionCertificate = $client['attributes']['saml.encryption.certificate'];
            }
        }
    }

    public function submit()
    {
        $base_client = (new KisaraClient(config('sso')))->findById($this->client_id);
        // We must change value of authenticationFlowBindingOverrides to object if value is empty array
        // to prevent error update client
        if (empty($base_client['authenticationFlowBindingOverrides'])) {
            $base_client['authenticationFlowBindingOverrides'] = new \stdClass();
        }

        if (empty($this->samlSigningCertificate)) {
            $base_client['attributes']['saml.client.signature'] = 'false';
            $base_client['attributes']['saml.signing.certificate'] = "";
        } else {
            $base_client['attributes']['saml.client.signature'] = 'true';
            $base_client['attributes']['saml.signing.certificate'] = $this->samlSigningCertificate;
        }

        if (empty($this->samlEncryptionCertificate)) {
            $base_client['attributes']['saml.encrypt'] = 'false';
            $base_client['attributes']['saml.encryption.certificate'] = "";
        } else {
            $base_client['attributes']['saml.encrypt'] = 'true';
            $base_client['attributes']['saml.encryption.certificate'] = $this->samlEncryptionCertificate;
        }

        $result = (new KisaraClient(config('sso')))->update($this->client_id, $base_client);

        if ($result['code'] === 204) {
            $this->dispatchBrowserEvent('toast:ok', [
                'message' => "Berhasil disimpan!"
            ]);
        } else {
            $this->dispatchBrowserEvent('toast:error', [
                'message' => "Gagal disimpan!"
            ]);
        }

    }

    public function render()
    {
        return view('livewire.client.keys.index');
    }
}
