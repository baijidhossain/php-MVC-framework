<?php

class Transaction{
	
	public $id;
	public $method_id;
	public $user_id;
	public $account_id;
	public $txid;
	public $rate_id;
	public $rate;
	public $currency;
	public $amount;
	public $adjustment;
	public $fee;
	public $status;
	public $type;
	public $ipx_account;
	
	
	private $db;
	
	
	public function __construct(){
		
      $this->db = new Database();
	  
    }
	
	
	public function getRateByAmount($methodId, $amount, $uid){
		
		return $this->db->query("SELECT id as rateid,rate FROM rate WHERE type='BUY' AND effective_date <= ? AND status='1' AND starting_amount <= ? AND method_id=? AND (user_id='0' OR user_id=?) ORDER BY starting_amount DESC,effective_date DESC,user_id DESC,id DESC LIMIT 1", TIMESTAMP, $amount, $methodId, $uid)->fetchArray();
		
	}
	
	private function getTransactionInfo($id){
		
		$transaction_query = $this->db->Query("SELECT t.id,t.method_id,t.currency,t.user_id,t.gateway_txid AS reference,r.rate,t.rate_id,t.amount,t.transaction_type FROM transaction AS t LEFT JOIN rate AS r ON r.id=t.rate_id WHERE t.transaction_type=? AND t.status='Pending' AND t.id=?",$this->type, $id);
	
		if($transaction_query->numRows() > 0){
			return $transaction_query->fetchArray();
		}else{
			return false;
		}
	}
	
	
	private function verifyData(){
		
		if(empty($this->method_id) || empty($this->type) || empty($this->user_id) || empty($this->txid) || empty($this->rate) || empty($this->currency) || empty($this->amount) || is_null($this->fee) || empty($this->status)){
			
			return false;
		} 
		
		return true;
	}
	
	
	
	public function makeAdjustment(){
		
		
		if(empty($this->method_id) || empty($this->id) || empty($this->type) || empty($this->user_id) || empty($this->currency) || empty($this->amount)){
			
			return false;
		} 
		
		$txAmount = ($this->type == 'IN' ? $this->amount : -$this->amount);
		
		try{
				
			$this->db->beginTransaction();
			
			$this->db->query("INSERT INTO transaction (method_id, gateway_txid, user_id, amount, currency, parent_txid, transaction_type, status, created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)", $this->method_id, 'Balance Adjustment of #' . $this->id, $this->user_id, $txAmount, $this->currency, $this->id, 'Adjustment', 'Complete', TIMESTAMP); 
			
			$newTxID = $this->db->lastInsertID();
					
			$this->updateBalance($this->amount, $newTxID, true);
			
			$this->db->Commit();
						
			return true;
					
						
		}catch(Exception $e){
						
			$this->db->Rollback();
						
			return false;
		}
		
	}
	
	
	
	public function updatePayPalTX(){
		
		if(!empty($this->id) && !empty($this->status) && !is_null($this->fee)){
			
			$this->type = 'IN';
			
			if($txinfo = $this->getTransactionInfo($this->id)){
				
				$txAmount = $txinfo['rate'] * ($txinfo['amount'] - $this->fee);
				
				try{
				
					$this->db->beginTransaction();
					
					$this->db->Query("UPDATE transaction SET status=? WHERE gateway_txid=? ", $this->status, $this->id); 
					
					if($this->fee > 0){
						
						$this->db->Query("UPDATE transaction SET status=? WHERE transaction_type='Adjustment' AND parent_txid=?", $this->status, $txinfo['id']);	
					}
					
					if($this->status == 'Complete'){
						
						$this->updateBalance($txAmount, $txinfo['id'], true);
						
					}
					
					$this->db->Commit();
						
					return true;
					
						
				}catch(Exception $e){
						
					$this->db->Rollback();
						
					return false;
				}
			}
		}
		
		return false;
	}
	
	
	
	
	public function updateTransactionMethod($newAccount){
		
		if(!empty($this->id) && !empty($newAccount)){
			
			$old = $this->db->Query("SELECT user_id,account_id AS id FROM transaction WHERE id=?", $this->id)->fetchArray();
			
			$method = $this->db->Query("SELECT method_id AS id FROM account WHERE id=? AND user_id=?", $newAccount, $old['user_id']);
			
			if($method->numRows() > 0){
			
				$method = $method->fetchArray();
				
				$changeQuery = $this->db->Query("UPDATE transaction SET account_id=?, method_id=? WHERE id=?",$newAccount, $method['id'], $this->id);
			
				if($changeQuery->affectedRows() > 0){
					
					$this->transactionLog($this->id, 'Witdrawal Account Changed, from '.$old['id'].' to '.$newAccount);
					
					return true;
				}
			}
			
		}
		
		return false;
	}
	
	
	
	
	public function rejectTransaction(){
		
		if(!empty($this->id)){
			
			$reject_query = $this->db->Query("UPDATE transaction SET status='Rejected' WHERE id=? AND status='Pending'", $this->id);
			
			if($reject_query->affectedRows() > 0){
				
				$this->transactionLog($this->id, 'Rejected');
				
				return true;
			}
		}
		
		return false;
		
	}
	
	
	
	
	public function approveTransaction(){
		
		if(!empty($this->amount) && !empty($this->id) && !is_null($this->adjustment)){
			
			$this->type = 'IN';
			
			if($txinfo = $this->getTransactionInfo($this->id)){
				
				$finalRate = $this->getRateByAmount($txinfo['method_id'], $this->amount, $txinfo['user_id']);
			
				$txAmount = $finalRate['rate'] * $this->amount;
				
				$this->status = 'Complete';
				
				$this->user_id = $txinfo['user_id'];
				
				try{
				
					$this->db->beginTransaction();
					
					$this->db->Query("UPDATE transaction SET status='Complete', ipx_account_id=? WHERE id=? AND status='Pending'",$this->ipx_account, $this->id);
					
					if($this->adjustment != 0){
						
						$this->db->Query("INSERT INTO transaction (method_id, gateway_txid, user_id, amount, currency, rate_id, parent_txid, transaction_type, status, created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", $txinfo['method_id'], 'Balance Adjustment of #'.$this->id, $txinfo['user_id'], $this->adjustment, $txinfo['currency'], $txinfo['rate_id'], $this->id, 'Adjustment', $this->status, TIMESTAMP);
						
						if($finalRate['rate'] != $txinfo['rate']){
							
							$this->db->Query("INSERT INTO transaction (method_id, gateway_txid, user_id, amount, currency, rate_id, parent_txid, transaction_type, status, created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", $txinfo['method_id'], 'Rate Adjustment of #'.$this->id, $txinfo['user_id'], round(($this->amount * ($finalRate['rate'] - $txinfo['rate'])) / $txinfo['rate'], 2), $txinfo['currency'], $txinfo['rate_id'], $this->id, 'Adjustment', $this->status, TIMESTAMP);
						}
					}
					
					$this->transactionLog($this->id, 'Approved');
					
					$this->updateBalance($txAmount, $this->id, true);
					
					$this->db->Commit();
						
					return true;
					
						
				}catch(Exception $e){
						
					$this->db->Rollback();
						
					return false;
				}
			}
		}
		
		
		return false;
	}
	
	
	
	
	public function confirmTransaction(){
		
		if(!empty($this->id) && !empty($this->txid)){
			
			$confirmQuery = $this->db->Query("UPDATE transaction SET status='Complete', gateway_txid=?, ipx_account_id=? WHERE id=? AND status='Pending' AND transaction_type='OUT'", $this->txid, $this->ipx_account, $this->id);
		
			if($confirmQuery->affectedRows() > 0){
				
				$this->transactionLog($this->id, 'Confirmed');
				
				return true;
			}
		}
		
		
		return false;
	}
	
	
	
	public function cancelTransaction(){
		
		if(!empty($this->id)){
			
			
			if($txinfo = $this->getTransactionInfo($this->id)){
				
				$this->amount = $txinfo['amount'];
				
				$this->user_id = $txinfo['user_id'];
				
				try{
				
					$this->db->beginTransaction();
					
					$this->db->Query("UPDATE transaction SET status='Canceled' WHERE id=? AND status='Pending'", $this->id);
					
					$this->transactionLog($this->id, 'Canceled');
					
					if($txinfo['transaction_type'] == 'OUT'){
						
						$this->type = 'IN';
						
						$this->updateBalance(($txinfo['amount'] * -1), $this->id, true);
					}
					
					
					$this->db->Commit();
						
					return true;
					
						
				}catch(Exception $e){
						
					$this->db->Rollback();
						
					return false;
				}
				
			}
			
		}
		
		
		return false;
	}
	
	
	
	
	public function makeTransaction(){
		
		if(!$this->verifyData()){
			
			return false;
		}
		
		$txAmount = $this->rate * ($this->amount - $this->fee);
		
		$this->amount = ($this->type == 'OUT' ? -$this->amount : $this->amount);
		
		try{
				
			$this->db->beginTransaction();
			
			$this->db->query("INSERT INTO transaction (method_id, account_id, gateway_txid, user_id, amount, currency, rate_id, transaction_type, status, created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", $this->method_id, $this->account_id, $this->txid, $this->user_id, $this->amount, $this->currency, $this->rate_id, $this->type, $this->status, TIMESTAMP); 
			
			$parentTx = $this->db->lastInsertID();
			
			if($this->fee > 0){
				
				$this->db->Query("INSERT INTO transaction (method_id, gateway_txid, user_id, amount, currency, rate_id, parent_txid, transaction_type, status, created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", $this->method_id, 'Fee Adjustment of #'.$parentTx, $this->user_id, -$this->fee, $this->currency, $this->rate_id, $parentTx, 'Adjustment', $this->status, TIMESTAMP);
				
			}
			
			if($this->status == 'Complete' || $this->type == "OUT"){
				
				$this->updateBalance($txAmount, $parentTx, true);
				
			}
			
			$this->db->Commit();
				
			return true;
			
				
		}catch(Exception $e){
				
			$this->db->Rollback();
				
			return false;
		}
		
	}
	
	
	
	private function updateBalance($amount, $reference=null, bool $inTransaction = false){
		
		if(empty($this->type) || is_null($reference) || empty($this->user_id)){
			
			return false;
		}
		
		
		try{
				
			$uinfo = $this->db->Query("SELECT balance FROM user WHERE id=?", $this->user_id)->fetchArray();
		
			$new_balance = ($this->type == 'IN' ? $uinfo['balance'] + $amount : $uinfo['balance'] - $amount);
			
			$updateType = ($this->type == 'IN' ? 'Credit' : 'Debit');
			
			$logAmount = ($this->type == 'OUT' ? -$amount : $amount);
			
			//Starting Query
			$inTransaction ?: $this->db->beginTransaction();
			
			$this->db->Query("UPDATE user SET balance=? WHERE id=?", $new_balance, $this->user_id);
			
			$this->db->Query("INSERT INTO balance_log (uid, reference, amount, previous_balance, new_balance, updated_by, created) VALUES (?, ?, ?, ?, ?, ?, ?)", $this->user_id, $reference, $logAmount, $uinfo['balance'], $new_balance, $_SESSION['userid'], TIMESTAMP);
			
			$inTransaction ?: $this->db->Commit();
			
			$this->Notify($amount, $new_balance, $reference);
			
			return true;
			
			
		}catch(Exception $e){
			
			$inTransaction ?: $this->db->Rollback();
			
			return false;
		}
		
	}
	
	
	
	private function transactionLog($id, $action){
		
		return $this->db->Query("INSERT INTO action_log (table_name, row_id, action, user, created) VALUES (?, ?, ?, ?, ?)", 'transaction', $id, $action, $_SESSION['userid'], TIMESTAMP);
	}
	
	
	
	private function Notify($amount, $new_amount, $reference){
		
		$n = new Notification;
		
		$n->event = 'BALANCE_UPDATED';
		
		$n->user_id = $this->user_id;
		
		$n->setData('AMOUNT', $amount);
		
		$n->setData('AVAILABLE_BALANCE', $new_amount);
		
		$n->setData('TX_TIME', date("d M Y, h:i A"));
		
		$n->setData('TX_ID', '#'.$reference);
		
		$n->setData('TX_TYPE', ($this->type == 'IN' ? 'Credited' : 'Debited'));
		
		$n->Notify();
		
	}
}