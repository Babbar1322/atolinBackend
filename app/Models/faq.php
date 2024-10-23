<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\faq
 *
 * @property int $id
 * @property string|null $question
 * @property string|null $answer
 * @property int $published
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|faq newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|faq newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|faq query()
 * @method static \Illuminate\Database\Eloquent\Builder|faq whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|faq whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|faq whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|faq wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|faq whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|faq whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class faq extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question',
        'answer',
        'published',
    ];
}
