<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StripeBankDetails
 *
 * @property int $id
 * @property string|null $bank_id
 * @property string|null $priority_id
 * @property string|null $bank_name
 * @property string|null $fingerprint
 * @property string|null $last4
 * @property string|null $country
 * @property string|null $routing_number
 * @property string|null $currency
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails query()
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails whereBankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails whereFingerprint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails whereLast4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails wherePriorityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails whereRoutingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StripeBankDetails extends Model
{
    use HasFactory;

    protected $table = "user_stripe_bank_details";
}
