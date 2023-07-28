<?php
namespace App\Http\Controllers;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function getOne(Request $request, $id)
    {
        $userSession = $request->get('userdata');
        $expense     = Expense::find($id);
        
        if(!$expense || $expense->user_id != $userSession->user_id)
            return response()->json(['error' => 'Expense não encontrada'], 400);

        return response()->json($expense, 200);
    }

    public function getAll(Request $request){
        $userSession = $request->get('userdata');
        $expenses = Expense::where('user_id', $userSession->user_id)->get(); 
        
        if(!$expenses)
            return response()->json(['error' => 'Não contém expenses para este usuário!'], 400);

        return response()->json($expenses, 200);
    }
}