<?php


namespace Module\Account\Services;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Account\Models\AccountGroup;

class TransactionLedgerReportService
{
    public function getTrialBalanceReportData(Request $request)
    {
        return AccountGroup::with(['accountControls' => function ($q) use ($request) {
            $q->with(['accountSubsidiaries' => function ($qr) use ($request) {
                $qr->with(['accounts' => function ($qur) use ($request) {
                    $from = $request->routeIs('report.trial-balance') ? '1580-01-01' : fdate($request->from ?? today());
                    $to = fdate($request->to ?? today());

                    $qur->withCount(['transaction_items as debit' => function ($quer) use ($from, $to) {
                        $quer->select(DB::Raw('SUM(debit_amount)'))
                            ->searchByField('company_id')
                            ->where('date', '>=',  $from)
                            ->where('date', '<=',  $to);
                    }])->withCount(['transaction_items as credit' => function ($quer) use ($from, $to) {
                        $quer->select(DB::Raw('SUM(credit_amount)'))
                            ->searchByField('company_id')
                            ->where('date', '>=',  $from)
                            ->where('date', '<=',  $to);
                    }]);
                }]);
            }]);
        }])->get();
    }










    /*
     |--------------------------------------------------------------------------
     | INCOME STATEMENT
     |--------------------------------------------------------------------------
    */
    public function getIncomeStatementReportData(Request $request)
    {

        $data['search'] = $search = $request->month ?? $request->year ?? date('Y');

    
        // REVENUE 
        $data['revenues']       = $this->accountGroupSummary($account_group_id = 4, $query = DB::Raw('SUM(credit_amount - debit_amount)'), $search);

        // purchases
        $data['purchases']      = $this->accountGroupSummary($account_group_id = 6, $query = DB::Raw('SUM(debit_amount - credit_amount)'), $search);
        // exenses
        $data['expenses']       = $this->accountGroupSummary($account_group_id = 5, $query = DB::Raw('SUM(debit_amount - credit_amount)'), $search);

        //depreciations
        $data['depreciations']  = $this->accountGroupSummary($account_group_id = 9, $query = DB::Raw('SUM(credit_amount - debit_amount)'), $search);

        //equity
        $data['equity']         = $this->accountGroupSummary($account_group_id = 3, $query = DB::Raw('SUM(credit_amount - debit_amount)'), $search);




        return $data;


        /**
         * NON USED DATA, REMOVE WILL IN FUTRUE VERSION
         */

        // purchases
        $data['purchases'] = AccountGroup::where('id', 6)
            ->with(['accountControls' => function ($q) use ($request) {
                $q->with(['accounts' => function ($qur) use ($request) {
                    $from = fdate($request->from ?? today());
                    $to = fdate($request->to ?? today());

                    $qur->withCount(['transaction_items as balance' => function ($quer) use ($request, $from, $to) {
                        $quer->select(DB::Raw('SUM(debit_amount - credit_amount)'))
                            // ->searchByField('company_id')
                            ->when(request()->filled('company_id'), function ($q) {
                                $q->whereIn('company_id', request('company_id'));
                            });
                    }]);
                }]);
            }])->get()->sum(function ($item) {

                return $item->accountControls->sum(function ($control) {
                    return $control->accounts->sum('balance');
                });
            });



        // exenses
        $data['expenses'] = AccountGroup::where('id', 5)
            ->with(['accountControls' => function ($q) use ($request) {
                $q->with(['accounts' => function ($qur) use ($request) {
                    $from = fdate($request->from ?? today());
                    $to = fdate($request->to ?? today());

                    $qur->withCount(['transaction_items as balance' => function ($quer) use ($request, $from, $to) {
                        $quer->select(DB::Raw('SUM(debit_amount - credit_amount)'))
                            // ->searchByField('company_id')
                            ->when(request()->filled('company_id'), function ($q) {
                                $q->whereIn('company_id', request('company_id'));
                            });
                    }]);
                }]);
            }])->get()->sum(function ($item) {

                return $item->accountControls->sum(function ($control) {
                    return $control->accounts->sum('balance');
                });
            });



        // exenses
        $data['depreciations'] = AccountGroup::where('id', 9)
            ->with(['accountControls' => function ($q) use ($request) {
                $q->with(['accounts' => function ($qur) use ($request) {
                    $from = fdate($request->from ?? today());
                    $to = fdate($request->to ?? today());

                    $qur->withCount(['transaction_items as balance' => function ($quer) use ($request, $from, $to) {
                        $quer->select(DB::Raw('SUM(debit_amount - credit_amount)'))
                            // ->searchByField('company_id')
                            ->when(request()->filled('company_id'), function ($q) {
                                $q->whereIn('company_id', request('company_id'));
                            });
                    }]);
                }]);
            }])->get()->sum(function ($item) {

                return $item->accountControls->sum(function ($control) {
                    return $control->accounts->sum('balance');
                });
            });



        // equity
        $data['equity'] = AccountGroup::where('id', 3)
            ->with(['accountControls' => function ($q) use ($request) {
                $q->with(['accounts' => function ($qur) use ($request) {
                    $from = fdate($request->from ?? today());
                    $to = fdate($request->to ?? today());

                    $qur->withCount(['transaction_items as balance' => function ($quer) use ($request, $from, $to) {
                        $quer->select(DB::Raw('SUM(credit_amount - debit_amount)'))
                            // ->searchByField('company_id')
                            ->when(request()->filled('company_id'), function ($q) {
                                $q->whereIn('company_id', request('company_id'));
                            });
                    }]);
                }]);
            }])->get()->sum(function ($item) {

                return $item->accountControls->sum(function ($control) {
                    return $control->accounts->sum('balance');
                });
            });





        return $data;
    }












    /*
     |--------------------------------------------------------------------------
     | EQUITY STATEMENT
     |--------------------------------------------------------------------------
    */
    public function getEquityStatementReportData(Request $request)
    {


        // REVENUE
        $data['revenues'] = AccountGroup::whereIn('id', [4])->with(['accountControls' => function ($q) use ($request) {
            $q->with(['accounts' => function ($qur) use ($request) {
                $from = fdate($request->from ?? today());
                $to = fdate($request->to ?? today());

                $qur->withCount(['transaction_items as balance' => function ($quer) use ($request, $from, $to) {
                    $quer->select(DB::Raw('SUM(credit_amount - debit_amount)'))
                        ->searchByField('company_id');
                }]);
            }]);
        }])->get()->sum(function ($item) {

            return $item->accountControls->sum(function ($control) {
                return $control->accounts->sum('balance');
            });
        });



        // purchases
        $data['purchases'] = AccountGroup::where('id', 6)
            ->with(['accountControls' => function ($q) use ($request) {
                $q->with(['accounts' => function ($qur) use ($request) {
                    $from = fdate($request->from ?? today());
                    $to = fdate($request->to ?? today());

                    $qur->withCount(['transaction_items as balance' => function ($quer) use ($request, $from, $to) {
                        $quer->select(DB::Raw('SUM(debit_amount - credit_amount)'))
                            ->searchByField('company_id');
                    }]);
                }]);
            }])->get()->sum(function ($item) {

                return $item->accountControls->sum(function ($control) {
                    return $control->accounts->sum('balance');
                });
            });



        // exenses
        $data['expenses'] = AccountGroup::where('id', 5)
            ->with(['accountControls' => function ($q) use ($request) {
                $q->with(['accounts' => function ($qur) use ($request) {
                    $from = fdate($request->from ?? today());
                    $to = fdate($request->to ?? today());

                    $qur->withCount(['transaction_items as balance' => function ($quer) use ($request, $from, $to) {
                        $quer->select(DB::Raw('SUM(debit_amount - credit_amount)'))
                            ->searchByField('company_id');
                    }]);
                }]);
            }])->get()->sum(function ($item) {

                return $item->accountControls->sum(function ($control) {
                    return $control->accounts->sum('balance');
                });
            });



        // exenses
        $data['depreciations'] = AccountGroup::where('id', 9)
            ->with(['accountControls' => function ($q) use ($request) {
                $q->with(['accounts' => function ($qur) use ($request) {
                    $from = fdate($request->from ?? today());
                    $to = fdate($request->to ?? today());

                    $qur->withCount(['transaction_items as balance' => function ($quer) use ($request, $from, $to) {
                        $quer->select(DB::Raw('SUM(debit_amount - credit_amount)'))
                            ->searchByField('company_id');
                    }]);
                }]);
            }])->get()->sum(function ($item) {

                return $item->accountControls->sum(function ($control) {
                    return $control->accounts->sum('balance');
                });
            });


        return $data;
    }


    /*
    |------------------------------------------------------------------------------------------------------------------
    | ACCOUNT GROUP SUM DATA BY ID AND QUERY    
    |------------------------------------------------------------------------------------------------------------------
    */
    public function accountGroupSummary($id, $query, $search)
    {
        return AccountGroup::query()
                            ->where('id', $id)
                            ->with(['accountControls' => function ($q) use ($query, $search) {
                                $q->with(['accounts' => function ($qur) use ($query, $search) {
                                    $qur
                                    ->withCount(['transaction_items as balance' => function ($quer) use ($query, $search) {
                                        $company_ids = request('company_id');
                                        if (!is_array(request('company_id'))) {
                                            $company_ids = [request('company_id')];
                                        }
                                        $quer->where('date', 'LIKE', $search . '%')
                                        ->select($query)
                                            // ->searchByField('company_id');
                                            ->when(request()->filled('company_id'), function ($q) use($company_ids) {
                                                $q->whereIn('company_id', $company_ids);
                                            });
                                    }]);
                                }]);
                            }])
                            ->first();
    }
}
