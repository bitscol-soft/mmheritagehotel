<?php

namespace Module\CRM\Controllers;

use App\Models\User;
use App\Models\Country;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Traits\CheckPermission;
use Illuminate\Http\RedirectResponse;
use Module\CRM\Models\CRMCustomer;
use Illuminate\Support\Facades\DB;

/*
 |--------------------------------------------------------------------------
 | CRM CUSTOMER / COMPANY MASTER (revival, BUGS #27)
 |--------------------------------------------------------------------------
 | The c_r_m_customers table backs the company picker on booking, banquet
 | and POS screens (via the CRMCustomer model). This screen makes the rows
 | manageable again after the original CRM module was lost.
 |
 | Permission slugs are `crm.customers.*` (underscored) while the route names
 | are `crm.customers.*` from the resource.
 */

class CustomerController extends Controller
{
    use CheckPermission;

    private function rules(): array
    {
        return [
            'org_name'     => 'required|string|max:191',
            'org_phone'    => 'nullable|string|max:191',
            'org_email'    => 'nullable|email|max:191',
            'c_name'       => 'nullable|string|max:191',
            'c_phone'      => 'nullable|string|max:191',
            'c_email'      => 'nullable|email|max:191',
            'tax_id'       => 'nullable|integer',
            'street'       => 'nullable|string|max:191',
            'city'         => 'nullable|string|max:191',
            'address'      => 'nullable|string|max:191',
            'zip_code'     => 'nullable|integer',
            'country_id'   => 'nullable|integer|exists:countries,id',
            'currency'     => 'nullable|string|max:191',
            'leads'        => 'nullable|boolean',
            'lead_status'  => 'nullable|string|max:191',
            'lead_source'  => 'nullable|string|max:191',
            'assigned'     => 'nullable|string|max:191',
            'contact_date' => 'nullable|date',
        ];
    }

    private function formData(): array
    {
        return [
            'countries'  => Country::orderBy('name')->pluck('name', 'id'),
            'currencies' => Currency::orderBy('name')->pluck('name', 'id'),
            'users'      => User::orderBy('name')->pluck('name', 'id'),
        ];
    }

    public function index(Request $request)
    {
        $this->hasAccess("crm.customers.index");

        $customers = CRMCustomer::query()
            ->when($request->filled('org_name'), fn ($q) => $q->where('org_name', 'like', '%' . $request->org_name . '%'))
            ->when($request->filled('city'), fn ($q) => $q->where('city', $request->city))
            ->latest('id')
            ->get();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $this->hasAccess("crm.customers.create");

        return view('customers.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        $this->hasAccess("crm.customers.create");

        $request->validate($this->rules());

        try {
            CRMCustomer::create($this->payload($request));
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('crm.customers.index')->with('message', 'Customer created successfully!');
    }

    public function edit($id)
    {
        $this->hasAccess("crm.customers.edit");

        $customer = CRMCustomer::findOrFail($id);

        return view('customers.edit', $this->formData() + compact('customer'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->hasAccess("crm.customers.edit");

        $request->validate($this->rules());

        $customer = CRMCustomer::findOrFail($id);

        try {
            $customer->update($this->payload($request));
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('crm.customers.index')->with('message', 'Customer updated successfully!');
    }

    public function destroy($id): RedirectResponse
    {
        $this->hasAccess("crm.customers.delete");

        $customer = CRMCustomer::findOrFail($id);

        try {
            $linked = DB::table('booking')->where('company_id', $id)->count();
        } catch (\Throwable $e) {
            $linked = 0;
        }

        if ($linked > 0) {
            return redirect()->back()->with('error', 'Customer has bookings and cannot be deleted.');
        }

        $customer->delete();

        return redirect()->route('crm.customers.index')->with('message', 'Customer deleted successfully!');
    }

    private function payload(Request $request): array
    {
        $data = $request->except(['_token', '_method']);

        // the form sends a checkbox; absent means "not a lead"
        $data['leads'] = $request->boolean('leads') ? 1 : 0;

        return $data;
    }
}
