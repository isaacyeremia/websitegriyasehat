class Transaction extends Model
{
    protected $fillable = [
        'queue_id',
        'service_name',
        'amount',
        'payment_status',
        'transaction_date'
    ];

    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }
}
