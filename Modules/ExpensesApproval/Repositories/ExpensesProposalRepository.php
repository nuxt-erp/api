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
            // OR THAT THE USER IS THE TEAM LEADER OR DIRECTOR APPROVER OF THE EXPENSE CATEGORY
            // THAT HAVE PENDING STATUS
            $this->queryBuilder
                ->whereHas('status', function (Builder $query) {
                    $query->where('value', 'pending');
                })
                ->where(function (Builder $query) use ($user) {
                    $query->where('author_id', $user->id)
                        ->orWhereHas('category', function (Builder $query) use ($user) {
                            $query->where('team_leader_id', $user->id)
                                ->orWhere('sponsor_id', $user->id);
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
            // OR THAT THE USER IS THE TEAM LEADER OR DIRECTOR APPROVER OF THE EXPENSE CATEGORY
            // THAT HAVE STATUS DIFFERENT FROM PENDING
            $this->queryBuilder
                ->whereHas('status', function (Builder $query) {
                    $query->where('value', '<>', 'pending');
                })
                ->where(function (Builder $query) use ($user) {
                    $query->where('author_id', $user->id)
                        ->orWhereHas('category', function (Builder $query) use ($user) {
                            $query->where('team_leader_id', $user->id)
                                ->orWhere('sponsor_id', $user->id);
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
                

                // SAVE ATTCHMENTS
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
        $category = Category::where('id', $data['expenses_category_id'])->first();
        $rule = ExpensesRule::where('start_value', '<', $data['total_cost'])->where('end_value', '>=', $data['total_cost'])->orWhereNull('end_value')->orderBy('start_value')->first();
        $team_leader_required = $rule->team_leader_approval;
        $director_required = $rule->sponsor_approval;
        $pending_id = Parameter::where('name', 'expenses_approval_status')->where('value', 'pending')->pluck('id')->first();
        $approved_id = Parameter::where('name', 'expenses_approval_status')->where('value', 'approved')->pluck('id')->first();
        
        // TEAM LEADER AND DIRECTOR APPROVAL REQUIRED
        if ($team_leader_required && $director_required) {
            // AUTHOR OF EXPENSE IS THE DIRECTOR OF THE CATEGORY, APPROVE DIRECTLY
            if ($user->id === $category->sponsor_id) {
                $data['status_id'] = $approved_id;
            }
            // AUTHOR OF EXPENSE IS THE TEAM LEADER OF THE CATEGORY, APPROVE TEAM LEADER AND WAIT FOR DIRECTOR APPROVAL
            else if ($user->id === $category->team_leader_id) {
                $data['status_id'] = $pending_id;
            }
            // AUTHOR OF EXPENSE IS OTHER USER, WAIT FOR TEAM LEADER AND DIRECTOR APPROVAL
            else {
                $data['status_id'] = $pending_id;
            }
        }
        // ONLY TEAM LEADER APPROVAL REQUIRED
        else if($team_leader_required && !$director_required) {
            // AUTHOR OF EXPENSE IS THE DIRECTOR OR TEAM LEADER OF THE CATEGORY, APPROVE DIRECTLY
            if ($user->id === $category->team_leader_id || $user->id === $category->sponsor_id) {
                $data['status_id'] = $approved_id;
            }
            // AUTHOR OF EXPENSE IS OTHER USER, WAIT FOR TEAM LEADER APPROVAL
            else {
                $data['status_id'] = $pending_id;
            }
        }
        else if(!$team_leader_required && $director_required) {
            // AUTHOR OF EXPENSE IS THE DIRECTOR OF THE CATEGORY, APPROVE DIRECTLY
            if ($user->id === $category->sponsor_id) {
                $data['status_id'] = $approved_id;
            }
            // AUTHOR OF EXPENSE IS OTHER USER, WAIT DIRECTOR APPROVAL
            else {
                $data['status_id'] = $pending_id;
            }
        }
        // NO APPROVAL REQUIRED, APPROVE DIRECTLY
        else {
            $data['status_id'] = $approved_id;
        }

        return $data['status_id'];
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
        $proposal   = ExpensesProposal::find($id);
        $rule       = $proposal->rule();
        $team_leader_required = $rule->team_leader_approval;
        $director_required = $rule->sponsor_approval;
        $team_leader_approval = $team_leader_required ? ($proposal->author_id===$proposal->category->team_leader_id ? true : ExpensesApproval::where('expenses_proposal_id', $proposal->id)->where('approver_id', $proposal->category->team_leader_id)->first()) : null;
        $sponsor_approval = $director_required ? ExpensesApproval::where('expenses_proposal_id', $proposal->id)->where('approver_id', $proposal->category->sponsor_id)->first() : null;
        $approved_id = Parameter::where('name', 'expenses_approval_status')->where('value', 'approved')->pluck('id')->first();

        if ($team_leader_required && $director_required && $team_leader_approval && $sponsor_approval) {
            $proposal->status_id = $approved_id;
        } else if($team_leader_required && !$director_required && $team_leader_approval) {
            $proposal->status_id = $approved_id;
        } else if(!$team_leader_required && !$director_required) {
            $proposal->status_id = $approved_id;
        }

        $proposal->save();

        if ($proposal->status_id === $approved_id) {
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

        $buyer = $proposal->category->buyer->email;

        $this->sendEmail( [$buyer], $data);
    }

    public function sendEmailApprovers($proposal)
    {   
        $to = [];
       
        if ($proposal->rule()->team_leader_approval && $proposal->author_id !== $proposal->category->team_leader_id && $proposal->author_id !== $proposal->category->sponsor_id) {
            $to[] = $proposal->category->team_leader->email;
        }

        if ($proposal->rule()->sponsor_approval && $proposal->author_id !== $proposal->category->sponsor_id ) {
            $to[] = $proposal->category->sponsor->email;
        }

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
