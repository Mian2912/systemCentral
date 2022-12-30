<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cite extends Model
{
    use HasFactory;

    protected $fillable = [
	'name',
	'lastname',
	'type_document',
	'document',
	'phone',
	'eps',
	'id_speciality',
	'id_status',
	'id_user'
    ];
    
    public function speciality(){
	return $this->belongsTo('App\Models\Speciality.php', 'id');
    }
}
