<?php

namespace Modules\ExpensesApproval\Repositories;

use App\Models\Parameter;
use App\Models\User;
use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Modules\ExpensesApproval\Entities\Category;
use Modules\ExpensesApproval\Entities\ExpensesApproval;
use Modules\ExpensesApproval\Entities\ExpensesAttachment;
use Modules\ExpensesApproval\Entities\ExpensesProposal;
use Modules\ExpensesApproval\Entities\ExpensesRule;
use Snowfire\Beautymail\Beautymail;

class ExpensesProposalRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {
        if (!empty($searchCriteria['date'])) {

            $this->queryBuilder
                ->whereDate('created_at', '>=', $searchCriteria['date'][0])
                ->whereDate('created_at', '<=', $searchCriteria['date'][1]);
        }

        if (!empty($searchCriteria['status'])) {
            $status_id = Parameter::where('name', 'expenses_approval_status')->where('value', Arr::pull($searchCriteria, 'status'))->pluck('id')->first();
            $searchCriteria['status_id'] = $status_id;
        }

        return parent::findBy($searchCriteria);
    }

    public function getPendingProposals(array $searchCriteria = [])
    {
        $user = auth()->user();

        // FOR ADMIN, GET ALL THE PENDING PROPOSALS
        if($user->hasRole('admin')) {
            $this->queryBuilder
                ->whereHas('status', function (Builder $query) {
                    $query->where('value', 'pending');
                });
        }
        // FOR BUYERS, GET ALL THE APPROVED PROPOSALS ONLY
        else if($user->hasRole('buyer')) {
            $this->queryBuilder
                ->whereHas('status', function (Builder $query) {
                    $query->where('value', 'approved');
                });
        } else {
            // GET ALL THE EXPENSES PROPOSALS CREATED BY THE USER
            // OR THAT THE USER IS THE LEAD OR SPONSOR APPROVER OF THE EXPENSE CATEGORY
            // THAT HAVE PENDING STATUS
            $this->queryBuilder
                ->whereHas('status', function (Builder $query) {
                    $query->where('value', 'pending');
                })
                ->where(function (Builder $query) use ($user) {
                    $query->where('author_id', $user->id)
                    ->orWhereHas('category', function (Builder $query1) use ($user) {
                        $query1->where('lead_id', $user->id)
                        ->orWhereHas('sponsors', function (Builder $query2) use ($user) {
                            $query2->where('users.id', $user->id);
                        });
                    });
                });


        }

        return $this->findBy($searchCriteria);
    }

    public function getProcessedProposals(array $searchCriteria = [])
    {
        $user = auth()->user();

        // FOR ADMIN, GET ALL PROPOSALS THAT HAVE STATUS DIFFERENT FROM PENDING
        if($user->hasRole('admin')) {
            $this->queryBuilder
                ->whereHas('status', function (Builder $query) {
                    $query->where('value', '<>', 'pending');
                });
        }
        // FOR BUYERS, GET ALL THE PURCHASED PROPOSALS ONLY
        else if($user->hasRole('buyer')) {
            $this->queryBuilder
                ->whereHas('status', function (Builder $query) {
                    $query->where('value', 'purchased');
                });
        } else {
            // GET ALL THE EXPENSES PROPOSALS CREATED BY THE USER
            // OR THAT THE USER IS THE LEAD OR SPONSOR APPROVER OF THE EXPENSE CATEGORY
            // THAT HAVE STATUS DIFFERENT FROM PENDING
            $this->queryBuilder
                ->whereHas('status', function (Builder $query) {
                    $query->where('value', '<>', 'pending');
                })
                ->where(function (Builder $query) use ($user) {
                    $query->where('author_id', $user->id)
                    ->orWhereHas('category', function (Builder $query1) use ($user) {
                        $query1->where('lead_id', $user->id)
                        ->orWhereHas('sponsors', function (Builder $query2) use ($user) {
                            $query2->where('users.id', $user->id);
                        });
                    });
                });

        }
        return $this->findBy($searchCriteria);
    }

    public function store($data)
    {
        DB::transaction(function () use ($data)
        {
            $attachments = $data['attachments_list'];

            $user = auth()->user();
            $data['author_id'] = $user->id;

            // STATUS OF THE EXPENSE IS DEFINED BASED ON THE USER AND THE EXPENSES RULES
            $data['status_id'] = $this->updateStatus($data, $user);

            parent::store($data);

            if($this->model) {
                // IF EXPENSE IS AUTOMATICALLY APPROVED, SEND EMAIL
                if ($this->model->status === 'Approved') {
                    $this->sendEmailApproved($this->model);
                } else {
                    // SEND EMAIL TO APPROVERS
                    $this->sendEmailApprovers($this->model);
                }


                // SAVE ATTACHMENTS
                if($attachments) {
                    foreach($attachments as $attachment) {
                        ExpensesAttachment::create([
                            'expenses_proposal_id'  => $this->model->id,
                            'file_name'             => $attachment['file_name']
                        ]);
                    }
                }
            }
        });
    }

    public function update($model, array $data)
    {

        DB::transaction(function () use ($data, $model)
        {
            $user = auth()->user();

            $attachments = $data['attachments_list'];

            $original_rule = $model->rule();

            // BUYER FINISH PURCHASE - SAVE PURCHASE DATE
            if(isset($data['buyer_role']) && $data['buyer_role']) {

                $purchased_id = Parameter::where('name', 'expenses_approval_status')->where('value', 'purchased')->pluck('id')->first();
                $data['status_id'] = $purchased_id;
                $data['purchase_date'] = now();
            } else {
                // UPDATE STATUS IF THERE IS CHANGE ON TOTAL COST
                if($model->total_cost !== $data['total_cost']) {
                    $data['status_id'] = $this->updateStatus($data, $user);
                }
            }

            // SAVE UPDATED EXPENSES PROPOSAL DATA
            parent::update($model, $data);

            // SAVE UPDATED EXPENSES PROPOSAL ATTACHMENTS
            if($attachments) {
                foreach($attachments as $attachment) {

                    $item = ExpensesAttachment::where('expenses_proposal_id', $this->model->id)->where('file_name', $attachment['file_name'])->first();

                    if(!$item){
                        ExpensesAttachment::create([
                            'expenses_proposal_id'  => $this->model->id,
                            'file_name'             => $attachment['file_name']
                        ]);
                    }
                }
            }

            // CHECK IF RULE CHANGED AND ANY APPROVAL EMAIL NEEDS TO BE SENT
            if($this->model->rule()->id !== $original_rule->id) {
                // IF EXPENSE IS AUTOMATICALLY APPROVED, SEND EMAIL
                if ($this->model->status === 'Approved') {
                    $this->sendEmailApproved($this->model);
                } else {
                    // SEND EMAIL TO APPROVERS
                    $this->sendEmailApprovers($this->model);
                }
            }

            // BUYER FINISH PURCHASE - SEND PURCHASE CONFIRMATION EMAIL
            if(isset($data['buyer_role']) && $data['buyer_role']) {

                $data = [
                    'id'            => $this->model->id,
                    'user_name'     => $this->model->author->name,
                    'category'      => $this->model->category->name,
                    'item'          => $this->model->item,
                    'supplier_link' => $this->model->supplier_link,
                    'subtotal'      => $this->model->subtotal,
                    'hst'           => $this->model->hst,
                    'ship'          => $this->model->ship,
                    'total_cost'    => $this->model->total_cost,
                    'type'          => 'purchased',
                ];

                $this->sendEmail([$this->model->author->email], $data);
            }

        });
    }

    private function updateStatus($data, $user) {

        $category           = Category::where('id', $data['expenses_category_id'])->first();

        $rule               = ExpensesRule::where('start_value', '<', $data['total_cost'])
                            ->where('end_value', '>=', $data['total_cost'])
                            ->orWhereNull('end_value')
                            ->orderBy('start_value')
                            ->first();

        $pending_id         = Parameter::where('name', 'expenses_approval_status')->where('value', 'pending')->pluck('id')->first();
        $approved_id        = Parameter::where('name', 'expenses_approval_status')->where('value', 'approved')->pluck('id')->first();
        $approved           = TRUE;

        $primary_sponsor    = $category->sponsors && count($category->sponsors) > 0 ? $category->sponsors[0] : null;

        $is_primary_author  = !empty($primary_sponsor) && $user->id == $primary_sponsor->id;
        $is_lead_author     = $user->id == $category->lead_id;
        $is_other_author    = FALSE;

        $other_sponsors     = [];
        if($category->sponsors){
            foreach ($category->sponsors as $key => $sponsor) {
                if($key > 0){
                    $other_sponsors[] = $sponsor;
                    if($sponsor->id == $user->id){
                        $is_other_author = TRUE;
                    }
                }
            }
        }

        // NEED LEAD APPROVAL
        if($rule->lead_approval && !$is_primary_author && !$is_other_author && !$is_lead_author){
            $approved = $user->id == $category->lead_id;
        }

        // NEED PRIMARY SPONSOR APPROVAL
        if($rule->sponsor_approval && $approved && !$is_primary_author){
            $approved = $approved && $user->id == $primary_sponsor->id;
        }

        // NEED OTHERS SPONSOR APPROVAL
        if($rule->others_sponsor_approval && $approved && !$is_other_author){
            $approved = FALSE;
        }

        return $approved ? $approved_id : $pending_id;
    }


    public function approveProposal($id)
    {
        // SAVE APPROVAL
        $user = auth()->user();

        ExpensesApproval::create([
            'expenses_proposal_id'  => $id,
            'approver_id'           => $user->id
        ]);


        // CHANGE STATUS OF EXPENSES PROPOSAL
        $proposal           = ExpensesProposal::find($id);
        $rule               = $proposal->rule();
        $approved_status_id = Parameter::where('name', 'expenses_approval_status')->where('value', 'approved')->pluck('id')->first();
        $approved           = TRUE;

        $primary_sponsor    = optional($proposal->category)->sponsors && count($proposal->category->sponsors) > 0 ? $proposal->category->sponsors[0] : null;

        $is_primary_author  = !empty($primary_sponsor) && $proposal->author_id == $primary_sponsor->id;
        $is_lead_author     = $proposal->author_id == $proposal->category->lead_id;
        $is_other_author    = FALSE;

        $other_sponsors     = [];
        if($proposal->category->sponsors){
            foreach ($proposal->category->sponsors as $key => $sponsor) {
                if($key > 0){
                    $other_sponsors[] = $sponsor;
                    if($sponsor->id == $proposal->author_id){
                        $is_other_author = TRUE;
                    }
                }
            }
        }


        // NEED LEAD APPROVAL
        if($rule->lead_approval && !$is_primary_author && !$is_other_author && !$is_lead_author){
            $approved = ExpensesApproval::where('expenses_proposal_id', $proposal->id)->where('approver_id', $proposal->category->lead_id)->count() > 0;
        }

        // NEED PRIMARY SPONSOR APPROVAL
        if($rule->sponsor_approval && $approved && !$is_primary_author && $primary_sponsor){
            $approved = $approved && ExpensesApproval::where('expenses_proposal_id', $proposal->id)->where('approver_id', $primary_sponsor->id)->count() > 0;
        }

        // NEED OTHERS SPONSOR APPROVAL
        if($rule->others_sponsor_approval && $approved && !$is_other_author){
            foreach ($other_sponsors as $sponsor) {
                $approved = $approved && ExpensesApproval::where('expenses_proposal_id', $proposal->id)->where('approver_id', $sponsor->id)->count() > 0;
            }
        }

        if ($approved) {
            $proposal->status_id = $approved_status_id;
            $proposal->save();
            $this->sendEmailApproved($proposal);
        }

        return $proposal;
    }

    public function disapproveProposal($id)
    {
        $proposal  = ExpensesProposal::find($id);
        $denied_id = Parameter::where('name', 'expenses_approval_status')->where('value', 'denied')->pluck('id')->first();
        $proposal->status_id = $denied_id;
        $proposal->save();

        $data = [
            'id'            => $proposal->id,
            'user_name'     => $proposal->author->name,
            'category'      => $proposal->category->name,
            'item'          => $proposal->item,
            'supplier_link' => $proposal->supplier_link,
            'subtotal'      => $proposal->subtotal,
            'hst'           => $proposal->hst,
            'ship'          => $proposal->ship,
            'total_cost'    => $proposal->total_cost,
            'type'          => 'denied',
        ];

        $this->sendEmail([$proposal->author->email], $data);

        return $proposal;
    }

    public function cancelProposal($id)
    {
        ExpensesApproval::where('expenses_proposal_id', $id)->delete();

        $proposal  = ExpensesProposal::find($id);

        $original_status = $proposal->status;

        $pending_id = Parameter::where('name', 'expenses_approval_status')->where('value', 'pending')->pluck('id')->first();
        $proposal->status_id = $pending_id;
        $proposal->save();

        if($original_status === 'Approved') $proposal['hide'] = true;

        return $proposal;
    }

    public function sendEmailApproved($proposal)
    {
        $data = [
            'id'            => $proposal->id,
            'user_name'     => $proposal->author->name,
            'category'      => $proposal->category->name,
            'item'          => $proposal->item,
            'supplier_link' => $proposal->supplier_link,
            'subtotal'      => $proposal->subtotal,
            'hst'           => $proposal->hst,
            'ship'          => $proposal->ship,
            'total_cost'    => $proposal->total_cost,
            'type'          => 'approved',
        ];

        $this->sendEmail([$proposal->author->email], $data);

        $data['type'] = 'buyer';

        $this->sendEmail( [$proposal->category->buyer->email], $data);
    }

    public function sendEmailApprovers($proposal)
    {
        $to = [];

        // LEAD APPROVAL
        if ($proposal->rule()->lead_approval && $proposal->author_id !== $proposal->category->lead_id) {
            $to[] = $proposal->category->lead->email;
        }

        // SPONSOR APPROVAL
        if ($proposal->rule()->sponsor_approval) {
            foreach ($proposal->category->sponsors as $user) {
                if($proposal->author_id != $user->id){
                    $to[] = $user->email;
                }
            }
        }

        if(!empty($to)){
            $data = [
                'id'            => $proposal->id,
                'user_name'     => $proposal->author->name,
                'category'      => $proposal->category->name,
                'item'          => $proposal->item,
                'supplier_link' => $proposal->supplier_link,
                'subtotal'      => $proposal->subtotal,
                'hst'           => $proposal->hst,
                'ship'          => $proposal->ship,
                'total_cost'    => $proposal->total_cost,
                'type'          => 'approval',
            ];

            $this->sendEmail($to, $data);
        }
    }

    public function sendEmail(array $to, $data)
    {
        try {
            $beautymail = app()->make(Beautymail::class);
            $beautymail->send('expenses_approval.email', $data, function($message) use ($data, $to)
            {
                $message->from(config('mail.from.address'))
                        ->subject('Purchase Order Request #' . $data['id'] . ' - User ' . strtoupper($data['user_name']));

                foreach ($to as $email) {
                    $message->bcc($email);
                }
            });
        } catch (\Throwable $th) {
            lad('Error to send email');
        }
    }


}
