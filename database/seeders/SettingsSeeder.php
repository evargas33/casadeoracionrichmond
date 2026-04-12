<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // Identidad
            'church_name'        => 'Casa de Oración',
            'church_tagline'     => 'Iglesia Evangélica · Bay Area',
            'church_description' => 'Comunidad hispana de fe, adoración y servicio. Te esperamos con los brazos abiertos.',
            'church_founded'     => '2009',
            'pastor_name'        => 'Pastor José Hernández',
            'pastor_title'       => 'Pastor Principal',

            // Contacto
            'church_address'     => '1245 Mission Blvd, Suite 4',
            'church_city'        => 'San Francisco, CA 94110',
            'church_phone'       => '(415) 555-0192',
            'church_email'       => 'info@casadeoracion.org',
            'church_maps_url'    => 'https://maps.google.com',

            // Horarios
            'schedule_sunday'    => '10:00 am',
            'schedule_saturday'  => '7:00 pm',
            'schedule_friday'    => '7:30 pm',

            // Redes sociales
            'social_facebook'    => '',
            'social_instagram'   => '',
            'social_youtube'     => '',

            // SEO / Meta
            'meta_title'         => 'Casa de Oración — Iglesia Evangélica',
            'meta_description'   => 'Iglesia evangélica hispana en el Área de la Bahía. Únete a nuestra comunidad de fe.',
        ];

        Setting::setMany($defaults);
    }
}
