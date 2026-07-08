<?php

namespace App\Models\MasterData;
use App\Models\MasterData\Master;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string|null $nama_lembaga
 * @property string|null $alamat_lembaga
 * @property string|null $wa_url
 * @property string|null $instance_id
 * @property string|null $wa_token
 * @property string|null $api_key
 * @property string|null $_api_key
 * @property string|null $logo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
use App\Traits\Auditable;

class Master extends Model
{
    use Auditable;

    protected $fillable = [
        'nama_lembaga',
        'alamat_lembaga',
        'wa_url',
        'instance_id',
        'wa_token',
        'api_key',
        'logo',
        'hero_title',
        'hero_subtitle',
        'hero_image',
        'hero_overlay_opacity',
        'tentang_kami',
        'wa_number',
        'wa_widget_status',
        'wa_widget_message',
        'instagram_url',
        'landing_sections_visibility',
        'stats_siswa',
        'stats_tutor',
        'stats_modul',
        'stats_kepuasan',
        'dp_persen_minimal', 
        'hero_cta_text',
        'hero_cta_link',
        'about_us',
        'facebook_url',
        'youtube_url',
        'tiktok_url',
        'whatsapp_number',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'cloud_backup_provider',
        'gdrive_client_id',
        'gdrive_client_secret',
        'gdrive_refresh_token',
        'gdrive_folder_id',
    ];

    protected $casts = [
        'landing_sections_visibility' => 'array',
        'wa_token'                    => 'encrypted',
        'api_key'                     => 'encrypted',
        'mail_password'               => 'encrypted',
        'gdrive_client_secret'        => 'encrypted',
        'gdrive_refresh_token'        => 'encrypted',
    ];
}


