<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class UpdateRecipients
 * @package App\Models
 */
class UpdateRecipients extends Model
{
    /**
     * @var string
     */
    protected $table = "update_recipients";

    /**
     * @var array
     */
    protected $fillable = [
        'email', 'participate',
    ];

    /**
     * Find Update by email
     *
     * @param $query
     * @param $email
     * @return mixed
     */
    public function scopeFindUpdateByEmail($query, $email)
    {
        return $query->where('email', $email)->first();
    }

    /**
     * Get the participate attribute
     *
     * @param $value
     * @return mixed
     */
    public function getParticipateAttribute($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * set the participate attribute to boolean
     *
     * @param $value
     */
    public function setParticipateAttribute($value)
    {
        $this->attributes['participate'] = $this->getParticipateAttribute($value);
    }

}
