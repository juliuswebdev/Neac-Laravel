<?php

namespace App\Http\Controllers;
use App\User;
use App\Mails;
use Mail;
use Illuminate\Http\Request;

class UserMailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:applicant-mail|employee-mail');
    }
    
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $attachments = [];
        $fullurls = [];
        if ($request->hasFile('attachments')) {
            $files = $request->file('attachments');
            foreach($files as $file) {
                $destinationPath = 'documents/'; // upload path
                $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = $name ."_nurse_". time() . "." . $file->getClientOriginalExtension();
                $file->move($destinationPath, $fileName);
                $attachments[] = $fileName;
                $fullurls[] =  env('APP_URL').'/documents/'.$fileName;
            }
        }

        $user = User::find($request->input('user_id'));
        $body = $request->input('messages');

        $data = [
            'body'          => $body,
            'full_name'     => $user->first_name .' '. $user->last_name,
            'to'            => ($request->input('to')) ? explode(',',$request->input('to')) : '',
            'cc'            => ($request->input('cc')) ? explode(',',$request->input('cc')) : '',
            'bcc'           => ($request->input('bcc')) ? explode(',',$request->input('bcc')) : '',
            'subject'       => $request->input('subject'),
            'attachments'   => implode(',', $fullurls),
        ];

        $mail = Mail::send('mails.mail', $data, function($message) use ($data) {
            $message->to($data['to'], $data['full_name'])
            ->subject($data['subject']);
            $message->from('support@applynclex.com','NEAC Medical Exams Center');
            if($data['cc']) {
                $message->cc($data['cc']);
            }
            if($data['bcc']) {
                $message->bcc($data['bcc']);
            }
            if($data['attachments']) {
                $message->attach($data['attachments']);
            }
        });

        if(  count(Mail::failures()) > 0 ) {
            $message = array(
                'message' => 'Email sending Failed!',
                'alert' => 'danger'
            );
        } else {
            Mails::create( array_merge($request->all(), ['attachments' => implode(',',$attachments)] ) );
            $message = array(
                'message' => 'Email Sent!',
                'alert' => 'success'
            );
        }

        return redirect()->back()->with($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $user = User::find($id);
        $mails = Mails::where('to', $user->email)->orderBy('id', 'DESC')->get();
        return view('applicants.mail.index')->with([
            'user' => $user,
            'mails' => $mails
        ]);
    }

    public function view($id)
    {
        //
        $mail = Mails::find($id);
        return view('applicants.mail.show')->with('mail', $mail);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

}
