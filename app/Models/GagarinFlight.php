<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GagarinFlight extends Model
{
    protected $table = 'gagarin_info';
    protected $primaryKey = 'id_info';
    protected $fillable = [
        'mission_name',
        'launch_date',
        'launch_site_name',
        'launch_site_latitude',
        'launch_site_longtitude',
        'duration_hours',
        'duration_minutes',
        'spacecraft_name',
        'spacecraft_manufacturer',
        'spacecraft_crew_capacity',
        'landing_site_name',
        'landing_site_country',
        'landing_site_latitude',
        'landing_site_longtitude',
        'details_parachute',
        'details_valocity',
        'cosmonaut_name',
        'cosmonaut_birthdate',
        'cosmonaut_rank',
        'cosmonaut_bio_early',
        'cosmonaut_bio_career',
        'cosmonaut_bio_post'
    ];
}
