<?php

namespace Modules\ExpensesApproval\Repositories;

use App\Models\Parameter;
use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Modules\ExpensesApproval\Entities\Category;
use Modules\ExpensesApproval\Entities\ExpensesApproval;
use Modules\ExpensesApproval\Entities\ExpensesProposal;
use Modules\ExpensesApproval\Entities\ExpensesRule;

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
            $status_id = Parameter::where('value', Arr::pull($searchCriteria, 'status'))->pluck('id')->first();
            $searchCriteria['status_id'] = $status_id;
        }

        

        return parent::findBy($searchCriteria);
    }

    public function getPendingProposals(array $searchCriteria = [])
    {
        $user = auth()->user();        
        // FOR BUYERS, GET ALL THE APPROVED PROPOSALS ONLY
        if($user->hasRole('buyer')) {
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
                                ->orWhere('director_id', $user->id);                    
                        });
                });
                
                
        }

        return $this->findBy($searchCriteria);
    }

    public function getProcessedProposals(array $searchCriteria = [])
    {
        $user = auth()->user();
        
        // FOR BUYERS, GET ALL THE PURCHASED PROPOSALS ONLY
        if($user->hasRole('buyer')) {
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
                                ->orWhere('director_id', $user->id);                    
                        });
                });
                
        }
        return $this->findBy($searchCriteria);
    }

    public function store($data)
    {
        DB::transaction(function () use ($data)
        {
            // STATUS OF THE EXPENSE IS DEFINED BASED ON THE USER AND THE EXPENSES RULES
            $user = auth()->user();
            $data['author_id'] = $user->id;

            $category = Category::where('id', $data['expenses_category_id'])->first();
            $rule = ExpensesRule::where('start_value', '<', $data['total_cost'])->where('end_value', '>=', $data['total_cost'])->orWhereNull('end_value')->orderBy('start_value')->first();
            $team_leader_required = $rule->team_leader_approval;
            $director_required = $rule->director_approval;
            $pending_id = Parameter::where('value', 'pending')->pluck('id')->first();
            $approved_id = Parameter::where('value', 'approved')->pluck('id')->first();

            // TEAM LEADER AND DIRECTOR APPROVAL REQUIRED
            if ($team_leader_required && $director_required) {
                // AUTHOR OF EXPENSE IS THE DIRECTOR OF THE CATEGORY, APPROVE DIRECTLY
                if ($user->id === $category->director_id) {
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
                if ($user->id === $category->team_leader_id || $user->id === $category->director_id) {
                    $data['status_id'] = $approved_id;
                }
                // AUTHOR OF EXPENSE IS OTHER USER, TEAM LEADER
                else {
                    $data['status_id'] = $pending_id;
                }
            } 
            // NO APPROVAL REQUIRED, APPROVE DIRECTLY
            else {
                $data['status_id'] = $approved_id;
            }
           
            parent::store($data);

            // TODO: save attachments
            
        });
    }

    public function update($model, array $data)
    {

        DB::transaction(function () use ($data, $model)
        {
            // TODO: update attachments                      
            
            parent::update($model, $data); 
        });
              
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
        $director_required = $rule->director_approval;
        $team_leader_approval = $team_leader_required ? ExpensesApproval::where('expenses_proposal_id', $proposal->id)->where('approver_id', $proposal->category->team_leader_id)->first() : null;
        $director_approval = $director_required ? ExpensesApproval::where('expenses_proposal_id', $proposal->id)->where('approver_id', $proposal->category->director_id)->first() : null;
        $approved_id = Parameter::where('value', 'approved')->pluck('id')->first();

        if ($team_leader_required && $director_required && $team_leader_approval && $director_approval) {
            $proposal->status_id = $approved_id;
        } else if($team_leader_required && !$director_required && $team_leader_approval) {
            $proposal->status_id = $approved_id;
        }

        $proposal->save();  

        return $proposal;
    }

    public function disapproveProposal($id)
    {
        $proposal  = ExpensesProposal::find($id);
        $denied_id = Parameter::where('value', 'denied')->pluck('id')->first();      
        $proposal->status_id = $denied_id;
        $proposal->save();

        return $proposal;
    }
}
