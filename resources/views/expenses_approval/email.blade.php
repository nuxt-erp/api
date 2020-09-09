@extends('beautymail::templates.minty')

@section('content')

	@include('beautymail::templates.minty.contentStart')
		
		<tr>
			<td width="100%" height="10"></td>
		</tr>
		<tr>
			<td class="title">
				@if ($type === 'approval')
					Expense Approval
				@elseif ($type === 'approved')
					Expense Approved
				@elseif ($type === 'denied')
					Expense Denied
				@elseif ($type === 'buyer')
					Expense Purchase
				@elseif ($type === 'purchased')
					Expense Purchased
				@endif
			</td>
		</tr>
		<tr>
			<td width="100%" height="25"></td>
		</tr>
		<tr>
			<td class="paragraph">
				@if ($type === 'approval')
					A new expense proposal was created by <strong>{{ strtoupper($user_name) }}</strong>, and needs your approval.
				@elseif ($type === 'approved')
					Hi <strong>{{ strtoupper($user_name) }}</strong>, the expense proposal bellow was approved. <br>
					You will receive a new notification when the purchase is completed.
				@elseif ($type === 'denied')
					Hi <strong>{{ strtoupper($user_name) }}</strong>, the expense proposal bellow was not approved. <br>
					Contact your team leader for more details.
				@elseif ($type === 'buyer')
					A new expense proposal was approved. 
				@elseif ($type === 'purchased')
					Hi <strong>{{ strtoupper($user_name) }}</strong>, the expense proposal bellow was already purchased.
				@endif
			</td>
		</tr>
		<tr>
			<td width="100%" height="10"></td>
		</tr>
		<tr>
			<td class="paragraph">
				Category: <strong>{{$category}} </strong>
			</td>
		</tr>	
		<tr>
			<td class="paragraph">
				Item description: <strong>{{$item}} </strong>
			</td>
		</tr>
		        @if (!empty($supplier_link))
		<tr>
			<td class="paragraph">
				Supplier link: <strong>{{ $supplier_link }}</strong>
			</td>
		</tr>
		@endif
		<tr>
			<td class="paragraph">
				Subtotal: <strong>CA${{ number_format($subtotal, 2) }} </strong>
			</td>
		</tr>
		<tr>
			<td class="paragraph">
				HST: <strong>CA${{ number_format($hst, 2) }} </strong>
			</td>
		</tr>
		<tr>
			<td class="paragraph">
				Ship: <strong>CA${{ number_format($ship, 2) }} </strong>
			</td>
		</tr>
		<tr>
			<td class="paragraph">
				Total Cost: <strong>CA${{ number_format($total_cost, 2) }}
			</td>
		</tr>
		<tr>
			<td width="100%" height="15"></td>
		</tr>
		<tr>
			<td class="paragraph">			
				@if ($type === 'approval')
					Sign in to Approve or Deny this expense.		
				@elseif ($type === 'buyer')
					Sign in to see all the expenses waiting to be purchased.
				@else
					Sign in to see all your expense proposals.
				@endif	
			</td>
		</tr>
		<tr>
			<td width="100%" height="25"></td>
		</tr>		
		<tr>
			<td>
				@include('beautymail::templates.minty.button', ['text' => 'Sign in', 'link' => '#'])
			</td>

		</tr>

		<tr>
			<td width="100%" height="25"></td>
		</tr>
		
	@include('beautymail::templates.minty.contentEnd')

@stop