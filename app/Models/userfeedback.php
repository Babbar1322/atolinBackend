<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\userfeedback
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $feedback
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\userfeedbackFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|userfeedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|userfeedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|userfeedback query()
 * @method static \Illuminate\Database\Eloquent\Builder|userfeedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|userfeedback whereFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|userfeedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|userfeedback whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|userfeedback whereUserId($value)
 * @mixin \Eloquent
 */
class userfeedback extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'feedback',

    ];
}
