<?php
class WebUser extends CWebUser
{
	public $roles = array();
	public $department_id;
	
	public function getDept()
    {	
    	if (empty($this->id)) {
            // Not identified => no rights
            return false;
        }
        
        /* only populate if $roles is empty */
        if (empty($this->department_id)){
	        $user = User::model()->findByPk($this->id);
	        $this->department_id = $user->depart_id;
        }
        return $this->department_id;
    }
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
        
        $operation = array_map('trim',explode(',', $operation));
        
        if (in_array('testlab',$operation))
        {
        	if(in_array('testlab engineer',$this->roles))
        		return true;
        		
        	if(in_array('testlab supervisor',$this->roles))
        		return true;
        		
        	if(in_array('testlab tech',$this->roles))
        		return true;
        }
        
    	if (in_array('testlab tech',$operation))
        {
        	if(in_array('testlab engineer',$this->roles))
        		return true;
        		
        	if(in_array('testlab supervisor',$this->roles))
        		return true;
        		
        	if(in_array('testlab tech',$this->roles))
        		return true;
        }
        
    	if (in_array('testlab supervisor',$operation))
        {
        	if(in_array('testlab engineer',$this->roles))
        		return true;
        		
        	if(in_array('testlab supervisor',$this->roles))
        		return true;
        }
        
   	 	if (in_array('testlab engineer',$operation))
        {
        	if(in_array('testlab engineer',$this->roles))
        		return true;
        		
        	if(in_array('testlab supervisor',$this->roles))
        		return true;
        }
        
        if (in_array('manufacturing',$operation))
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
        
    	if (in_array('manufacturing tech',$operation))
        {   		       		
        	if(in_array('manufacturing coating',$this->roles))
        		return true;
        		
        	if(in_array('manufacturing cell assembly',$this->roles))
        		return true;
        		
        	if(in_array('manufacturing battery assembly',$this->roles))
        		return true;
        		
        	if(in_array('manufacturing engineer',$this->roles))
        		return true;
        		
        	if(in_array('manufacturing supervisor',$this->roles))
        		return true;
        }
        
     	if (in_array('manufacturing battery assembly', $operation))
        {   		       		
        	if(in_array('manufacturing battery assembly',$this->roles))
        		return true;
        		
        	if(in_array('manufacturing engineer',$this->roles))
        		return true;
        		
        	if(in_array('manufacturing supervisor',$this->roles))
        		return true;
        }
        
    	if(in_array('manufacturing coating', $operation))
        {   		       		
        	if(in_array('manufacturing coating',$this->roles))
        		return true;
        		
        	if(in_array('manufacturing engineer',$this->roles))
        		return true;
        		
        	if(in_array('manufacturing supervisor',$this->roles))
        		return true;
        }
        
   		 if(in_array('manufacturing supervisor', $operation))
        {   		       			
        	if(in_array('manufacturing engineer',$this->roles))
        		return true;
        		
        	if(in_array('manufacturing supervisor',$this->roles))
        		return true;
        }
        
    	if(in_array('manufacturing engineer', $operation))
        {   		       			
        	if(in_array('manufacturing engineer',$this->roles))
        		return true;
        		
        	if(in_array('manufacturing supervisor',$this->roles))
        		return true;
        }
        
        if(in_array('quality', $operation))
        {   
        	if(in_array('quality',$this->roles))
        		return true;
        }
        
   	  	if(in_array('engineering', $operation))
        {   
        	if(in_array('engineering',$this->roles))
        		return true;
        }
        
        // allow access if the operation request is the current user's role
        return (in_array($operation,$this->roles));
    }
}
