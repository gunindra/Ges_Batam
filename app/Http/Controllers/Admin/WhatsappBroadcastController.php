<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use App\Models\WhatsappBroadcast;
use App\Models\WhatsappBroadcastDetail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Jobs\sendWABroadcastJob;
use App\Jobs\sendWABroadcastDetailJob;
use Yajra\DataTables\Facades\DataTables;

class WhatsappBroadcastController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $query = WhatsappBroadcast::select('id', 'message', 'status', 'created_at')
                                        ->withCount('recipients'); // Add recipients count

            return DataTables::of($query)
                ->editColumn('status', function ($item) {
                    $statusBadgeClass = match ($item->status) {
                        'pending' => 'badge-info',
                        'in queue' => 'badge-warning',
                        'sent' => 'badge-success',
                        'failed' => 'badge-danger',
                        default => 'badge-secondary',
                    };
                    return '<span class="badge ' . $statusBadgeClass . '">' . $item->status . '</span>';
                })
                ->editColumn('created_at', function ($item) {
                    // Format created_at as yyyy-mm-dd HH:mm:ss
                    return $item->created_at->format('Y-m-d H:i:s');
                })
                ->addColumn('recipients', function ($item) {
                    // Display the recipients count
                    return $item->recipients_count;
                })
                ->addColumn('action', function ($item) {
                    return '
                        <button class="btn btn-primary btn-resend" data-id="' . $item->id . '">Resend</button>
                        <a href="' . route('wa.broadcast.edit', $item->id) . '" class="btn btn-success">View</a>
                    ';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
                

        }
        return view('content.broadcast.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $listPembeli = DB::select("SELECT id, nama_pembeli, marking FROM tbl_pembeli");
        return view('content.broadcast.new', compact('listPembeli'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Step 1: Validate input
            $validatedData = $request->validate([
                'message' => 'required|string',
                'media' => 'nullable|file|max:4096', // Max 4 MB
                'name.*' => 'required|string',
                'phone.*' => 'required|string|regex:/^62[0-9]{9,13}$/',
            ]);

            // Step 2: Handle media upload
            $mediaPath = null;
            if ($request->hasFile('media')) {
                $mediaPath = $request->file('media')->store('broadcast_media', 'public');
            }

            // Step 3: Create a WhatsAppBroadcast record
            $broadcast = WhatsAppBroadcast::create([
                'message' => $validatedData['message'],
                'status' => 'pending',
                'media_path' => $mediaPath,
                'user_id' => auth()->id(),
            ]);

            // Step 4: Prepare recipients data
            $recipients = [];
            if ($request->has('send_to_all')) {
                // Retrieve all active customers (assuming you have a Customer model)
                $allCustomers = Customer::where('status', 1)->get(['nama_pembeli', 'no_wa']);
                foreach ($allCustomers as $customer) {
                    $recipients[] = [
                        'whatsapp_broadcast_id' => $broadcast->id,
                        'recipient' => $customer->nama_pembeli,
                        'phone' => $customer->no_wa,
                        'send_time' => now(),
                        'status' => 'pending',
                        'send_response' => '',
                    ];
                }
            }

            // Step 4.1: Prepare recipients data based on table list
            foreach ($request->phone as $index => $phone) {
                $recipients[] = [
                    'whatsapp_broadcast_id' => $broadcast->id,
                    'recipient' => $request->name[$index],
                    'phone' => $phone,
                    'send_time' => now(),
                    'status' => 'pending',
                    'send_response' => '',
                ];
            }

            // Step 5: Save recipients to WhatsAppBroadcastDetail
            WhatsappBroadcastDetail::insert($recipients);

            // Commit the transaction if everything is successful
            DB::commit();

            // Step 6: Dispatch the job to send messages asynchronously
            sendWABroadcastJob::dispatch($broadcast->id);

            return redirect()->back()->with('success', 'Broadcast created successfully!');
        } catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->back()->withErrors(['error' => 'Broadcast creation failed.']);
        }
    }

    public function resend(Request $request)
    {
        $broadcastId = $request->id;
        $type = $request->type;
        try {
            if($type == 'broadcast'){
                $broadcast = WhatsappBroadcast::findOrFail($broadcastId);
                sendWABroadcastJob::dispatch($broadcast->id);
            }else{
                $broadcastDetail = WhatsappBroadcastDetail::findOrFail($broadcastId);
                $broadcast = $broadcastDetail->whatsapp_broadcast_id;
                SendWABroadcastDetailJob::dispatch($broadcastDetail->id, $broadcast);
            }
            return response()->json(['message' => 'Broadcast resent successfully.']);
        } catch (\Exception $e) {

            return response()->json(['message' => 'Failed to resend broadcast.' . $e], 500);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $broadcast = WhatsappBroadcast::with('recipients')->findOrFail($id);
        return view('content.broadcast.edit', compact('broadcast'));
    }

}
