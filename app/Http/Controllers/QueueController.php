class QueueController extends Controller
{
    // Ambil antrian
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required'
        ]);

        $patient = Patient::create([
            'name' => $request->name,
            'phone' => $request->phone
        ]);

        $lastQueue = Queue::whereDate('queue_date', now())
            ->orderBy('queue_number', 'desc')
            ->first();

        $nextNumber = $lastQueue ? $lastQueue->queue_number + 1 : 1;

        $queue = Queue::create([
            'patient_id' => $patient->id,
            'queue_number' => $nextNumber,
            'queue_date' => now(),
        ]);

        return response()->json([
            'message' => 'Antrian berhasil dibuat',
            'queue_number' => $queue->queue_number
        ]);
    }

    // Admin melihat antrian hari ini
    public function index()
    {
        return Queue::with('patient')
            ->whereDate('queue_date', now())
            ->orderBy('queue_number')
            ->get();
    }

    // Panggil antrian
    public function call($id)
    {
        $queue = Queue::findOrFail($id);
        $queue->update(['status' => 'called']);

        return response()->json(['message' => 'Antrian dipanggil']);
    }

    // Selesaikan antrian
    public function done($id)
    {
        $queue = Queue::findOrFail($id);
        $queue->update(['status' => 'done']);

        return response()->json(['message' => 'Antrian selesai']);
    }
}
