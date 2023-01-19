<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Models\ContactUsForm;
use Illuminate\Http\Request;

class ContactUsFormController extends Controller
{
    public function store(ContactFormRequest $contactFormRequest)
    {
        $data = filterData($contactFormRequest->validated());
        $form = ContactUsForm::query()->create($data);
        return successResponse(['form' => $form]);
    }

    public function index()
    {
        $forms = ContactUsForm::query()->orderByDesc('id')->get();
        return successResponse([
           'forms' => $forms
        ]);

    }

    public function show(ContactUsForm $contactUsForm)
    {
       return successResponse([
           'form' => $contactUsForm->first(),
       ])  ;
    }
}
