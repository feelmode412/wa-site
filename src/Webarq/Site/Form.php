<?php namespace Webarq\Site;

class Form {
	
	// Example 1: echo Site\Form::select('bank_id', $banks)
	// Example 2: echo Site\Form::select('bank_id', $banks, null, array(), '-- None --');
	public function select($name, $list = array(), $selected = null, $options = array(), $nullText = '- Please Select -')
	{
		$list = array('' => $nullText) + $list;
		return \Form::select($name, $list, $selected, $options);
	}

}