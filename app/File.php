<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public function from_user () {
    	return $this->belongsTo('App\User', 'from', 'id');
    }

    public function to_user () {
    	return $this->belongsTo('App\User', 'to', 'id');
    }

    public function to_company () {
    	return $this->belongsTo('App\Company', 'company_id', 'id');
    }
}
