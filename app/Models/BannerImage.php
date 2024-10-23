<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BannerImage
 *
 * @property int $id
 * @property string $image
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BannerImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BannerImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BannerImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|BannerImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannerImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannerImage whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannerImage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannerImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BannerImage extends Model
{
    use HasFactory;

    protected $fillable = ['banner_image,status'];
}
