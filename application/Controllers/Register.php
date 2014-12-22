<?php
namespace Application\Controllers;

use Application\App;
use Slim\Slim;
//use Library\SFacebook;
use Application\H;
use Application\PublicException;
use Application\Helpers\JsonResponse;

class Register
{
	protected $FIRST_NAME_MAX_LEN = 100;
	protected $LAST_NAME_MAX_LEN  = 100;

    public function index()
    {
		//die("Submitted!");
		try{
			$request = App::$inst->request;

			#accept post only
			if ($request->isPost()){

				if (!$request->post('firstName')){
					throw new PublicException('First name is a mandatory field.');
				}
		    	elseif (strlen(trim($request->post('firstName'))) > $this->FIRST_NAME_MAX_LEN) {
	    			throw new PublicException("First name is too long, it can contain up to $this->FIRST_NAME_MAX_LEN characters.");
				}
				elseif (!preg_match("/^[A-za-z0-9_-]{1,$this->FIRST_NAME_MAX_LEN}$/", trim($request->post('firstName')))){
					throw new PublicException('First name can contain only alphabetical characters, digits, underscores and dashes.');	
				}

				if (!$request->post('lastName')){
					throw new PublicException('Last name is a mandatory field.');
				}
		    	elseif (strlen(trim($request->post('lastName'))) > $this->LAST_NAME_MAX_LEN) {
	    			throw new PublicException('Last name is too long, it can contain up to $this->LAST_NAME_MAX_LEN characters.');
				}
				elseif (!preg_match("/^[A-za-z0-9_-]{1,$this->LAST_NAME_MAX_LEN}$/", trim($request->post('lastName')))){
					throw new PublicException('Last name can contain only alphabetical characters, digits, underscores and dashes.');	
				}

				//elseif (){#email is already in use
				//	throw new PublicException('Last name can contain only alphabetical characters, digits, underscores and dashes.');	
				//}

				if (!$request->post('email')){
					throw new PublicException('Email is a mandatory field.');
				}
				elseif (strlen(trim($request->post('email'))) > 254 ){
					throw new PublicException("Email is too long, it can contain up to 254 characters.");
				}
				elseif (!filter_var(trim($request->post('email')), FILTER_VALIDATE_EMAIL)){
					throw new PublicException("Invalid email format.");

				}


				if (!$request->post('birthDate')){
					throw new PublicException('Birth date is a mandatory field.');
				}
				elseif (!strtotime(trim($request->post('birthDate')))){
					throw new PublicException('Birth date is in wrong format.');
				}
				else {
					$user_dob = strtotime(trim($request->post('birthDate')));
					//date in the future
					if ( time() <= $user_dob ){
						throw new PublicException("Date of birth cannot be in the future.");	
					}
					//under 18
					$age_req =  strtotime('+18 years', $user_dob);	
					if (time() < $age_req){
						throw new PublicException("User is under 18 years.");
					}
					//date is to ancient
					//if ($user_dob <= strtotime('-120 years', time());){
					//	throw new PublicException("User is more than 120 year old.");	
					//}
				}



				if (!$request->post('terms') || strtolower(trim($request->post('terms'))) !== 'true'){
					throw new PublicException('Terms and conditions are not accepted.');
				}

				if (!$request->post('rules') || strtolower(trim($request->post('rules'))) !== 'true'){
					throw new PublicException('Sweepstake rules are not accepted.');	
				}

				$repoUser = \DB::user();
				$em = \DB::em();
				$user = $repoUser->newEntity();
				$user['first_name'] = trim($request->post('firstName'));
				$user['last_name'] = trim($request->post('lastName'));
				$user['email'] = trim($request->post('email')); 
				//$user['birth_date'] = strtotime(trim($request->post('birthDate')));
				$user['birth_date'] = new \DateTime();
				$user['toc_accepted'] = true;
				$user['rules_accepted'] = true;

				//not mandatory parameter
				if (!$request->post('receiveEmails')){
					$user['receive_emails'] = false;
				} 
				elseif (strtolower(trim($request->post('receiveEmails'))) === 'true') {
					$user['receive_emails'] = true;
				}
				else {
					$user['receive_emails'] = false;
				}

				$user['is_winner'] = false;
				//$em->beginTransaction();
				try {
					$repoUser->update($user);
					$em->flush();
					//$em->commit();
					//mysql read lock$user->flush();
				} catch (\Exception $e) {
					if ($e instanceof  \PDOException && $e->getCode() === '23000'){
						if( preg_match( ".*Duplicate entry.*", $e->getMessage())) {
							$email = trim($request->post('email'));
							throw new PublicException("Email address $email is already used.");
						}
					} else {
						$ep = $e->getPrevious();
						if ($ep && $ep instanceof \PDOException && $ep->getCode() === '23000'){
							if( preg_match( "/.*Duplicate entry.*/", $ep->getMessage())) {
								$email = trim($request->post('email'));
								throw new PublicException ("Email address $email is already used.");
							}
						}
					}
					throw $e;
				}
				$this->checkWinner($user);
			}
			else {
				throw new PublicException ("Post method should be used.");
			}
			//JsonResponse::success($res)->responseToJson();
		} catch(\Exception $e) {
			if($e instanceof \Application\PublicException){
            	JsonResponse::error($e, null, $e->getMessage());
            }
            else{
				JsonResponse::error($e);
			}
		}
    }

     /**
     * check if user is a winner
     *
     * @param string $id
     * @return boolean
     */
    protected function checkWinner($user)
    {
 	    $em = \DB::em();
	    $query = $em->createQuery("SELECT gift FROM Entity\Gift gift where gift.winning_time < :time_to_check 
	    																	and gift.winning_time >= CURRENT_DATE() 
	    																	and gift.winning_time < DATE_ADD(CURRENT_DATE(),1, 'day') 
	    																	and gift.user is NULL");
	    $query->setMaxResults(2);
	    $query->setParameters(array(
	       	'time_to_check' => $user['created']
	    ));
	    $gifts_available = $query->getResult();
	    if ($gifts_available && count($gifts_available) > 0) {
	    	foreach ($gifts_available as $gift) {
	    		$em->beginTransaction();
				try {
					$gift_free = $em->find('Entity\Gift', $gift['id'], \Doctrine\DBAL\LockMode::PESSIMISTIC_WRITE );
					if (!$gift_free['user']) {
						$gift_free['user'] = $user;
						$em->flush();
						$em->commit();
						break;
					}
					else {
						$em->rollback();
					}

				}
				catch (\Exception $e){
					$em->rollback();
					throw $e;
				}
			}
	    }
	}
}
