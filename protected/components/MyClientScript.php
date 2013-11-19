<?php

class MyClientScript extends CClientScript
{
	public function registerCoreScript($sName)
	{
		if (Yii::app()->request->isAjaxRequest)
			return $this;
		
		return parent::registerCoreScript($sName);
	}
}