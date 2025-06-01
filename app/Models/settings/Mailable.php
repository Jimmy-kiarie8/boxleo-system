<?php

namespace App\Models\settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mailable extends Model
{
    use HasFactory;

    protected $fillable = ['name','subject','recipient','model','additional_recipients','attach_file','template', 'user_id'];

    public function setRecipientAttribute($value)
    {
        $this->attributes['recipient'] = serialize($value);
    }

    public function getRecipientAttribute($value)
    {
        return unserialize($value);
    }
}
