<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTelegramCommandValidationRequest;
use App\Http\Requests\EditTelegramCommandValidationRequest;
use App\Models\TelegramCommand;
use Illuminate\Http\Request;

class TelegramCommandController extends Controller
{
    /**
     * Вызов шаблона со всеми командами
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $telegramCommand = TelegramCommand::all();
        return view('telegramCommand.index', compact('telegramCommand'));
    }

    /**
     * Вызов шаблона создания команды
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('telegramCommand.create');
    }

    /**
     * Функция создания команды
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(CreateTelegramCommandValidationRequest $request)
    {
        TelegramCommand::create($request->validated());
        return back()->with(['success' => true]);
    }

    /**
     * Показ команды
     * Display the specified resource.
     *
     * @param  \App\Models\TelegramCommand  $telegramCommand
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(TelegramCommand $telegramCommand)
    {
        return view('telegramCommand.show', ['command' => $telegramCommand]);
    }

    /**
     * Вызов шаблона редактирования команды
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TelegramCommand  $telegramCommand
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(TelegramCommand $telegramCommand)
    {
        return view('telegramCommand.edit', ['command' => $telegramCommand]);
    }

    /**
     * Редактирование команды
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TelegramCommand  $telegramCommand
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(EditTelegramCommandValidationRequest $request, TelegramCommand $telegramCommand)
    {
        $telegramCommand->update($request->validated());
        return back()->with(['success' => true]);
    }

    /**
     * Удаление команды
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TelegramCommand  $telegramCommand
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function destroy(TelegramCommand $telegramCommand)
    {
        $telegramCommand->delete();
        return back()->with(['successError' => true]);
    }
}
