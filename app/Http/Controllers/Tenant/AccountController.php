<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\AccountType;
use App\Models\Tenant\Account;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\AccountRequest;
use App\Http\Resources\Tenant\AccountCollection;
use App\Http\Resources\Tenant\AccountResource;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class AccountController extends Controller
{
    public function index()
    {
        return view('tenant.accounts.index');
    }

    public function columns()
    {
        return [
            'name' => 'Nombre'
        ];
    }

    public function records(Request $request)
    {
        $records = Account::where($request->column, 'like', "%{$request->value}%")
            ->orderBy('date_of_issue');

        return new AccountCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
    }

    public function create()
    {
        return view('tenant.items.form');
    }

    public function tables()
    {
        $currency_types = CurrencyType::whereActive()->orderByDescription()->get();
        $account_types = AccountType::whereActive()->orderByDescription()->get();

        return compact('currency_types', 'account_types');
    }

    public function record($id)
    {
        $record = new AccountResource(Account::findOrFail($id));

        return $record;
    }

    public function store(AccountRequest $request)
    {
        $id = $request->input('id');
        
        $expense = Account::firstOrNew(['id' => $id]);

        if(is_null($request->input('id')))
        {
            $expense->current_balance = $request->input('beginning_balance');
        }

        $expense->fill($request->all());
        $expense->save();

        return [
            'success' => true,
            'message' => ($id) ? 'Cuenta editada con éxito' : 'Cuenta registrada con éxito',
            'id' => $expense->id
        ];
    }

    public function destroy($id)
    {
        $expense = Account::findOrFail($id);
        $expense->delete();

        return [
            'success' => true,
            'message' => 'Gasto eliminado con éxito'
        ];
    }
}
