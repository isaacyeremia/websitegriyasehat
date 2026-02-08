use App\Models\Queue;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    // Form input rekam medis
    public function create($queueId)
    {
        $queue = Queue::with('patient')->findOrFail($queueId);
        return view('medical_records.create', compact('queue'));
    }

    // Simpan rekam medis
    public function store(Request $request, $queueId)
    {
        $request->validate([
            'complaint' => 'required',
            'diagnosis' => 'required',
            'treatment' => 'required',
        ]);

        MedicalRecord::create([
            'queue_id' => $queueId,
            'complaint' => $request->complaint,
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
            'medicine' => $request->medicine,
            'doctor_note' => $request->doctor_note,
            'checkup_date' => now()
        ]);

        return redirect('/admin/antrian')
            ->with('success', 'Rekam medis berhasil disimpan');
    }

    // Riwayat rekam medis pasien
    public function history($patientId)
    {
        $records = MedicalRecord::whereHas('queue', function ($q) use ($patientId) {
            $q->where('patient_id', $patientId);
        })->with('queue')->orderBy('checkup_date', 'desc')->get();

        return view('medical_records.history', compact('records'));
    }
}
