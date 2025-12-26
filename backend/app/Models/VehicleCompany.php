class VehicleCompany extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'address', 'active'];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
