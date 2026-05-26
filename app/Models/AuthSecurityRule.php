<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthSecurityRule extends Model
{
    use HasFactory;
    protected $fillable = [
        'require_email_verification',
        'require_captcha',
        'recaptcha_sitekey',
        'recaptcha_secretkey',
        'allow_registration',
        'allowed_email_domains'
    ];
    protected $casts = [
        'require_email_verification' => 'boolean',
        'require_captcha' => 'boolean',
        'allow_registration' => 'boolean',
        
    ];
}
