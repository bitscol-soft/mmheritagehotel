<?php

namespace Module\CRM\Controllers;

use Illuminate\Http\Request;
use App\Traits\CheckPermission;
use Illuminate\Http\RedirectResponse;
use Module\CRM\Models\MailTemplate;

/*
 |--------------------------------------------------------------------------
 | CRM MAIL TEMPLATES (revival, BUGS #27)
 |--------------------------------------------------------------------------
 | Backs the crm:send-invoice scheduled command (MailTemplate::where('status',
 | 1)->first() is the template it mails out).
 */

class MailTemplateController extends Controller
{
    use CheckPermission;

    private function rules(): array
    {
        return [
            'subject' => 'required|string|max:191',
            'body'    => 'required|string|max:191',
            'status'  => 'required|boolean',
        ];
    }

    public function index()
    {
        $this->hasAccess("crm.mail_templates.index");

        $templates = MailTemplate::query()->latest('id')->get();

        return view('mail-templates.index', compact('templates'));
    }

    public function create()
    {
        $this->hasAccess("crm.mail_templates.create");

        return view('mail-templates.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->hasAccess("crm.mail_templates.create");

        $request->validate($this->rules());

        try {
            MailTemplate::create($request->except('_token'));
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('crm.mail-templates.index')->with('message', 'Mail template created successfully!');
    }

    public function edit($id)
    {
        $this->hasAccess("crm.mail_templates.edit");

        $template = MailTemplate::findOrFail($id);

        return view('mail-templates.edit', compact('template'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->hasAccess("crm.mail_templates.edit");

        $request->validate($this->rules());

        $template = MailTemplate::findOrFail($id);

        try {
            $template->update($request->except(['_token', '_method']));
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('crm.mail-templates.index')->with('message', 'Mail template updated successfully!');
    }

    public function destroy($id): RedirectResponse
    {
        $this->hasAccess("crm.mail_templates.delete");

        MailTemplate::findOrFail($id)->delete();

        return redirect()->route('crm.mail-templates.index')->with('message', 'Mail template deleted successfully!');
    }
}
