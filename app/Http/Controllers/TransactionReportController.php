use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionReportController extends Controller
{
    // Halaman laporan
    public function index(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;

        $transactions = Transaction::with('queue.patient')
            ->when($start && $end, function ($q) use ($start, $end) {
                $q->whereBetween('transaction_date', [$start, $end]);
            })
            ->orderBy('transaction_date', 'desc')
            ->get();

        return view('reports.transactions', compact('transactions'));
    }

    // Export PDF
    public function exportPdf(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;

        $transactions = Transaction::with('queue.patient')
            ->when($start && $end, function ($q) use ($start, $end) {
                $q->whereBetween('transaction_date', [$start, $end]);
            })
            ->get();

        $pdf = Pdf::loadView('reports.transactions_pdf', compact('transactions'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('laporan-transaksi-griya-sehat.pdf');
    }
}
