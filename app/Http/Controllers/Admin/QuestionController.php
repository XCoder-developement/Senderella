<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\QuestionDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Question\StoreRequest;
use App\Http\Requests\Admin\Question\UpdateRequest;
use App\Models\Question\Question;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class QuestionController extends Controller
{
    protected $view = 'admin_dashboard.questions.';
    protected $route = 'questions.';


    public function index(QuestionDataTable $dataTable)
    {
        return $dataTable->render($this->view . 'index');
    }

    public function create()
    {
        return view($this->view . 'create');
    }


    public function store(StoreRequest $request)
    {
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = [
                'question' => $request['question-' . $localeCode],
                'answer' => $request['answer-' . $localeCode],

            ];
        }


        Question::create($data);


        return redirect()->route($this->route . "index")
            ->with(['success' => __("messages.createmessage")]);
    }

    public function edit($id)
    {
        $question = Question::whereId($id)->firstOrFail();
        return view($this->view . 'edit', compact('question'));
    }


    public function update(UpdateRequest $request, $id)
    {
        $question = Question::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = [
                'question' => $request['question-' . $localeCode],
                'answer' => $request['answer-' . $localeCode],

            ];
        }


        $question->update($data);


        return redirect()->route($this->route . "index")
            ->with(['success' => __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $question = Question::whereId($id)->firstOrFail();
        $question->delete();
        return response()->json(['status' => true]);
    }
}//End of controller
