<?php
class WebUser extends CWebUser
{
	public $roles = array();
    /**
     * Overrides a Yii method that is used for roles in controllers (accessRules).
     *
     * @param string $operation Name of the operation required (here, a role).
     * @param mixed $params (opt) Parameters for this operation, usually the object to access.
     * @return bool Permission granted?
     */
    public function checkAccess($operation, $params=array())
    {	
    	if (empty($this->id)) {
            // Not identified => no rights
            return false;
        }
        
        /* only populate if $roles is empty */
        if (empty($this->roles)){
	        $user = User::model()->findByPk($this->id)->with(array('roles'));
	        foreach($user->roles as $role)
	        {
	        	$this->roles[] = strtolower($role->name);
	        }
        }
        
        /* Admin has access to everything so grant it */
        if (in_array('admin',$this->roles)) {
            return true; // admin role has access to everything
        }
        
        if ($operation === 'testlab')
        {
        	if(in_array('testlab engineer',$this->roles))
        		return true;
        		
        	if(in_array('testlab supervisor',$this->roles))
        		return true;
        		
        	if(in_array('testlab tech',$this->roles))
        		return true;
        	
        }
        
        if ($operation === 'manufacturing')
        {
        	if(in_array('manufacturing engineer',$this->roles))
        		return true;
        		
        	if(in_array('manufacturing supervisor',$this->roles))
        		return true;
        		
        	if(in_array('manufacturing coating',$this->roles))
        		return true;
        		
        	if(in_array('manufacturing cell assembly',$this->roles))
        		return true;
        		
        	if(in_array('manufacturing battery assembly',$this->roles))
        		return true;
        }
        
    if ($operation === 'manufacturing tech')
        {   		       		
        	if(in_array('manufacturing coating',$this->roles))
        		return true;
        		
        	if(in_array('manufacturing cell assembly',$this->roles))
        		return true;
        		
        	if(in_array('manufacturing battery assembly',$this->roles))
        		return true;
        }
        
     if ($operation === 'manufacturing battery assembly')
        {   		       		
        	if(in_array('manufacturing battery assembly',$this->roles))
        		return true;
        }
        
        // allow access if the operation request is the current user's role
        return (in_array($operation,$this->roles));
    }
}
