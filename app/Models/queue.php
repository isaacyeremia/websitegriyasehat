class Queue extends Model
{
    protected $fillable = [
        'patient_id',
        'queue_number',
        'status',
        'queue_date'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
public function transaction()
{
    return $this->hasOne(Transaction::class);
}

