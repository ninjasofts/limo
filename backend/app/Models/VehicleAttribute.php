class VehicleAttribute extends Model
{
    protected $fillable = ['name', 'type', 'options'];

    protected $casts = [
        'options' => 'array',
    ];
}
