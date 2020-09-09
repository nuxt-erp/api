<?php

namespace Modules\ExpensesApproval\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\ExpensesApproval\Entities\ExpensesApproval;

class ExpensesProposalResource extends JsonResource
{

    public function toArray($request)
    {
        $user = auth()->user();
        $actions = collect([]);
        $preview = false;

        if($user->hasRole('buyer') && !$this->purchased_at) {
            $actions->push(collect([
                'name'  => 'Finish Purchase',
                'code'  => 'finish_purchase',
                'type'  => 'success'
            ]));
        } else {
            if($this->status->value === 'pending') {
                if($user->id === $this->author_id && $this->approvals->isEmpty()) {
                    $actions->push(collect([
                        'name'  => 'Edit',
                        'code'  => 'edit_expense',
                        'icon'  => 'edit',
                        'type'  => 'primary'
                    ]));

                    $actions->push(collect([
                        'name'  => 'Delete',
                        'code'  => 'delete_expense',
                        'icon'  => 'delete',
                        'type'  => 'danger'
                    ]));
                } else if($user->id === $this->author_id && $user->id === $this->category->team_leader_id){
                    $actions->push(collect([
                        'name'  => 'Edit',
                        'code'  => 'edit_expense',
                        'icon'  => 'edit',
                        'type'  => 'primary'
                    ]));

                    $actions->push(collect([
                        'name'  => 'Delete',
                        'code'  => 'delete_expense',
                        'icon'  => 'delete',
                        'type'  => 'danger'
                    ]));                
                } else if($user->id === $this->category->team_leader_id || $user->id === $this->category->director_id){


                    $user_approval = ExpensesApproval::where('expenses_proposal_id', $this->id)->where('approver_id', $user->id)->first();

                    if(!$user_approval) {
                        $preview = true;

                        $actions->push(collect([
                            'name'  => 'View Expense',
                            'code'  => 'view_expense',
                            'icon'  => 'search-recipes',
                            'type'  => 'info'
                        ]));

                        $actions->push(collect([
                            'name'  => 'Approve Expense',
                            'code'  => 'approve_expense',
                            'icon'  => 'approve',
                            'type'  => 'success'
                        ]));

                        $actions->push(collect([
                            'name'  => 'Disapprove Expense',
                            'code'  => 'disapprove_expense',
                            'icon'  => 'disapprove',
                            'type'  => 'danger'
                        ]));
                    }                                        
                }
            } else if($this->status->value === 'approved') {
                if (($user->id === $this->author_id && $user->id === $this->category->director_id)
                    ||($user->id === $this->author_id && $user->id === $this->category->team_leader_id && !$this->rule()->director_approval))  {
                    $actions->push(collect([
                        'name'  => 'Delete',
                        'code'  => 'delete_expense',
                        'icon'  => 'delete',
                        'type'  => 'danger'
                    ]));
                }
            }
        }

        return [
            'id'                        => $this->id,
            'expenses_category_id'      => $this->expenses_category_id,
            'expenses_category_name'    => $this->category->name, 
            'author_id'                 => $this->author_id,
            'author_name'               => $this->author->name,
            'item'                      => $this->item,
            'reason'                    => $this->reason,
            'supplier_link'             => $this->supplier_link,
            'subtotal'                  => $this->subtotal,
            'hst'                       => $this->hst,                   
            'ship'                      => $this->ship,
            'total_cost'                => $this->total_cost,            
            'status'                    => $this->status->description,
            'approvers'                 => $this->approvers(),
            'approvals'                 => $this->approvals,
            'attachments'               => $this->attachments,
            'purchase_date'             => optional($this->purchase_date)->format('Y-m-d'),
            'created_at'                => optional($this->created_at)->format('Y-m-d'),
            'updated_at'                => optional($this->updated_at)->format('Y-m-d'),
            'actions'                   => $actions,
            'hide'                      => $this->status->value != 'pending',
            'preview'                   => $preview
        ];
    }
}
