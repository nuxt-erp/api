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

        $primary_sponsor    = $this->category->sponsors && count($this->category->sponsors) > 0 ? $this->category->sponsors[0] : null;

        $is_user_primary_sponsor    = $primary_sponsor && $primary_sponsor->id == $user->id;
        $is_author_primary_sponsor  = $primary_sponsor && $primary_sponsor->id == $this->author_id;

        $is_author_lead             = $this->author_id == $this->category->lead_id;
        $is_user_lead               = $user->id == $this->category->lead_id;

        $is_user_other_sponsor      = FALSE;
        $is_author_other_sponsor    = FALSE;

        $other_sponsors     = [];
        if($this->category->sponsors){
            foreach ($this->category->sponsors as $key => $sponsor) {
                if($key > 0){
                    $other_sponsors[] = $sponsor;
                    if($sponsor->id == $user->id){
                        $is_user_other_sponsor = TRUE;
                    }
                    if($sponsor->id == $this->author_id){
                        $is_author_other_sponsor = TRUE;
                    }
                }
            }
        }

        if($user->hasRole('buyer') && $this->status->value==='approved') {
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
                } else if($user->id === $this->category->lead_id || $is_user_primary_sponsor){

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
                    } else {
                        $actions->push(collect([
                            'name'  => 'Cancel Expense',
                            'code'  => 'cancel_expense',
                            'icon'  => 'undo',
                            'type'  => 'danger'
                        ]));
                    }
                }
            } else if($this->status->value === 'approved') {
                if ($this->category->sponsors->contains($user->id) || ($user->id === $this->category->lead_id && $is_author_primary_sponsor)) {
                    $actions->push(collect([
                        'name'  => 'Cancel Expense',
                        'code'  => 'cancel_expense',
                        'icon'  => 'undo',
                        'type'  => 'danger'
                    ]));
                }
            }
        }

        $approvals = null;

        if($this->status->value !== 'pending' && !$this->approvals->isEmpty()){
            foreach($this->approvals as $item) {
                if($approvals){
                    $approvals .= ', ' . $item->approver->name;
                } else {
                    $approvals .= $item->approver->name;
                }
            }
        } else {
            if($this->status->value !== 'denied') $approvals = 'pre-approved';
        }

        $approvers = '';

        // LEAD APPROVAL
        if($this->rule()->lead_approval && $this->category) {
            if(!$is_author_lead && !$is_user_lead && !$is_author_primary_sponsor) {
                $lead_approval = ExpensesApproval::where('expenses_proposal_id', $this->id)->where('approver_id', $this->category->lead_id)->first();
                if(!$lead_approval) {
                    $approvers .= $this->category->lead->name;
                }
            }
        }

        // PRIMARY SPONSOR
        if($this->rule()->sponsor_approval && $primary_sponsor) {
            // dont show current user
            if(!$is_user_primary_sponsor && !$is_author_primary_sponsor) {
                $sponsor_approval = ExpensesApproval::where('expenses_proposal_id', $this->id)->where('approver_id', $primary_sponsor->id)->first();
                // remove approvals made
                if(!$sponsor_approval) {
                    $approvers .= ($approvers ? ', ' : ' ') . $primary_sponsor->name;
                }
            }
        }

        if($this->rule()->others_sponsor_approval && !empty($other_sponsors)) {
            foreach ($other_sponsors as $key => $sponsor_user) {
                $sponsor_approval = ExpensesApproval::where('expenses_proposal_id', $this->id)->where('approver_id', $sponsor_user->id)->first();
                // remove approvals made
                if(!$sponsor_approval && $sponsor_user->id != $user->id){ // dont show current user
                    $approvers .= ($approvers ? ', ' : ' ') . $sponsor_user->name;
                }
            }
        }

        return [
            'id'                        => $this->id,
            'expenses_category_id'      => $this->expenses_category_id,
            'expenses_category_name'    => optional($this->category)->name,
            'subcategory_id'            => $this->subcategory_id,
            'subcategory_name'          => optional($this->subcategory)->name,
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
            'approvers'                 => $user->hasRole('buyer') ? $approvals : $approvers,
            'approvals'                 => $approvals,
            'attachments'               => $this->attachments,
            'purchase_date'             => optional($this->purchase_date)->format('Y-m-d'),
            'created_at'                => optional($this->created_at)->format('Y-m-d'),
            'updated_at'                => optional($this->updated_at)->format('Y-m-d'),
            'actions'                   => $actions,
            'hide'                      => $this->hide ? $this->hide : $this->status->value != 'pending',
            'preview'                   => $preview
        ];
    }
}
