<?php

namespace App\Http\Controllers;

use App\Models\Page_Tutorials;
use App\Models\Pages;
use App\Models\Chat;
use App\Models\Chat_history;
use App\Models\Chat_bot;
use App\Models\User;
use App\Models\Student;
use App\Models\Relationship;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        session()->flash('page', (object)[
            'page' => 'customer-service',
            'child' => 'great care',
        ]);

        $chat = Chat::with('user')
        ->withCount(['history' => function ($query) {
            $query->whereNull('answered')
                ->where('user_id', '!=', 2);
        }])
        ->get();

        foreach($chat as $c){
            if($c->user['role_id'] == 4){
                $c->name = ucwords(strtolower(Student::where('user_id', $c->user_id)->value('name')));
                $c->profil = ucwords(strtolower(Student::where('user_id', $c->user_id)->value('profil')));
            }
            elseif($c->user['role_id'] == 5){
                $c->name = ucwords(strtolower(Relationship::where('user_id', $c->user_id)->value('name')));
                $c->profil = NULL;
            }
        }

        $tutorials = Chat_bot::with('page')->paginate(5);
        $admin = User::where('username', '=', 'administrator')->first();

        return view('components.care.cc', compact('chat', 'tutorials', 'admin'));
    }

    public function detail($id){
        session()->flash('page', (object)[
            'page' => 'customer-service',
            'child' => 'great care',
        ]);

        $chat = Chat::with(['user','history'])->where('id', $id)->first();
        if($chat->user['role_id'] == 4){
            $chat->name = ucwords(strtolower(Student::where('user_id', $chat->user_id)->value('name')));
            $chat->profil = ucwords(strtolower(Student::where('user_id', $chat->user_id)->value('profil')));
        }
        elseif($chat->user['role_id'] == 5){
            $chat->name = ucwords(strtolower(Relationship::where('user_id', $chat->user_id)->value('name')));
            $chat->profil = NULL;
        }

        return view('components.care.detail', compact('chat'));
    }

    public function chat()
    {
        session()->flash('page', (object)[
            'page' => 'customer-service',
            'child' => 'great care',
        ]);

        $today = Carbon::now()->toDateString();
        $questions = Pages::with(['chatbots'])->get();
        $chat = Chat::with(['user' ,'history' => function($query) use($today){
            $query->where('created_at', '=', $today);
        }])->where('user_id', session('id_user'))->first();

        if($chat && optional($chat->user)->role_id == 4){
            $chat->name = ucwords(strtolower(Student::where('user_id', $chat->user_id)->value('name')));
            $chat->profil = ucwords(strtolower(Student::where('user_id', $chat->user_id)->value('profil')));
        }
        elseif($chat && optional($chat->user)->role_id == 5){
            $chat->name = ucwords(strtolower(Relationship::where('user_id', $chat->user_id)->value('name')));
            $chat->profil = NULL;
        }

        return view('components.care.index', compact('questions', 'chat'));
    }

    public function directWhatsapp()
    {
        $phone = User::where('role_id', '=', 2)->value('phone');
        $adminPhone = "62" . $phone; // Gunakan format internasional tanpa tanda "+"
        $message = urlencode("Hello Admin, I need some help.");
        
        return redirect("https://wa.me/$adminPhone?text=$message");
    }

    public function store(Request $request)
    {
        $check = Chat::where('user_id', session('id_user'))->exists();
        if($check){
            $chat = Chat::where('user_id', session('id_user'))->value('id');

            Chat_history::create([
                'chat_id' => $chat,
                'user_id' => session('id_user'),
                'text' => $request->message,
            ]);
            return redirect()->back();
        }
        else{
            $chat = Chat::create([
                    'user_id' => session('id_user'),
                ]);

            Chat_history::create([
                'chat_id' => $chat->id,
                'user_id' => session('id_user'),
                'text' => $request->message,
            ]);
            return redirect()->back();
        }
    }

    public function answer(Request $request)
    {
        $answer = Chat_history::create([
            'chat_id' => $request->id,
            'user_id' => session('id_user'),
            'text' => $request->message,
            'answered' => $request->id,
        ]);

        Chat_history::where('chat_id', $request->id)
            ->where('user_id', '!=', 2)
            ->update([
                'answered' => $request->id, 
        ]);
                
        return redirect()->back();
    }

    public function getMessages()
    {
        $today = Carbon::now()->toDateString();
        $chat = Chat::with(['history' => function($query) use($today){
            $query->where('created_at', '=', $today);
        }])->where('user_id', session('id_user'))
        ->first();

        if($chat->user['role_id'] == 4){
            $chat->name = ucwords(strtolower(Student::where('user_id', $chat->user_id)->value('name')));
            $chat->profil = ucwords(strtolower(Student::where('user_id', $chat->user_id)->value('profil')));
        }
        elseif($chat->user['role_id'] == 5){
            $chat->name = ucwords(strtolower(Relationship::where('user_id', $chat->user_id)->value('name')));
            $chat->profil = NULL;
        }

        return response()->json([
            'messages' => $chat ? $chat->history : [],
            'user_id' => session('id_user'),
            'user_name' => $chat->name,
            'profil' => $chat->profil,
        ]);
    }

    public function getMessagesAdmin($id)
    {
        $chat = Chat::with(['history'])->where('id', $id)->first();

        if($chat->user['role_id'] == 4){
            $chat->name = ucwords(strtolower(Student::where('user_id', $chat->user_id)->value('name')));
            $chat->profil = ucwords(strtolower(Student::where('user_id', $chat->user_id)->value('profil')));
        }
        elseif($chat->user['role_id'] == 5){
            $chat->name = ucwords(strtolower(Relationship::where('user_id', $chat->user_id)->value('name')));
            $chat->profil = NULL;
        }

        return response()->json([
            'messages' => $chat ? $chat->history : [],
            'user_id' => session('id_user'),
            'user_name' => $chat->name,
            'profil' => $chat->profil,
        ]);
    }

    public function notificationMessage()
    {
        $role = session('role');
        if($role == 'superadmin' || $role == 'admin'){
            $notif = Chat_history::whereNull('answered')->count();
            return response()->json($notif);
        }
        elseif($role !== 'superadmin' || $role !== 'admin'){
            $chatId = Chat::where('user_id', session('id_user'))->value('id');
            $notif = Chat_history::where('chat_id', $chatId)
            ->orderBy('id', 'desc') // Atau 'created_at' jika ada timestamp
            ->first();

            $notif = $notif->user_id == 2 ? 1 : 0; 

            return response()->json($notif);
        }
    }

    public function create()
    {
        session()->flash('page', (object)[
            'page' => 'customer-service',
            'child' => 'great care',
        ]);
        $topics = Pages::get();

        return view('components.care.create', compact('topics'));
        
    }

    public function actionCreate(Request $request){
        for($i=0; $i<count($request->page_id); $i++){
            Chat_bot::create([
                'page_id' => $request->page_id[$i],
                'title' => $request->title[$i],
                'description' => $request->answer[$i],
            ]);
        }
        
        return redirect(route('cc.great.care'))->with('success', 'Chat bot has been successfully saved!');
    }
    
    public function edit($id)
    {
        session()->flash('page', (object)[
            'page' => 'customer-service',
            'child' => 'great care',
        ]);

        $data = Chat_bot::where('id', $id)->first();
        $topics = Pages::get();
        
        return view('components.care.edit', compact('data', 'topics'));
    }

    public function actionUpdate(Request $request){
        Chat_bot::where('id', $request->chat_bot_id)->update([
            'title' => $request->title,
            'description' => $request->answer,
        ]);

        return redirect(route('cc.great.care'))->with('success', 'Chat bot has been successfully updated!');;
    }
}
