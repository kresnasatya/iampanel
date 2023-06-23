<div class="container px-6 mx-auto grid">
    <h1 class="my-6 text-3xl font-semibold text-gray-700 dark:text-gray-200">{{ __('Realm') }}</h1>

    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ __('Endpoints') }}</h1>
    <ul>
        <li><a href="{{ config('sso.base_url') }}/realms/{{ config('sso.realm') }}/.well-known/openid-configuration" target="_blank" class="underline text-blue-500 focus-visible-control">OpenID Endpoint Configuration</a></li>
        <li><a href="{{ config('sso.base_url') }}/realms/{{ config('sso.realm') }}/protocol/saml/descriptor" target="_blank" class="underline text-blue-500 focus-visible-control">SAML 2.0 Identity Provider Metadata</a></li>
    </ul>
</div>
