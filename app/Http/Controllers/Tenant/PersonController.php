<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Requests\Tenant\PersonRequest;
use App\Http\Resources\Tenant\PersonCollection;
use App\Http\Resources\Tenant\PersonResource;
use App\Imports\PersonsImport;
use App\Models\Tenant\Catalogs\Country;
use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Catalogs\District;
use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Models\Tenant\Catalogs\Province;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Person;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\DB;

class PersonController extends Controller
{
    public function index($type)
    {
        return view('tenant.persons.index', compact('type'));
    }

    public function view($type, Person $person)
    {
        $totals = DB::connection('tenant')->table('persons as per')
            ->select(DB::raw('(SELECT SUM(total) FROM documents AS doc WHERE doc.customer_id = per.id) AS total'),
                DB::raw('(SELECT SUM(total) FROM sale_notes AS san WHERE san.customer_id = per.id) AS total2'),
                DB::raw('(SELECT SUM(total_paid) FROM documents AS doc WHERE doc.customer_id = per.id) AS total_paid'),
                DB::raw('(SELECT SUM(total_paid) FROM sale_notes AS doc WHERE doc.customer_id = per.id) AS total_paid2'))
            ->where('per.type', 'customers')
            ->where('per.id', $person->id)
            ->first();

        return view('tenant.persons.view', compact('person', 'totals'));
    }

    public function columns()
    {
        return [
            'name' => 'Nombre',
            'number' => 'Número'
        ];
    }

    public function records($type, Request $request)
    {
        $records = Person::where($request->column, 'like', "%{$request->value}%")
                            ->where('type', $type)
                            ->orderBy('name');

        return new PersonCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
    }

    public function create()
    {
        return view('tenant.customers.form');
    }

    public function tables()
    {
        $countries = Country::whereActive()->orderByDescription()->get();
        $departments = Department::whereActive()->orderByDescription()->get();
        $provinces = Province::whereActive()->orderByDescription()->get();
        $districts = District::whereActive()->orderByDescription()->get();
        $identity_document_types = IdentityDocumentType::whereActive()->get();

        return compact('countries', 'departments', 'provinces', 'districts', 'identity_document_types');
    }

    public function record($id)
    {
        $record = new PersonResource(Person::findOrFail($id));

        return $record;
    }

    public function store(PersonRequest $request)
    {
        $id = $request->input('id');
        $person = Person::firstOrNew(['id' => $id]);
        $person->fill($request->all());
        $person->save();

        return [
            'success' => true,
            'message' => ($id)?'Cliente editado con éxito':'Cliente registrado con éxito',
            'id' => $person->id
        ];
    }

    public function destroy($id)
    {
        $person = Person::findOrFail($id);
        $person->delete();

        return [
            'success' => true,
            'message' => 'Cliente eliminado con éxito'
        ];
    }

    public function import(Request $request)
    {
        if ($request->hasFile('file')) {
            try {
                $import = new PersonsImport();
                $import->import($request->file('file'), null, Excel::XLSX);
                $data = $import->getData();
                return [
                    'success' => true,
                    'message' =>  __('app.actions.upload.success'),
                    'data' => $data
                ];
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' =>  $e->getMessage()
                ];
            }
        }
        return [
            'success' => false,
            'message' =>  __('app.actions.upload.error'),
        ];
    }
}