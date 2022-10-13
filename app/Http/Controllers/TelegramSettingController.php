<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditParametrsValidationRequest;
use App\Http\Requests\ParametrsValidationRequest;
use App\Models\TelegramSetting;
use Illuminate\Http\Request;

class TelegramSettingController extends Controller
{
    /**
     * Вызов страницы со всеми настройками
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $telegramSetting = TelegramSetting::all();
        return view('telegramSetting.index', compact('telegramSetting'));
    }

    /**
     * Вызов страницы для создания настройки
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('telegramSetting.create');
    }

    /**
     * Функция создания настройки
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(ParametrsValidationRequest $request)
    {
        TelegramSetting::create($request->validated());
        return redirect()->route('telegram-setting.index')->with(['success' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TelegramSetting  $telegramSetting
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(TelegramSetting $telegramSetting)
    {

    }

    /**
     * Вызов страницы для редактировния настройки
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TelegramSetting  $telegramSetting
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(TelegramSetting $telegramSetting)
    {
        return view('telegramSetting.edit', compact('telegramSetting'));
    }

    /**
     * Функция редактирования настройки
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TelegramSetting  $telegramSetting
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(EditParametrsValidationRequest $request, TelegramSetting $telegramSetting)
    {
        $telegramSetting->update($request->validated());
        return back()->with(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TelegramSetting  $telegramSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(TelegramSetting $telegramSetting)
    {
        //
    }
}
