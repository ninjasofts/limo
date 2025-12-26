class VehicleType extends Model
{
    protected $fillable = ['name', 'slug', 'active'];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
