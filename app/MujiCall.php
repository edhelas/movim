<?php

namespace App;

use Awobaz\Compoships\Database\Eloquent\Model;

class MujiCall extends Model
{
    use \Awobaz\Compoships\Compoships;

    public $incrementing = false;
    protected $primaryKey = ['session_id', 'id'];
    protected $fillable = ['id', 'muc', 'jidfrom', 'video', 'isfromconference'];
    protected $with = ['participants', 'presences'];

    protected $attributes = [
        'session_id'    => SESSION_ID
    ];

    public function session()
    {
        return $this->hasOne('App\Session');
    }

    public function conference()
    {
        return $this->hasOne('App\Conference', 'conference', 'jidfrom')
            ->where('session_id', $this->session_id);
    }

    public function presences()
    {
        return $this->hasMany('App\Presence', 'jid', 'muc')
            ->where('session_id', $this->session_id)
            ->where('value', '<', '5')
            ->where('resource', '!=', '');
    }

    public function presence()
    {
        return $this->hasOne('App\Presence', ['jid', 'session_id'], ['muc', 'session_id'])
                    ->where('value', '<', 5)
                    ->where('mucjid', \App\User::me()->id);
    }

    public function participants()
    {
        return $this->hasMany('App\MujiCallParticipant', 'muji_call_id', 'id')
            ->where('session_id', $this->session_id);
    }

    public function getIconAttribute()
    {
        return $this->video ? 'videocam' : 'call';
    }

    public function getendIconAttribute()
    {
        return $this->video ? 'videocam_off' : 'call_end';
    }

    /*public function presences()
    {
        return $this->hasMany('App\Presence', 'jid', 'conference')
                    ->where('session_id', $this->session_id)
                    ->where('resource', '!=', '')
                    ->where('value', '<', 5)
                    ->orderBy('mucrole')
                    ->orderBy('mucaffiliation', 'desc')
                    ->orderBy('value')
                    ->orderBy('resource');
    }*/
}
