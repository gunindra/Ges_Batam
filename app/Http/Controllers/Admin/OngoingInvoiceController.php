<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OngoingInvoiceExport;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Str;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;


class OngoingInvoiceController extends Controller
{
    public function index()
    {
        $listDo = DB::table('tbl_pengantaran')
        ->join('tbl_pengantaran_detail', 'tbl_pengantaran.id', '=', 'tbl_pengantaran_detail.pengantaran_id')
        ->join('tbl_invoice', 'tbl_pengantaran_detail.invoice_id', '=', 'tbl_invoice.id')
        ->join('tbl_resi', 'tbl_resi.invoice_id', '=', 'tbl_invoice.id')
        ->join('tbl_status', 'tbl_invoice.status_id', '=', 'tbl_status.id')
        ->whereIn('tbl_status.id', [1, 4])
        ->select('tbl_resi.no_do')
        ->distinct()
        ->get();

    $listCustomer = DB::table('tbl_pengantaran')
        ->join('tbl_pengantaran_detail', 'tbl_pengantaran.id', '=', 'tbl_pengantaran_detail.pengantaran_id')
        ->join('tbl_invoice', 'tbl_pengantaran_detail.invoice_id', '=', 'tbl_invoice.id')
        ->join('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id')
        ->join('tbl_status', 'tbl_invoice.status_id', '=', 'tbl_status.id')
        ->whereIn('tbl_status.id', [1, 4])
        ->select('tbl_pembeli.nama_pembeli')
        ->distinct()
        ->get();
        return view('Report.OngoingInvoice.indexongoinginvoice', [
            'listCustomer' => $listCustomer,
            'listDo' => $listDo,
        ]);
    }


    public function getlistOngoing(Request $request)
    {
        $NoDo = $request->no_do;
        $Customer = $request->nama_pembeli;
        $query = DB::table('tbl_pengantaran')
            ->select(
                'tbl_invoice.no_invoice',
                'tbl_resi.no_do',
                'tbl_supir.nama_supir',
                DB::raw("DATE_FORMAT(tbl_pengantaran.tanggal_pengantaran, '%d %M %Y') AS tanggal_pengantaran"),
                DB::raw("DATE_FORMAT(tbl_invoice.tanggal_buat, '%d %M %Y') AS tanggal_buat"),
                'tbl_invoice.alamat',
                'tbl_pembeli.nama_pembeli AS nama_pembeli',
                'tbl_status.status_name AS status_transaksi'
            )
            ->leftJoin('tbl_supir', 'tbl_pengantaran.supir_id', '=', 'tbl_supir.id')
            ->join('tbl_pengantaran_detail', 'tbl_pengantaran.id', '=', 'tbl_pengantaran_detail.pengantaran_id')
            ->join('tbl_invoice', 'tbl_pengantaran_detail.invoice_id', '=', 'tbl_invoice.id')
            ->join('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id')
            ->join('tbl_status', 'tbl_invoice.status_id', '=', 'tbl_status.id')
            ->join('tbl_resi', 'tbl_resi.invoice_id', '=', 'tbl_invoice.id')
            ->whereIn('tbl_status.id', [1, 4]);

        if ($Customer) {
            $query->where('tbl_pembeli.nama_pembeli', 'LIKE', $Customer);
        }

        if ($NoDo) {
            $query->where('tbl_resi.no_do', 'LIKE', $NoDo);
        }


        $query->orderBy('tbl_pengantaran.id', 'desc');

        $data = $query->get();

        return DataTables::of($data)
            ->editColumn('status_transaksi', function ($row) {
                $statusBadgeClass = '';
                switch ($row->status_transaksi) {
                    case 'Dalam Perjalanan':
                        $statusBadgeClass = 'badge-success';
                        break;
                    case 'Batam / Sortir':
                        $statusBadgeClass = 'badge-primary';
                        break;
                    case 'Delivering':
                        $statusBadgeClass = 'badge-success';
                        break;
                    case 'Ready For Pickup':
                        $statusBadgeClass = 'badge-warning';
                        break;
                    default:
                        $statusBadgeClass = 'badge-secondary';
                        break;
                }

                return '<span class="badge ' . $statusBadgeClass . '">' . $row->status_transaksi . '</span>';
            })
            // ->addColumn('action', function ($row) {
            //     return '<a href="#" class="btn btnUpdateTracking btn-sm btn-secondary" data-id="' . $row->no_invoice . '"><i class="fas fa-edit"></i></a>' .
            //         '<a href="#" class="btn btnDestroyTracking btn-sm btn-danger ml-2" data-id="' . $row->no_invoice . '"><i class="fas fa-trash"></i></a>';
            // })
            ->rawColumns(['status_transaksi', 'action'])
            ->make(true);
    }
    public function export(Request $request)
    {
        $NoDo = $request->no_do;
        $Customer = $request->nama_pembeli;

        return Excel::download(new OngoingInvoiceExport($NoDo, $Customer), 'OngoingInvoice.xlsx');
    }
    public function exportOngoingPdf(Request $request)
    {
        $NoDo = $request->no_do;
        $Customer = $request->nama_pembeli;

        try {
            $query = DB::table('tbl_pengantaran')
                ->select(
                    'tbl_invoice.no_invoice',
                    'tbl_resi.no_do',
                    'tbl_supir.nama_supir',
                    DB::raw("DATE_FORMAT(tbl_pengantaran.tanggal_pengantaran, '%d %M %Y') AS tanggal_pengantaran"),
                    DB::raw("DATE_FORMAT(tbl_invoice.tanggal_buat, '%d %M %Y') AS tanggal_buat"),
                    'tbl_invoice.alamat',
                    'tbl_pembeli.nama_pembeli AS nama_pembeli',
                    'tbl_status.status_name AS status_transaksi'
                )
                ->leftJoin('tbl_supir', 'tbl_pengantaran.supir_id', '=', 'tbl_supir.id')
                ->join('tbl_pengantaran_detail', 'tbl_pengantaran.id', '=', 'tbl_pengantaran_detail.pengantaran_id')
                ->join('tbl_invoice', 'tbl_pengantaran_detail.invoice_id', '=', 'tbl_invoice.id')
                ->join('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id')
                ->join('tbl_status', 'tbl_invoice.status_id', '=', 'tbl_status.id')
                ->join('tbl_resi', 'tbl_resi.invoice_id', '=', 'tbl_invoice.id')
                ->whereIn('tbl_status.id', [1, 4]);

            if ($Customer) {
                $query->where('tbl_pembeli.nama_pembeli', 'LIKE', $Customer);
            }

            if ($NoDo) {
                $query->where('tbl_resi.no_do', 'LIKE', $NoDo);
            }

            $query->orderBy('tbl_pengantaran.id', 'desc');
            $ongoingData = $query->get();

            if ($ongoingData->isEmpty()) {
                return response()->json(['error' => 'No ongoing invoices found'], 404);
            }

            try {
                $pdf = pdf::loadView('exportPDF.ongoinginvoicepdf', [
                    'ongoingData' => $ongoingData,
                    'NoDo' => $NoDo,
                    'Customer' => $Customer,


                ])
                    ->setPaper('A4', 'portrait')
                    ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                    ->setWarnings(false);
            } catch (\Exception $e) {
                Log::error('Error generating ongoing invoice PDF: ' . $e->getMessage(), ['exception' => $e]);
                return response()->json(['error' => 'Failed to generate PDF'], 500);
            }
            try {
                $folderPath = storage_path('app/public/ongoinginvoice');

                // Cek apakah folder sudah ada, jika belum maka buat folder
                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0777, true); // 0777 memberikan izin penuh untuk folder dan subfolder
                }

                $fileName = 'ongoing_invoices_' . (string) Str::uuid() . '.pdf';
                $filePath = $folderPath . '/' . $fileName;

                // Save the PDF
                $pdf->save($filePath);
            } catch (\Exception $e) {
                Log::error('Error saving PDF: ' . $e->getMessage(), ['exception' => $e]);
                return response()->json(['error' => 'Failed to save PDF'], 500);
            }
            // Return the URL of the saved PDF
            $url = asset('storage/ongoinginvoice/' . $fileName);
            return response()->json(['url' => $url]);

        } catch (\Exception $e) {
            Log::error('Error generating ongoing invoice PDF: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'An error occurred while generating the ongoing invoice PDF'], 500);
        }
    }


}
