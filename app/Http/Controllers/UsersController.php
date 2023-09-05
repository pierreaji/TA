<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\UserSales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegister;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            // 'users' => User::where('role', '!=', 'Admin')->get(),
            'users' => User::with('Sales')->where('role', '!=', 'Admin')->get(),
        ];
        return view('master.users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $text = ($request->id == null) ? 'create' : 'update';
        DB::beginTransaction();
        try {
            $user = User::find($request->id);
            if (!$user) {
                $user = new User();
            }
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = $request->role;
            $user->save();

            if ($user->role == 'Sales') {
                $type = UserSales::where('id_user', $user->id)->first();
                if (!$type) $type = new UserSales();
                $type->id_user = $user->id;
                $type->type = $request->type;
                $type->nik = $request->nik;
                $type->number = $request->number;
                $type->address = $request->address;

                if ($request->skck != null) {
                    $file = $request->skck;
                    $extension = $file->getClientOriginalExtension();
                    if (!in_array($extension, ['jpg', 'pdf', 'png'])) {
                        self::failed("SKCK Format must JPG, PNG, PDF!");
                        return redirect()->back();
                    }
                    $name = 'document_' . time();
                    $filename = $name . '.' . $extension;
                    $path = Storage::putFileAs('public/document', $file, $filename);
                    $type->skck = 'storage/document/' . $filename;
                }
    
                if ($request->ktp != null) {
                    $file = $request->ktp;
                    $extension = $file->getClientOriginalExtension();
                    if (!in_array($extension, ['jpg', 'pdf', 'png'])) {
                        self::failed("KTP Format must JPG, PNG, PDF!");
                        return redirect()->back();
                    }
                    $name = 'document_' . time();
                    $filename = $name . '.' . $extension;
                    $path = Storage::putFileAs('public/document', $file, $filename);
                    $type->ktp = 'storage/document/' . $filename;
                }
    
                if ($request->sim != null) {
                    $file = $request->sim;
                    $extension = $file->getClientOriginalExtension();
                    if (!in_array($extension, ['jpg', 'pdf', 'png'])) {
                        self::failed("SIM Format must JPG, PNG, PDF!");
                        return redirect()->back();
                    }
                    $name = 'document_' . time();
                    $filename = $name . '.' . $extension;
                    $path = Storage::putFileAs('public/document', $file, $filename);
                    $type->sim = 'storage/document/' . $filename;
                }
    
                if ($request->stnk != null) {
                    $file = $request->stnk;
                    $extension = $file->getClientOriginalExtension();
                    if (!in_array($extension, ['jpg', 'pdf', 'png'])) {
                        self::failed("STNK Format must JPG, PNG, PDF!");
                        return redirect()->back();
                    }
                    $name = 'document_' . time();
                    $filename = $name . '.' . $extension;
                    $path = Storage::putFileAs('public/document', $file, $filename);
                    $type->stnk = 'storage/document/' . $filename;
                }
    
                if ($request->bpkb != null) {
                    $file = $request->bpkb;
                    $extension = $file->getClientOriginalExtension();
                    if (!in_array($extension, ['jpg', 'pdf', 'png'])) {
                        self::failed("BPKB Format must JPG, PNG, PDF!");
                        return redirect()->back();
                    }
                    $name = 'document_' . time();
                    $filename = $name . '.' . $extension;
                    $path = Storage::putFileAs('public/document', $file, $filename);
                    $type->bpkb = 'storage/document/' . $filename;
                }
    
                if ($request->pas_foto != null) {
                    $file = $request->pas_foto;
                    $extension = $file->getClientOriginalExtension();
                    if (!in_array($extension, ['jpg', 'pdf', 'png'])) {
                        self::failed("PAS FOTO Format must JPG, PNG, PDF!");
                        return redirect()->back();
                    }
                    $name = 'document_' . time();
                    $filename = $name . '.' . $extension;
                    $path = Storage::putFileAs('public/document', $file, $filename);
                    $type->pas_foto = 'storage/document/' . $filename;
                }
    
                if ($request->sertifikat != null) {
                    $file = $request->sertifikat;
                    $extension = $file->getClientOriginalExtension();
                    if (!in_array($extension, ['jpg', 'pdf', 'png'])) {
                        self::failed("SERTIFIKAT Format must JPG, PNG, PDF!");
                        return redirect()->back();
                    }
                    $name = 'document_' . time();
                    $filename = $name . '.' . $extension;
                    $path = Storage::putFileAs('public/document', $file, $filename);
                    $type->sertifikat = 'storage/document/' . $filename;
                }
    
                if ($request->agreement != null) {
                    $file = $request->agreement;
                    $extension = $file->getClientOriginalExtension();
                    if (!in_array($extension, ['jpg', 'pdf', 'png'])) {
                        self::failed("AGREEMENT Format must JPG, PNG, PDF!");
                        return redirect()->back();
                    }
                    $name = 'document_' . time();
                    $filename = $name . '.' . $extension;
                    $path = Storage::putFileAs('public/document', $file, $filename);
                    $type->agreement = 'storage/document/' . $filename;
                }
                if (Auth::user()->role == 'Admin') {
                    $type->approved_status = 1;
                    $type->is_renew = false;
                }

                $type->save();
            }

            DB::commit();

            if ($request->id == null) {
                Mail::to($user->email)->send(new UserRegister($request));
            }

            self::success("User {$text}d successfully!");

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            self::failed("Failed to $text user! " . $e->getMessage());

            return redirect()->back();
        }
    }

    public function sales(Request $request, string $id)
    {
        if (isset($request->notif) && $request->notif != null) {
            Notification::where('id', $request->notif)->update([
                'is_read' => true
            ]);
        }
        $user = User::findOrFail($id);
        if (Auth::user()->role == 'Sales') {
            $user = Auth::user();
        }
        $data = [
            'user' => $user,
        ];
        return view('master.users.sales', $data);
    }

    public function salesStore(Request $request, string $id)
    {


        $text = 'update';
        DB::beginTransaction();
        try {
            $user = UserSales::where('id_user', $id)->firstOrFail();

            if ($request->renew) {
                $user->is_renew = true;
                $user->type = 'Car';
                $user->skck = null;
                $user->ktp = null;
                $user->sim = null;
                $user->stnk = null;
                $user->pas_foto = null;
                $user->sertifikat = null;
                $user->save();
                Notification::create([
                    'title' => 'Renew Document',
                    'text' => "You must renew document",
                    'id_user' => $id
                ]);

                DB::commit();

                self::success("User renew successfully!");
                return redirect()->back();
            }
            if (Auth::user()->role == 'Sales') {
                $user = UserSales::where('id_user', Auth::user()->id)->firstOrFail();
            }

            $user->nik = $request->nik;
            $user->number = $request->number;
            $user->address = $request->address;

            if ($request->skck != null) {
                $file = $request->skck;
                $extension = $file->getClientOriginalExtension();
                if (!in_array($extension, ['jpg', 'pdf', 'png'])) {
                    self::failed("SKCK Format must JPG, PNG, PDF!");
                    return redirect()->back();
                }
                $name = 'document_' . time();
                $filename = $name . '.' . $extension;
                $path = Storage::putFileAs('public/document', $file, $filename);
                $user->skck = 'storage/document/' . $filename;
            }

            if ($request->ktp != null) {
                $file = $request->ktp;
                $extension = $file->getClientOriginalExtension();
                if (!in_array($extension, ['jpg', 'pdf', 'png'])) {
                    self::failed("KTP Format must JPG, PNG, PDF!");
                    return redirect()->back();
                }
                $name = 'document_' . time();
                $filename = $name . '.' . $extension;
                $path = Storage::putFileAs('public/document', $file, $filename);
                $user->ktp = 'storage/document/' . $filename;
            }

            if ($request->sim != null) {
                $file = $request->sim;
                $extension = $file->getClientOriginalExtension();
                if (!in_array($extension, ['jpg', 'pdf', 'png'])) {
                    self::failed("SIM Format must JPG, PNG, PDF!");
                    return redirect()->back();
                }
                $name = 'document_' . time();
                $filename = $name . '.' . $extension;
                $path = Storage::putFileAs('public/document', $file, $filename);
                $user->sim = 'storage/document/' . $filename;
            }

            if ($request->stnk != null) {
                $file = $request->stnk;
                $extension = $file->getClientOriginalExtension();
                if (!in_array($extension, ['jpg', 'pdf', 'png'])) {
                    self::failed("STNK Format must JPG, PNG, PDF!");
                    return redirect()->back();
                }
                $name = 'document_' . time();
                $filename = $name . '.' . $extension;
                $path = Storage::putFileAs('public/document', $file, $filename);
                $user->stnk = 'storage/document/' . $filename;
            }

            if ($request->bpkb != null) {
                $file = $request->bpkb;
                $extension = $file->getClientOriginalExtension();
                if (!in_array($extension, ['jpg', 'pdf', 'png'])) {
                    self::failed("BPKB Format must JPG, PNG, PDF!");
                    return redirect()->back();
                }
                $name = 'document_' . time();
                $filename = $name . '.' . $extension;
                $path = Storage::putFileAs('public/document', $file, $filename);
                $user->bpkb = 'storage/document/' . $filename;
            }

            if ($request->pas_foto != null) {
                $file = $request->pas_foto;
                $extension = $file->getClientOriginalExtension();
                if (!in_array($extension, ['jpg', 'pdf', 'png'])) {
                    self::failed("PAS FOTO Format must JPG, PNG, PDF!");
                    return redirect()->back();
                }
                $name = 'document_' . time();
                $filename = $name . '.' . $extension;
                $path = Storage::putFileAs('public/document', $file, $filename);
                $user->pas_foto = 'storage/document/' . $filename;
            }

            if ($request->sertifikat != null) {
                $file = $request->sertifikat;
                $extension = $file->getClientOriginalExtension();
                if (!in_array($extension, ['jpg', 'pdf', 'png'])) {
                    self::failed("SERTIFIKAT Format must JPG, PNG, PDF!");
                    return redirect()->back();
                }
                $name = 'document_' . time();
                $filename = $name . '.' . $extension;
                $path = Storage::putFileAs('public/document', $file, $filename);
                $user->sertifikat = 'storage/document/' . $filename;
            }

            if ($request->agreement != null) {
                $file = $request->agreement;
                $extension = $file->getClientOriginalExtension();
                if (!in_array($extension, ['jpg', 'pdf', 'png'])) {
                    self::failed("AGREEMENT Format must JPG, PNG, PDF!");
                    return redirect()->back();
                }
                $name = 'document_' . time();
                $filename = $name . '.' . $extension;
                $path = Storage::putFileAs('public/document', $file, $filename);
                $user->agreement = 'storage/document/' . $filename;
            }

            if (Auth::user()->role == 'Sales') {
                $user->approved_status = 0;
                $user->is_renew = false;
            }
            if (Auth::user()->role == 'Admin') {
                $user->approved_status = 1;
                $user->is_renew = false;
            }
            

            $user->save();


            Notification::create([
                'title' => 'New Document from ' . Auth::user()->name,
                'text' => "You have documents that need to be reviewed",
                'id_user' => 0,
                'target' => Auth::user()->id
            ]);

            DB::commit();

            self::success("User {$text}d successfully!");

            if (Auth::user()->role == 'Sales') {
                return redirect()->to(url(''));
            }
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            self::failed("Failed to $text user! " . $e->getMessage());

            return redirect()->back();
        }
    }

    public function salesConfirm(Request $request, string $id)
    {

        $text = 'approve';
        DB::beginTransaction();
        try {
            $user = UserSales::where('id_user', $id)->firstOrFail();
            if (Auth::user()->role == 'Sales') {
                $user = UserSales::where('id_user', Auth::user()->id)->firstOrFail();
            }

            $user->approved_status = $request->status;
            $user->reason = $request->reason;

            $user->save();

            Notification::create([
                'title' => 'Document approval',
                'text' => $request->status == 1 ? "Your document is received" : "Your document is rejected",
                'id_user' => $id,
            ]);

            DB::commit();

            self::success("User {$text}d successfully!");

            if (Auth::user()->role == 'Sales') {
                return redirect()->to(url(''));
            }
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            self::failed("Failed to $text user! " . $e->getMessage());

            return redirect()->back();
        }
    }

    public function generateWord($id)
    {
        $word_template_location = dirname(__FILE__) . '/Template.docx';
        // dd($word_template_location);
        //instantiate template word
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        $word_processor = new TemplateProcessor($word_template_location);


        //set value on variable on template
        $admin = User::where('role', 'Admin')->first();
        $sales = User::find($id);
        $nmber = 'PK/' . date('m') . '/00' . $sales->id;
        $temp = [];
        $tempDayName = date('l');
        if($tempDayName == 'Sunday'){
		    $temp['day'] = 'Minggu';
		} else if($tempDayName == 'Monday'){
		    $temp['day'] = 'Senin';
		} else if($tempDayName == 'Tuesday'){
		    $temp['day'] = 'Selasa';
		} else if($tempDayName == 'Wednesday'){
		    $temp['day'] = 'Rabu';
		} else if($tempDayName == 'Thursday'){
		    $temp['day'] = 'Kamis';
		} else if($tempDayName == 'Friday'){
		    $temp['day'] = 'Jumat';
		} else if($tempDayName == 'Saturday'){
		    $temp['day'] = 'Sabtu';
		}
        $word_processor->setValues([
            'day' => $temp['day'],
            'date' => date('d-m-Y'),
            'nomer_system' => $nmber,
            'nik_sales' => $sales?->Sales?->nik,
            'address_sales' => $sales?->Sales?->address,
            'number_sales' => "0{$sales?->Sales?->number}",
            'name_sales' => $sales?->name,
            'email_sales' => $sales?->email,
        ]);

        $nmber = str_replace('/', '-', $nmber);

        //save word result
        $pathToSave = storage_path('app/public/') . "$nmber.docx";
        $word_processor->saveAs($pathToSave);

        return redirect()->to(url('storage') . "/$nmber.docx");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $text = 'delete';
        DB::beginTransaction();
        try {
            $user = User::find($id);
            if ($user->id == Auth::user()->id) {

                self::failed("Can't delete yourself!");

                return redirect()->back();
            }
            $user->delete();

            DB::commit();

            self::success("User {$text}d successfully!");

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            self::failed("Failed to $text user! " . $e->getMessage());

            return redirect()->back();
        }
    }
}
