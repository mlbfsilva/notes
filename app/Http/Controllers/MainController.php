<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use App\Services\Operations;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class MainController extends Controller
{
    public function index()
    {
        // load user's notes
        $id = session('user.id');
        $notes = User::find($id)->notes()->get()->toArray();

        
        //show home view
        return view('home', ['notes' => $notes]);
    }

    public function newNote()
    {
        return view('new_note');
    }

    public function newNoteSubmit(Request $request)
    {
         $request->validate(
            [
                'text_title' => 'required|min:3|max:200', 
                'text_note' => 'required|min:3|max:3000',
            ],
            //error messages
            [
                'text_title.required' => 'O título é obrigatório.',
                'text_title.min' => 'O título deve ter pelo menos :min carácteres',
                'text_title.max' => 'O título deve ter no máximo :max carácteres',
                
                'text_note.required' => 'O preenchimento do texto é obrigatório.',
                'text_note.min' => 'A nota deve ter pelo menos :min carácteres',
                'text_note.max' => 'A nota deve ter no máximo :max carácteres'
            ]
        );
        $id = session('user.id');

        $note = new Note();
        $note->user_id = $id;
        $note->title = request()->text_title;
        $note->text = request()->text_note;
        $note->save();

        return redirect()->route('home');
    }

    public function editNote($id)
    {
        // $id = $this->decryptId($id);
        $id = Operations::decryptId($id);
        $note = Note::find($id);
        return view('edit_note', ['note'=> $note]);
    }

    public function editNoteSubmit(Request $request)
    {
        $request->validate(
            [
                'text_title' => 'required|min:3|max:200', 
                'text_note' => 'required|min:3|max:3000',
            ],
            //error messages
            [
                'text_title.required' => 'O título é obrigatório.',
                'text_title.min' => 'O título deve ter pelo menos :min carácteres',
                'text_title.max' => 'O título deve ter no máximo :max carácteres',
                
                'text_note.required' => 'O preenchimento do texto é obrigatório.',
                'text_note.min' => 'A nota deve ter pelo menos :min carácteres',
                'text_note.max' => 'A nota deve ter no máximo :max carácteres'
            ]
        );
        if($request->note_id == null){ //checar se a nota existe pelo id
            return redirect()->route('home');
        }

        $id = Operations::decryptId($request->note_id); // desencriptar o id

        $note = Note::find($id); //carregar a nota

        $note->title = $request->text_title; //atualizar os campos das notas
        $note->text= $request->text_note;
        $note->save();

        return redirect()->route('home');
    }

    public function deleteNote($id)
    {
        // $id = $this->decryptId($id);
        $id = Operations::decryptId($id);
        echo "Eu estou deletando a note com o id = $id";
    }
}

