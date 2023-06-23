@php
    $user = auth('imissu-web')->user();
    $picture = $user->picture ? user_profile_picture($user->picture) : user_profile_picture();
@endphp
<img {{ $attributes->merge(['class' => 'object-cover w-6 h-6 rounded-full']) }} src="{{ $picture }}" alt="User's photo" aria-hidden="true"/>