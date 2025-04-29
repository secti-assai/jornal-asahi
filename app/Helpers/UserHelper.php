<?php

namespace App\Helpers;

class UserHelper
{
    public static function getAvatarHtml($user, $size = 48, $class = 'rounded-circle me-3')
    {
        if ($user->profile_image) {
            return '<img src="' . asset('storage/' . $user->profile_image) . '" 
                    alt="' . $user->name . '" class="' . $class . '" 
                    width="' . $size . '" height="' . $size . '" style="object-fit: cover;">';
        } else {
            return '<img src="https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random" 
                    alt="' . $user->name . '" class="' . $class . '" 
                    width="' . $size . '" height="' . $size . '">';
        }
    }
}