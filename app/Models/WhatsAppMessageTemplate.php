<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppMessageTemplate extends Model
{
    protected $table = 'whatsapp_message_templates';
    
    protected $fillable = [
        'type',
        'message_template',
    ];

    /**
     * Get template by type
     */
    public static function getTemplate($type, $default = null)
    {
        $template = self::where('type', $type)->first();
        return $template ? $template->message_template : $default;
    }

    /**
     * Replace placeholders in template with actual values
     */
    public static function formatMessage($type, $data)
    {
        $template = self::getTemplate($type);
        if (!$template) {
            return '';
        }

        // Replace all placeholders
        foreach ($data as $key => $value) {
            $template = str_replace('{' . $key . '}', $value ?? '', $template);
        }

        return $template;
    }
}
